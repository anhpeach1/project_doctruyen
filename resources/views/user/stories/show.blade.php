<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $story->name }}</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- App Styles -->
    <!-- <link rel="stylesheet" href="{{ asset('css/app.css') }}"> -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        .story-cover {
            width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            max-height: 400px;
            object-fit: cover;
        }
        .story-info-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1);
        }
        .category-badge {
            background: linear-gradient(45deg, #0d6efd, #0dcaf0);
            font-size: 0.8rem;
            padding: 0.3rem 0.6rem;
            margin: 0.2rem;
            border-radius: 15px;
        }
        .hashtag {
            background-color: #f8f9fa;
            color: #6c757d;
            padding: 0.2rem 0.5rem;
            border-radius: 0.25rem;
            margin-right: 0.3rem;
            font-size: 0.85rem;
        }
        .description-text {
            white-space: pre-line;
            line-height: 1.6;
        }
        .stat-card {
            text-align: center;
            padding: 0.8rem;
            border-radius: 0.5rem;
            transition: all 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .stat-icon {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }
        .read-btn {
            border-radius: 50px;
            padding: 0.6rem 2rem;
            font-weight: 500;
        }
    </style>
</head>
<body class="bg-light">
    <x-navbar-login />

    <div class="container py-4">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('user.index') }}">Trang chủ</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $story->name }}</li>
            </ol>
        </nav>

        <div class="card story-info-card mb-4">
            <div class="card-body p-0">
                <div class="row g-0">
                    <div class="col-md-4 p-4">
                        @if($story->cover_image)
                            <img src="{{ Storage::url($story->cover_image) }}" class="story-cover" alt="{{ $story->name }}">
                        @else
                            <div class="story-cover d-flex align-items-center justify-content-center bg-secondary">
                                <i class="fas fa-book fa-3x text-white"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-8 p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <h1 class="card-title mb-3">{{ $story->name }}</h1>
                            <div>
                                @if($story->status == 'published')
                                    <span class="badge bg-success">Đã xuất bản</span>
                                @else
                                    <span class="badge bg-secondary">Bản nháp</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <small class="text-muted">Tác giả:</small>
                            <span class="ms-2 fw-medium">{{ $story->author->name }}</span>
                        </div>
                        
                        <div class="mb-3">
                            <small class="text-muted">Thể loại:</small>
                            <div class="mt-1">
                                @foreach($story->categories as $category)
                                    <span class="badge category-badge">{{ $category->name }}</span>
                                @endforeach
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="row row-cols-3 g-2">
                                <div class="col">
                                    <div class="stat-card bg-white">
                                        <div class="stat-icon text-primary">
                                            <i class="fas fa-eye"></i>
                                        </div>
                                        <div class="text-muted small">Lượt xem</div>
                                        <div class="fw-bold">{{ $story->views ?? 0 }}</div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="stat-card bg-white">
                                        <div class="stat-icon text-info">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                        <div class="text-muted small">Ngày tạo</div>
                                        <div class="fw-bold">{{ $story->created_at->format('d/m/Y') }}</div>
                                    </div>
                                </div>
                                <div class="col">
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <a href="{{ route('stories.read', $story->id) }}" class="btn btn-primary read-btn">
                                <i class="fas fa-book-reader me-2"></i>Đọc truyện
                            </a>
                            
                            @if(Auth::id() == $story->author_id)
                                <a href="{{ route('user.stories.edit', $story->id) }}" class="btn btn-outline-secondary ms-2">
                                    <i class="fas fa-edit me-1"></i>Chỉnh sửa
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Mô tả truyện</h5>  <!-- Added title for description section -->
                    </div>
                    <div class="card-body">
                        <p class="description-text">{{ $story->summary }}</p>
                    </div>
                </div>
                
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Thông tin tác phẩm</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <strong class="d-block text-muted">Ngày xuất bản:</strong>
                                <span>{{ $story->created_at->format('d/m/Y') }}</span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong class="d-block text-muted">Cập nhật lần cuối:</strong>
                                <span>{{ $story->updated_at->format('d/m/Y') }}</span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong class="d-block text-muted">Số trang:</strong>
                                <span>{{ $story->page_count ?? 'Chưa cập nhật' }}</span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong class="d-block text-muted">Trạng thái:</strong>
                                <span>{{ $story->status == 'published' ? 'Đã xuất bản' : 'Đang viết' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Thông tin chi tiết</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Độ tuổi</span>
                                <span class="badge bg-dark rounded-pill">{{ $story->age_rating }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Ngôn ngữ</span>
                                <span>{{ $story->language == 'vi' ? 'Tiếng Việt' : 'Tiếng Anh' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Cập nhật</span>
                                <span>{{ $story->updated_at->diffForHumans() }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
                
                @if($story->hashtags->count() > 0)
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Từ khóa</h5>
                    </div>
                    <div class="card-body">
                        @foreach($story->hashtags as $hashtag)
                            <span class="hashtag">#{{ $hashtag->name }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Track view count
        $(document).ready(function() {
            $.ajax({
                url: "{{ route('stories.track-view', $story->id) }}",
                type: 'POST',
                data: {
                    '_token': "{{ csrf_token() }}"
                }
            });

            // Copy link functionality
            $('#copy-link').click(function() {
                const url = $(this).data('url');
                navigator.clipboard.writeText(url).then(() => {
                    alert('Đã sao chép liên kết vào clipboard!');
                });
            });
            
            // Flash message handling
            const flashMessage = "{{ session('success') }}";
            if (flashMessage) {
                // Create and show toast notification
                const toast = document.createElement('div');
                toast.className = 'position-fixed bottom-0 end-0 p-3';
                toast.style.zIndex = '5';
                toast.innerHTML = `
                    <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="toast-header">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <strong class="me-auto">Thông báo</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                        <div class="toast-body">
                            ${flashMessage}
                        </div>
                    </div>
                `;
                document.body.appendChild(toast);
                
                // Auto-hide after 5 seconds
                setTimeout(() => {
                    const toastEl = document.querySelector('.toast');
                    const bsToast = bootstrap.Toast.getInstance(toastEl);
                    if (bsToast) bsToast.hide();
                }, 5000);
            }
        });
    </script>
</body>
</html>