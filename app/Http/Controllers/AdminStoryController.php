<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Story;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\ReadingHistory;
class AdminStoryController extends Controller
{
    /**
     * Display a listing of stories with filters.
     */
    public function index()
    {
        // Base query
        $storiesQuery = Story::with(['author', 'categories']);
        
        // Search by name if provided
        if (request()->has('search') && request('search') != '') {
            $storiesQuery->where('name', 'like', '%' . request('search') . '%');
        }
        
        // Filter by category if selected
        if (request()->has('category') && request('category') != '') {
            $storiesQuery->whereHas('categories', function($query) {
                $query->where('categories.id', request('category'));
            });
        }
        
        // Apply sorting
        switch(request('sort')) {
            case 'oldest':
                $storiesQuery->oldest();
                break;
            case 'name_asc':
                $storiesQuery->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $storiesQuery->orderBy('name', 'desc');
                break;
            case 'newest':
            default:
                $storiesQuery->latest();
                break;
        }
        
        $stories = $storiesQuery->paginate(10);
        
        return view('admin.stories.index', compact('stories'));
    }

    /**
     * Show the form for creating a new story.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.stories.create', compact('categories'));
    }

    /**
     * Store a newly created story.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'content' => 'required',
            'description' => 'required',
            'category_ids' => 'required|array',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        $story = Story::create([
            'content' => $validated['content'],
            'description' => $validated['description'],
            'author_id' => Auth::user()->id, // Or how you determine the author
        ]);

        // Handle thumbnail upload if provided
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('thumbnails', 'public');
            $story->thumbnail = $path;
            $story->save();
        }

        // Attach categories
        $story->categories()->attach($validated['category_ids']);

        return redirect()->route('admin.stories')
            ->with('success', 'Truyện đã được tạo thành công!');
    }

    /**
     * Display the specified story.
     */
    public function show($id)
    {
        $story = Story::with(['author', 'categories'])->findOrFail($id);
        return view('admin.stories.show', compact('story'));
    }

    /**
     * Show the form for editing the specified story.
     */
    public function edit($id)
    {
        $story = Story::with('categories')->findOrFail($id);
        $categories = Category::all();
        return view('admin.stories.edit', compact('story', 'categories'));
    }

    /**
     * Update the specified story.
     */
    public function update(Request $request, $id)
    {
        $story = Story::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|max:255',
            'content' => 'required',
            'description' => 'required',
            'category_ids' => 'required|array',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        $story->update([
            'name' => $validated['name'],
            'content' => $validated['content'],
            'description' => $validated['description'],
        ]);

        // Handle thumbnail upload if provided
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('thumbnails', 'public');
            $story->thumbnail = $path;
            $story->save();
        }

        // Sync categories
        $story->categories()->sync($validated['category_ids']);

        return redirect()->route('admin.stories')
            ->with('success', 'Truyện đã được cập nhật thành công!');
    }

    /**
     * Remove the specified story from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $story = Story::findOrFail($id);
            
            // Delete cover image if exists
            if ($story->cover_image && Storage::disk('public')->exists($story->cover_image)) {
                Storage::disk('public')->delete($story->cover_image);
            }
            
            // Delete related records
            $story->categories()->detach(); // Giữ nguyên - đây là belongsToMany
            $story->hashtags()->delete();   // Sửa từ detach() thành delete() vì đây là hasMany   // Giữ nguyên
            
            // Delete reading histories
            ReadingHistory::where('story_id', $story->id)->delete();
            
            // Delete the story
            $story->delete();
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Truyện đã được xóa thành công.'
                ]);
            }
            
            return redirect()->route('admin.stories')
                ->with('success', 'Truyện đã được xóa thành công.');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lỗi: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('admin.stories')
                ->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Display the reading history management page
     */
    public function readingHistories()
    {
        // Base query
        $query = \App\Models\ReadingHistory::with(['user', 'story'])
            ->orderBy('read_at', 'desc');
        
        // Filter by user
        if (request()->has('user') && request('user') != '') {
            $query->where('user_id', request('user'));
        }
        
        // Filter by story
        if (request()->has('story') && request('story') != '') {
            $query->where('story_id', request('story'));
        }
        
        // Filter by date range
        if (request()->has('date_from') && request('date_from') != '') {
            $query->whereDate('read_at', '>=', request('date_from'));
        }
        
        if (request()->has('date_to') && request('date_to') != '') {
            $query->whereDate('read_at', '<=', request('date_to'));
        }
        
        $readingHistories = $query->paginate(15);
            
        return view('admin.stories.reading_histories', compact('readingHistories'));
    }

    /**
     * Delete a reading history record
     */
    public function destroyReadingHistory($id)
    {
        $history = \App\Models\ReadingHistory::findOrFail($id);
        $history->delete();
        
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }
        
        return redirect()->route('admin.reading-histories')
            ->with('success', 'Lịch sử đọc đã được xóa thành công.');
    }

    /**
     * Hiển thị danh sách truyện đang chờ phê duyệt
     */
    public function pendingStories()
    {
        $pendingStories = Story::where('status', 'pending')
            ->with('author')
            ->orderBy('updated_at', 'desc')
            ->paginate(10);
            
        return view('admin.stories.pending', compact('pendingStories'));
    }

    /**
     * Hiển thị trang xem xét phê duyệt truyện
     */
    public function reviewStory($id)
    {
        $story = Story::with(['author', 'categories'])->findOrFail($id);
        return view('admin.stories.review', compact('story'));
    }

    /**
     * Phê duyệt truyện
     */
    public function approveStory(Request $request, $id)
    {
        $story = Story::findOrFail($id);
        $story->status = 'published';
        $story->save();
        
        // Tùy chọn: Gửi thông báo cho tác giả
        // $story->author->notify(new StoryApproved($story));
        
        return redirect()->route('admin.stories.pending')
            ->with('success', 'Truyện đã được phê duyệt và xuất bản thành công');
    }

    /**
     * Từ chối truyện
     */
    public function rejectStory(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:255'
        ]);
        
        $story = Story::findOrFail($id);
        $story->status = 'rejected';
        $story->rejection_reason = $request->rejection_reason;
        $story->save();
        
        // Tùy chọn: Gửi thông báo cho tác giả
        // $story->author->notify(new StoryRejected($story));
        
        return redirect()->route('admin.stories.pending')
            ->with('success', 'Truyện đã bị từ chối và tác giả sẽ được thông báo');
    }
}