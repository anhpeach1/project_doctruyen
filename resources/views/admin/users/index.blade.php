@extends('layouts.admin')

@section('title', 'Quản Lý Người Dùng')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Quản Lý Người Dùng</h1>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('admin.users') }}" method="GET" class="form-inline">
            <div class="form-group mr-3 mb-2">
                <label for="user_type" class="mr-2">Loại tài khoản:</label>
                <select name="user_type" id="user_type" class="form-control form-control-sm">
                    <option value="">Tất cả</option>
                    <option value="user" {{ request('user_type') == 'user' ? 'selected' : '' }}>Người dùng</option>
                    <option value="admin" {{ request('user_type') == 'admin' ? 'selected' : '' }}>Quản trị viên</option>
                </select>
            </div>
            
            <div class="form-group mr-3 mb-2">
                <label for="sort" class="mr-2">Sắp xếp:</label>
                <select name="sort" id="sort" class="form-control form-control-sm">
                    <option value="newest" {{ request('sort') == 'newest' || !request('sort') ? 'selected' : '' }}>Mới nhất</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Tên A-Z</option>
                    <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Tên Z-A</option>
                </select>
            </div>

            <div class="form-group mr-3 mb-2">
                <label for="search" class="mr-2">Tìm kiếm:</label>
                <input type="text" name="search" id="search" class="form-control form-control-sm" value="{{ request('search') }}" placeholder="Tên hoặc email...">
            </div>
            
            <button type="submit" class="btn btn-sm btn-primary mb-2">
                <i class="fas fa-filter"></i> Lọc
            </button>
            <a href="{{ route('admin.users') }}" class="btn btn-sm btn-secondary mb-2 ml-2">
                <i class="fas fa-sync"></i> Đặt lại
            </a>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5>Danh Sách Người Dùng</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên</th>
                        <th>Email</th>
                        <th>Loại tài khoản</th>
                        <th>Ngày đăng ký</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->userType == 'admin')
                                <span class="badge bg-danger">Quản trị viên</span>
                            @else
                                <span class="badge bg-primary">Người dùng</span>
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form class="delete-form d-inline" action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Không có người dùng nào</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $users->appends(request()->query())->links() }}
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Handle user deletion
        $('.delete-form').on('submit', function(e) {
            e.preventDefault();
            
            if (confirm('Bạn có chắc chắn muốn xóa người dùng này? Tất cả dữ liệu liên quan sẽ bị xóa và không thể khôi phục.')) {
                const form = $(this);
                const row = form.closest('tr');
                
                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: form.serialize(),
                    dataType: 'json',
                    success: function(result) {
                        if (result.success) {
                            row.fadeOut(function() {
                                $(this).remove();
                            });
                        } else {
                            alert('Lỗi: ' + result.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error details:', xhr.responseText);
                        alert('Đã xảy ra lỗi khi xóa người dùng: ' + (xhr.responseJSON?.message || 'Lỗi không xác định'));
                    }
                });
            }
        });
    });
</script>
@endsection