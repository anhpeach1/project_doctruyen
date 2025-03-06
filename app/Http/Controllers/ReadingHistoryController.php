<?php

namespace App\Http\Controllers;

use App\Models\ReadingHistory;
use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReadingHistoryController extends Controller
{
    /**
     * Hiển thị lịch sử đọc của người dùng.
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để xem lịch sử đọc.');
        }

        $readingHistories = ReadingHistory::where('user_id', Auth::id())
            ->with('story')
            ->orderByDesc('read_at')
            ->paginate(10);

        return view('reading_histories.index', compact('readingHistories'));
    }

    /**
     * Lưu lịch sử đọc khi người dùng bắt đầu đọc truyện.
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Bạn cần đăng nhập để lưu lịch sử đọc.'], 401);
        }

        $validatedData = $request->validate([
            'story_id' => 'required|exists:stories,id',
        ]);

        ReadingHistory::updateOrCreate(
            ['user_id' => Auth::id(), 'story_id' => $validatedData['story_id']],
            ['read_at' => now()]
        );

        return response()->json(['success' => 'Lịch sử đọc đã được lưu.']);
    }

    /**
     * Hiển thị chi tiết lịch sử đọc.
     */
    public function show(string $id)
    {
        $readingHistory = ReadingHistory::findOrFail($id);
        return view('reading_histories.show', compact('readingHistory'));
    }

    /**
     * Form chỉnh sửa lịch sử đọc.
     */
    public function edit(string $id)
    {
        $readingHistory = ReadingHistory::findOrFail($id);
        return view('reading_histories.edit', compact('readingHistory'));
    }

    /**
     * Cập nhật lịch sử đọc.
     */
    public function update(Request $request, string $id)
    {
        // Logic cập nhật lịch sử đọc nếu cần
    }

    /**
     * Xóa lịch sử đọc.
     */
    public function destroy(string $id)
    {
        $readingHistory = ReadingHistory::findOrFail($id);
        $readingHistory->delete();
        return redirect()->route('reading_histories.index')->with('success', 'Lịch sử đọc đã được xóa.');
    }
}