<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $query = User::query();
        
        // Search filter
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }
        
        // User type filter
        if ($request->has('user_type') && !empty($request->user_type)) {
            $query->where('userType', $request->user_type);
        }
        
        // Sort
        $sort = $request->sort ?? 'newest';
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            default: // newest
                $query->orderBy('created_at', 'desc');
                break;
        }
        
        $users = $query->paginate(10);
        
        return view('admin.users.index', compact('users'));
    }

    /**
     * Display the specified user.
     */
    public function show($id)
    {
        $user = User::with(['stories', 'readingHistories'])->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$id,
            'userType' => 'required|string|in:user,admin',
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->userType = $validatedData['userType'];
        
        if (!empty($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }
        
        $user->save();
        
        return redirect()->route('admin.users.show', $user->id)
            ->with('success', 'Thông tin người dùng đã được cập nhật thành công');
    }

    //

/**
 * Remove the specified user from storage.
 */
public function destroy($id)
{
    try {
        $user = User::findOrFail($id);
        
        // Delete related records first
        // Reading histories
        $user->readingHistories()->delete();
        
        // If user has stories, handle them
        // Either reassign or delete depending on your business logic
        foreach ($user->stories as $story) {
            // Delete story relations
            $story->categories()->detach();
            $story->hashtags()->delete();
            $story->readingHistories()->delete();
            
            // Delete the story itself
            $story->delete();
        }
        
        // Delete the user
        $user->delete();
        
        // Check if request is AJAX
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true, 
                'message' => 'Người dùng đã được xóa thành công'
            ]);
        }
        
        return redirect()->route('admin.users')
            ->with('success', 'Người dùng đã được xóa thành công');
    } catch (\Exception $e) {
        if (request()->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 500);
        }
        
        return redirect()->route('admin.users')
            ->with('error', 'Lỗi khi xóa người dùng: ' . $e->getMessage());
    }
}
}

