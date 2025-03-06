@extends('layouts.admin')

@section('title', 'Quản Lý Truyện')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Quản Lý Truyện Ngắn</h1>
</div>

<div class="card mb-4">
    <div class="card-header">
        <h5>Bộ Lọc</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.stories') }}" method="GET" class="form-inline">
            <div class="form-group mr-3 mb-2">
                <label for="category" class="mr-2">Thể loại:</label>
                <select name="category" id="category" class="form-control form-control-sm">
                    <option value="">Tất cả</option>
                    
                    @foreach(App\Models\Category::all() as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                    
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
                <input type="text" name="search" id="search" class="form-control form-control-sm" value="{{ request('search') }}" placeholder="Tên truyện...">
            </div>
            
            <button type="submit" class="btn btn-sm btn-primary mb-2">
                <i class="fas fa-filter"></i> Lọc
            </button>
            <a href="{{ route('admin.stories') }}" class="btn btn-sm btn-secondary mb-2 ml-2">
                <i class="fas fa-sync"></i> Đặt lại
            </a>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5>Danh Sách Truyện</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên Truyện</th>
                    <th>Tác Giả</th>
                    <th>Thể loại</th>
                    <th>Ngày Tạo</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
                <tbody>
                    @forelse($stories as $story)
                    <tr>
                        <td>{{ $story->id }}</td>
                        <td>{{ $story->name }}</td>
                        <td>{{ $story->author->name }}</td>
                        <td>
                            @foreach($story->categories as $category)
                                <span class="badge badge-info">{{ $category->name }}</span>
                            @endforeach
                        </td>
                        <td>{{ $story->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.stories.show', $story->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form class="delete-form d-inline" action="{{ route('admin.stories.destroy', $story->id) }}" method="POST">
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
                        <td colspan="6" class="text-center">Không có truyện nào</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $stories->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Handle story deletion
        $('.delete-form').on('submit', function(e) {
            e.preventDefault();
            
            if (confirm('Bạn có chắc chắn muốn xóa truyện này?')) {
                const form = $(this);
                const row = form.closest('tr');
                
                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: form.serialize(),
                    dataType: 'json',
                    success: function(result) {
                        if (result.success) {
                            // Xóa dòng khỏi bảng hoặc làm mới trang
                            row.fadeOut(function() {
                                $(this).remove();
                            });
                        } else {
                            alert('Lỗi: ' + result.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error details:', xhr.responseText);
                        alert('Đã xảy ra lỗi khi xóa truyện: ' + (xhr.responseJSON?.message || 'Lỗi không xác định'));
                    }
                });
            }
        });
    });
</script>
@endsection