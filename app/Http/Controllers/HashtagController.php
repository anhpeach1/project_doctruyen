<?php

namespace App\Http\Controllers;

use App\Models\Hashtag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HashtagController extends Controller
{
    /**
     * Hiển thị danh sách hashtag.
     */
    public function index()
    {
        $hashtags = Hashtag::all();
        return view('hashtags.index', compact('hashtags'));
    }

    /**
     * Hiển thị form tạo hashtag mới.
     */
    public function create()
    {
        return view('hashtags.create');
    }

    /**
     * Lưu trữ hashtag mới.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255|unique:hashtags,name',
        ]);

        Hashtag::create($validatedData);

        return redirect()->route('hashtags.index')->with('success', 'Hashtag đã được tạo thành công.');
    }

    /**
     * Hiển thị chi tiết hashtag.
     */
    public function show(string $id)
    {
        $hashtag = Hashtag::findOrFail($id);
        return view('hashtags.show', compact('hashtag'));
    }

    /**
     * Hiển thị form chỉnh sửa hashtag.
     */
    public function edit(string $id)
    {
        $hashtag = Hashtag::findOrFail($id);
        return view('hashtags.edit', compact('hashtag'));
    }

    /**
     * Cập nhật hashtag.
     */
    public function update(Request $request, string $id)
    {
        $hashtag = Hashtag::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|max:255|unique:hashtags,name,' . $hashtag->id,
        ]);

        $hashtag->update($validatedData);

        return redirect()->route('hashtags.index')->with('success', 'Hashtag đã được cập nhật thành công.');
    }

    /**
     * Xóa hashtag.
     */
    public function destroy(string $id)
    {
        $hashtag = Hashtag::findOrFail($id);
        $hashtag->delete();
        return redirect()->route('hashtags.index')->with('success', 'Hashtag đã được xóa thành công.');
    }
}