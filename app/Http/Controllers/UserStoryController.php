<?php

namespace App\Http\Controllers;

use App\Models\Story;
use App\Models\Category;
use App\Models\Hashtag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserStoryController extends Controller
{
    /**
     * Display a listing of the user's stories.
     */
    public function index(Request $request)
    {
        $query = Story::query()->where('status', 'published');
        
        // Add search functionality
        if ($request->filled('search')) {
            $search = $request->input('search');
            
            // Check if search term starts with '#' to search hashtags specifically
            if (strpos($search, '#') === 0) {
                // Remove the # symbol for searching
                $hashtagSearch = substr($search, 1);
                
                $query->whereHas('hashtags', function($q) use ($hashtagSearch) {
                    $q->where('name', 'like', "%{$hashtagSearch}%");
                });
            } else {
                // Search in story titles
                $query->where('name', 'like', "%{$search}%")
                    // Also search in hashtags if not specifically looking for hashtag
                    ->orWhereHas('hashtags', function($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            }
        }
        
        // Existing filter by category
        if ($request->filled('category')) {
            $categoryId = $request->input('category');
            $query->whereHas('categories', function($q) use ($categoryId) {
                $q->where('categories.id', $categoryId);
            });
        }
        
        // Existing sorting options
        $sort = $request->input('sort', 'newest');
        
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'most_viewed':
                $query->orderByDesc('views');
                break;
            default:
                $query->orderByDesc('created_at');
                break;
        }
        
        $stories = $query->with(['author', 'categories'])->paginate(15);
        $stories->appends($request->only(['search', 'category', 'sort']));
        
        $categories = Category::orderBy('name')->get();
        $featuredStories = Story::where('status', 'published')
            ->orderByDesc('views')
            ->take(3)
            ->get();
    
        return view('user.stories.index', compact('stories', 'categories', 'featuredStories'));
    }

    /**
     * Show the form for creating a new story.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $hashtags = Hashtag::orderBy('name')->get();
        return view('user.stories.create', compact('categories', 'hashtags'));
    }

    /**
     * Store a newly created story in storage.
     */
    public function store(Request $request)
    {
        // Validate dữ liệu đầu vào
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'summary' => 'required|string',
            'content' => 'required|string',
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:categories,id',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|string|in:draft,published',
            'age_rating' => 'required|string',
            'language' => 'required|string',
            'hashtags' => 'nullable|string',
        ]);

        // Quan trọng: Chuyển đổi từ "published" sang "pending" khi người dùng muốn gửi duyệt
        if ($validatedData['status'] == 'published') {
            $validatedData['status'] = 'pending';
        }

        // Thêm author_id từ người dùng đang đăng nhập
        $validatedData['author_id'] = Auth::id();

        // Xử lý upload ảnh bìa nếu có
        if ($request->hasFile('cover_image')) {
            $imagePath = $request->file('cover_image')->store('covers', 'public');
            $validatedData['cover_image'] = $imagePath;
        }

        // Tạo truyện mới với trạng thái đã được điều chỉnh
        $story = Story::create($validatedData);

        // Attach categories
        if (!empty($validatedData['category_ids'])) {
            $story->categories()->attach($validatedData['category_ids']);
        }

        // Handle hashtags
        if (!empty($validatedData['hashtags'])) {
            $hashtags = json_decode($validatedData['hashtags']);
            foreach ($hashtags as $tagName) {
                $story->hashtags()->create(['name' => trim($tagName)]);
            }
        }

        // Thêm thông báo thành công phù hợp
        $successMessage = $validatedData['status'] == 'pending' 
            ? 'Truyện đã được gửi và đang chờ phê duyệt từ quản trị viên' 
            : 'Truyện đã được lưu dưới dạng bản nháp';
            
        return redirect()->route('user.stories.dashboard')->with('success', $successMessage);
    }

    /**
     * Display the specified story.
     */
    public function show($id)
    {
        $story = Story::where('author_id', Auth::id())
            ->with(['categories', 'hashtags'])
            ->findOrFail($id);
            
        return view('user.stories.show', compact('story'));
    }

    /**
     * Show the form for editing the specified story.
     */
    public function edit($id)
    {
        // Get the story that belongs to the current user
        $story = Story::where('author_id', Auth::id())
            ->with(['categories', 'hashtags'])
            ->findOrFail($id);

        // Get all categories
        $categories = Category::orderBy('name')->get();

        // Get story hashtags as comma-separated string
        $hashtags = $story->hashtags->pluck('name')->implode(',');

        return view('user.stories.edit', compact('story', 'categories', 'hashtags'));
    }

    /**
     * Update the specified story in storage.
     */
    public function update(Request $request, $id)
    {
        $story = Story::where('author_id', Auth::id())->findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'summary' => 'required|string',
            'content' => 'required|string',
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:categories,id',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,published',
            'age_rating' => 'required|in:all,13+,16+,18+',
            'language' => 'required|in:vi,en',
            'hashtags' => 'nullable|string'
        ]);

        // Kiểm tra nếu người dùng muốn chuyển từ bản nháp sang xuất bản
        if ($story->status == 'draft' && $validatedData['status'] == 'published') {
            $validatedData['status'] = 'pending';
            $successMessage = 'Truyện đã được gửi và đang chờ phê duyệt';
        } else {
            $successMessage = 'Truyện đã được cập nhật thành công';
        }

        // Handle cover image update
        if ($request->hasFile('cover_image')) {
            // Delete old image if exists
            if ($story->cover_image) {
                Storage::disk('public')->delete($story->cover_image);
            }
            $validatedData['cover_image'] = $request->file('cover_image')
                ->store('covers', 'public');
        }

        // Update story details
        $story->update([
            'name' => $validatedData['name'],
            'summary' => $validatedData['summary'],
            'content' => $validatedData['content'],
            'cover_image' => $validatedData['cover_image'] ?? $story->cover_image,
            'status' => $validatedData['status'],
            'age_rating' => $validatedData['age_rating'],
            'language' => $validatedData['language']
        ]);

        // Update categories
        $story->categories()->sync($validatedData['category_ids']);

        // Update hashtags
        $story->hashtags()->delete(); // Delete existing hashtags
        if (!empty($validatedData['hashtags'])) {
            $hashtags = explode(',', $validatedData['hashtags']);
            foreach ($hashtags as $tagName) {
                $story->hashtags()->create([
                    'name' => trim($tagName)
                ]);
            }
        }

        return redirect()->route('user.stories.dashboard')
            ->with('success', $successMessage);
    }

    /**
     * Remove the specified story from storage.
     */
    public function destroy($id)
    {
        $story = Story::where('author_id', Auth::id())->findOrFail($id);

        // Delete cover image if exists
        if ($story->cover_image) {
            Storage::disk('public')->delete($story->cover_image);
        }

        // Delete relationships first
        $story->categories()->detach(); // This is a belongsToMany relationship, so detach() works
        $story->hashtags()->delete();   // For hasMany relationship, use delete()
        $story->readingHistories()->delete(); 

        // Delete the story
        $story->delete();

        return redirect()->route('user.stories.dashboard')
            ->with('success', 'Truyện đã được xóa thành công');
    }

    public function dashboard(Request $request)
    {
        $query = Story::where('author_id', Auth::id());
        
        // Handle search parameter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%");
        }
        
        // Handle status filter
        if ($request->filled('status')) {
            $status = $request->input('status');
            $query->where('status', $status);
        }
        
        $stories = $query->with('categories')->paginate(10)->withQueryString();
        
        return view('user.stories.dashboard', compact('stories'));
    }

    // Add a method to increment views
    public function incrementViews($id)
    {
        $story = Story::findOrFail($id);
        $story->increment('views');
        return $story->views;
    }
}