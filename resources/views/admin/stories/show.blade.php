@extends('layouts.admin')

@section('title', 'Chi Tiết Truyện')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Chi Tiết Truyện: {{ $story->name }}</h1>
        <div>
            <a href="{{ route('admin.stories') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
            <a href="{{ route('admin.stories.edit', $story->id) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Chỉnh sửa
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
                    @if($story->cover_image)
                    <div class="text-center mb-4">
                        <img src="{{ Storage::url($story->cover_image) }}" class="img-fluid rounded" style="max-height: 300px;" alt="{{ $story->name }}">
                    </div>
                    @endif

                    <h3>{{ $story->name }}</h3>
                    <p class="text-muted">Tác giả: {{ $story->author->name }}</p>
                    
                    <h5>Mô tả:</h5>
                    <div class="mb-4">{{ $story->summary }}</div>
                    
                    <h5>Nội dung:</h5>
                    <div class="story-content">
                        {{ $story->content }}
                    </div>
                </div>
            </div>
            
            @if($story->comments && $story->comments->count() > 0)
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Bình luận ({{ $story->comments->count() }})</h5>
                </div>
                <div class="card-body">
                    @foreach($story->comments as $comment)
                    <div class="comment mb-3 pb-3 border-bottom">
                        <div class="d-flex justify-content-between">
                            <h6>{{ $comment->user->name }}</h6>
                            <small class="text-muted">{{ $comment->created_at->format('d/m/Y H:i') }}</small>
                        </div>
                        <p class="mb-0">{{ $comment->content }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Thông tin chi tiết</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>ID:</span>
                            <span>{{ $story->id }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Trạng thái:</span>
                            <span>
                                @if($story->status == 'draft')
                                    <span class="badge bg-secondary">Bản nháp</span>
                                @elseif($story->status == 'pending')
                                    <span class="badge bg-warning text-dark">Đang chờ duyệt</span>
                                @elseif($story->status == 'published')
                                    <span class="badge bg-success">Đã xuất bản</span>
                                @elseif($story->status == 'rejected')
                                    <span class="badge bg-danger">Đã từ chối</span>
                                @endif
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Độ tuổi:</span>
                            <span>{{ $story->age_rating }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Ngôn ngữ:</span>
                            <span>{{ $story->language == 'vi' ? 'Tiếng Việt' : 'Tiếng Anh' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Lượt xem:</span>
                            <span>{{ $story->views ?? 0 }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Ngày tạo:</span>
                            <span>{{ $story->created_at->format('d/m/Y H:i') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Cập nhật cuối:</span>
                            <span>{{ $story->updated_at->format('d/m/Y H:i') }}</span>
                        </li>
                        <li class="list-group-item">
                            <span>Thể loại:</span>
                            <div class="mt-2">
                                @foreach($story->categories as $category)
                                    <span class="badge bg-info me-1">{{ $category->name }}</span>
                                @endforeach
                            </div>
                        </li>
                        @if($story->hashtags && $story->hashtags->count() > 0)
                        <li class="list-group-item">
                            <span>Từ khóa:</span>
                            <div class="mt-2">
                                @foreach($story->hashtags as $tag)
                                    <span class="badge bg-secondary me-1">{{ $tag->name }}</span>
                                @endforeach
                            </div>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">Quản lý</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.stories.destroy', $story->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa truyện này? Hành động này không thể hoàn tác.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-trash"></i> Xóa truyện này
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection