@extends('layouts.admin')

@section('title', 'Chi Tiết Người Dùng')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Chi Tiết Người Dùng: {{ $user->name }}</h1>
        <div>
            <a href="{{ route('admin.users') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Chỉnh sửa
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Thông tin người dùng</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>ID:</span>
                            <span>{{ $user->id }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Tên:</span>
                            <span>{{ $user->name }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Email:</span>
                            <span>{{ $user->email }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Loại tài khoản:</span>
                            <span>
                                @if($user->userType == 'admin')
                                    <span class="badge bg-danger">Quản trị viên</span>
                                @else
                                    <span class="badge bg-primary">Người dùng</span>
                                @endif
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Ngày đăng ký:</span>
                            <span>{{ $user->created_at->format('d/m/Y H:i') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Lần cập nhật cuối:</span>
                            <span>{{ $user->updated_at->format('d/m/Y H:i') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">Quản lý</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa người dùng này? Tất cả dữ liệu liên quan sẽ bị xóa và không thể khôi phục.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-trash"></i> Xóa người dùng này
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Truyện đã đăng ({{ $user->stories->count() }})</h5>
                </div>
                <div class="card-body">
                    @if($user->stories->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tên truyện</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày tạo</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->stories as $story)
                                <tr>
                                    <td>{{ $story->id }}</td>
                                    <td>{{ $story->name }}</td>
                                    <td>
                                        @if($story->status == 'draft')
                                            <span class="badge bg-secondary">Bản nháp</span>
                                        @elseif($story->status == 'pending')
                                            <span class="badge bg-warning text-dark">Đang chờ duyệt</span>
                                        @elseif($story->status == 'published')
                                            <span class="badge bg-success">Đã xuất bản</span>
                                        @elseif($story->status == 'rejected')
                                            <span class="badge bg-danger">Đã từ chối</span>
                                        @endif
                                    </td>
                                    <td>{{ $story->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.stories.show', $story->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-center text-muted">Người dùng này chưa đăng truyện nào.</p>
                    @endif
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5>Lịch sử đọc ({{ $user->readingHistories->count() }})</h5>
                </div>
                <div class="card-body">
                    @if($user->readingHistories->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tên truyện</th>
                                    <th>Ngày đọc</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->readingHistories as $history)
                                <tr>
                                    <td>{{ $history->id }}</td>
                                    <td>{{ $history->story->name ?? 'Không còn tồn tại' }}</td>
                                    <td>{{ $history->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-center text-muted">Người dùng này chưa có lịch sử đọc truyện.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection