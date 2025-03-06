@extends('layouts.admin')

@section('title', 'Xem xét truyện')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Xem xét truyện: {{ $story->name }}</h1>
        <div>
            <a href="{{ route('admin.stories.pending') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Nội dung truyện</h5>
                </div>
                <div class="card-body">
                    <h3>{{ $story->name }}</h3>
                    <p class="text-muted">Tác giả: {{ $story->author->name }}</p>
                    
                    <h5>Mô tả:</h5>
                    <div class="mb-4">{{ $story->description }}</div>
                    
                    <h5>Nội dung:</h5>
                    <div class="story-content">
                        {{ $story->content }}
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Thông tin</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Ngày tạo:</span>
                            <span>{{ $story->created_at->format('d/m/Y H:i') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Lần cập nhật cuối:</span>
                            <span>{{ $story->updated_at->format('d/m/Y H:i') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Thể loại:</span>
                            <span>
                                @foreach($story->categories as $category)
                                    <span class="badge bg-info">{{ $category->name }}</span>
                                @endforeach
                            </span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5>Quyết định</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.stories.approve', $story->id) }}" method="POST" class="mb-3">
                        @csrf
                        <button type="submit" class="btn btn-success btn-lg w-100">
                            <i class="fas fa-check-circle"></i> Phê duyệt và xuất bản
                        </button>
                    </form>
                    
                    <button type="button" class="btn btn-danger btn-lg w-100" data-toggle="modal" data-target="#rejectModal">
                        <i class="fas fa-ban"></i> Từ chối
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal từ chối -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.stories.reject', $story->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">Từ chối truyện</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="rejection_reason">Lý do từ chối:</label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-danger">Xác nhận từ chối</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    $(document).ready(function() {
        // Bootstrap 5 syntax
        var myModal = new bootstrap.Modal(document.getElementById('rejectModal'));
        $('.btn-danger[data-bs-toggle="modal"]').click(function() {
            myModal.show();
        });
    });
</script>
@endsection