<!-- filepath: /c:/Laravel/ts11/chocopie/resources/views/admin/stories/reading_histories.blade.php -->
@extends('layouts.admin')

@section('title', 'Quản Lý Lịch Sử Đọc')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Quản Lý Lịch Sử Đọc</h1>
</div>

<div class="card mb-4">
    <div class="card-header">
        <h5>Bộ Lọc</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.reading-histories') }}" method="GET" class="form-inline">
            <div class="form-group mr-3 mb-2">
                <label for="user" class="mr-2">Người dùng:</label>
                <select name="user" id="user" class="form-control form-control-sm">
                    <option value="">Tất cả</option>
                    @foreach(App\Models\User::all() as $user)
                        <option value="{{ $user->id }}" {{ request('user') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group mr-3 mb-2">
                <label for="story" class="mr-2">Truyện:</label>
                <select name="story" id="story" class="form-control form-control-sm">
                    <option value="">Tất cả</option>
                    @foreach(App\Models\Story::all() as $story)
                        <option value="{{ $story->id }}" {{ request('story') == $story->id ? 'selected' : '' }}>
                            {{ $story->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group mr-3 mb-2">
                <label for="date_from" class="mr-2">Từ ngày:</label>
                <input type="date" name="date_from" id="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
            </div>
            
            <div class="form-group mr-3 mb-2">
                <label for="date_to" class="mr-2">Đến ngày:</label>
                <input type="date" name="date_to" id="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
            </div>
            
            <button type="submit" class="btn btn-sm btn-primary mb-2">
                <i class="fas fa-filter"></i> Lọc
            </button>
            <a href="{{ route('admin.reading-histories') }}" class="btn btn-sm btn-secondary mb-2 ml-2">
                <i class="fas fa-sync"></i> Đặt lại
            </a>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5>Danh Sách Lịch Sử Đọc</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Người dùng</th>
                        <th>Truyện</th>
                        <th>Thời gian đọc</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($readingHistories as $history)
                    <tr>
                        <td>{{ $history->id }}</td>
                        <td>
                            <a href="{{ route('admin.users.show', $history->user_id) }}">
                                {{ $history->user->name }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('admin.stories.show', $history->story_id) }}">
                                {{ $history->story->name }}
                            </a>
                        </td>
                        <td>{{ $history->read_at->format('d/m/Y H:i:s') }}</td>
                        <td>
                            <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $history->id }}">
                                <i class="fas fa-trash-alt"></i> Xóa
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Không có lịch sử đọc nào</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $readingHistories->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Handle reading history deletion
        $('.delete-btn').click(function() {
            if (confirm('Bạn có chắc chắn muốn xóa lịch sử đọc này?')) {
                const historyId = $(this).data('id');
                
                $.ajax({
                    url: `/admin/reading-histories/${historyId}`,
                    type: 'DELETE',
                    data: {
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(result) {
                        window.location.reload();
                    },
                    error: function(xhr) {
                        alert('Đã xảy ra lỗi khi xóa lịch sử đọc.');
                    }
                });
            }
        });
    });
</script>
@endsection