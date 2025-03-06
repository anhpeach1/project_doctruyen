<?php

namespace App\Http\Controllers;

use App\Models\Story;
use App\Models\User;
use App\Models\Category;
use App\Models\Hashtag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ReadingHistory; 
class StoryController extends Controller
{
    /**
     * Hiển thị danh sách truyện.
     */
    public function index()
    {
        $stories = Story::with(['author', 'categories', 'hashtags'])->get();
        return view('stories.index', compact('stories'));
    }

    /**
     * Hiển thị form tạo truyện mới.
     */
    public function create()
    {
        $authors = User::all();
        $categories = Category::all();
        $hashtags = Hashtag::all();
        return view('stories.create', compact('authors', 'categories', 'hashtags'));
    }

    /**
     * Lưu trữ truyện mới được tạo.
     */
    public function store(Request $request)
    {
        // Validate dữ liệu
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'content' => 'required',
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:categories,id',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'nullable|string',
            'age_rating' => 'nullable|string',
            'language' => 'nullable|string'
        ]);

        // Thêm author_id là user hiện tại
        $validatedData['author_id'] = Auth::id();

        // Xử lý upload ảnh bìa nếu có
        if ($request->hasFile('cover_image')) {
            $imagePath = $request->file('cover_image')->store('covers', 'public');
            $validatedData['cover_image'] = $imagePath;
        }

        // Tạo story mới
        $story = Story::create($validatedData);

        // Thêm categories
        if ($request->has('category_ids')) {
            $story->categories()->attach($request->category_ids);
        }

        // Xử lý hashtags nếu có
        if ($request->has('hashtag_ids')) {
            $hashtags = json_decode($request->hashtag_ids);
            foreach ($hashtags as $tagName) {
                $hashtag = Hashtag::firstOrCreate(['name' => $tagName]);
                $story->hashtags()->attach($hashtag->id);
            }
        }

        return redirect()->route('user.stories.dashboard')->with('success', 'Truyện đã được tạo thành công.');
    }

    /**
     * Hiển thị chi tiết truyện.
     */
    public function show($id)
{
    $story = Story::with(['author', 'categories', 'hashtags'])
        ->findOrFail($id);
    
    return view('user.stories.show', compact('story'));
}

/**
 * Display the story for reading.
 */
public function read($id)
{
    $story = Story::with(['author', 'categories'])
        ->findOrFail($id);
    
    // Record reading history if user is authenticated
    if (Auth::check()) {
        ReadingHistory::updateOrCreate(
            ['user_id' => Auth::id(), 'story_id' => $story->id],
            ['read_at' => now()]
        );
    }
    
    return view('user.stories.read', compact('story'));
}

/**
 * Track story views
 */
public function trackView($id)
{
    $story = Story::findOrFail($id);
    $story->increment('views');
    return response()->json(['success' => true]);
}
    /**
     * Hiển thị form chỉnh sửa truyện.
     */
    public function edit(string $id)
    {
        $story = Story::findOrFail($id);
        $authors = User::all();
        $categories = Category::all();
        $hashtags = Hashtag::all();
        $story->load(['categories', 'hashtags']);
        return view('stories.edit', compact('story', 'authors', 'categories', 'hashtags'));
    }

    /**
     * Cập nhật truyện.
     */
    public function update(Request $request, string $id)
    {
        $story = Story::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'summary' => 'nullable|string',
            'content' => 'required|string',
            'author_id' => 'required|exists:users,id',
            'age_rating' => 'nullable|string|in:Tất cả,13+,16+,18+',
            'language' => 'nullable|string',
            'cover_image' => 'nullable|url',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',
            'hashtag_ids' => 'nullable|array',
            'hashtag_ids.*' => 'exists:hashtags,id',
            'status' => 'nullable|string',
        ]);

        $story->update($validatedData);

        if (isset($validatedData['category_ids'])) {
            $story->categories()->sync($validatedData['category_ids']);
        } else {
            $story->categories()->detach();
        }

        if (isset($validatedData['hashtag_ids'])) {
            $story->hashtags()->sync($validatedData['hashtag_ids']);
        } else {
            $story->hashtags()->detach();
        }

        return redirect()->route('stories.index')->with('success', 'Truyện đã được cập nhật thành công.');
    }

    /**
     * Xóa truyện.
     */
    public function destroy(string $id)
    {
        $story = Story::findOrFail($id);
        $story->delete();
        return redirect()->route('stories.index')->with('success', 'Truyện đã được xóa thành công.');
    }
}