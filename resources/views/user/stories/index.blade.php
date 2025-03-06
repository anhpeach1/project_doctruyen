<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
        .story-card {
            transition: transform 0.2s;
            border: none;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .story-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 2px 5px rgba(0,0,0,0.15);
        }
        .story-cover-container {
            position: relative;
            width: 100%;
            padding-top: 130%; /* Reduced height ratio */
            overflow: hidden;
            border-radius: 4px 4px 0 0;
        }
        .story-cover {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .category-badge {
            background: linear-gradient(45deg, #0d6efd, #0dcaf0);
            font-size: 0.7rem;
            padding: 0.2rem 0.4rem;
            margin: 0.1rem;
            border-radius: 10px;
        }
        .carousel-item img {
            height: 400px;
            object-fit: cover;
        }
        .section-title {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e9ecef;
        }
        .card-body {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .story-categories {
            margin-bottom: 10px;
        }
        .story-stats {
            margin-top: auto;
        }
        /* Mobile optimization */
        @media (max-width: 576px) {
            .story-stats {
                font-size: 0.7rem;
            }
        }
    </style>
</head>
<body class="bg-light">
    <x-navbar-login />

    <!-- Main Content -->
    <div class="container py-4">
        @include('user.stories.partials.carousel')
        
        @include('user.stories.partials.filters')
        
        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 g-2">
            @forelse($stories as $story)
                <div class="col">
                    <div class="card story-card h-100">
                        <div class="story-cover-container">
                            <a href="{{ route('stories.show', $story->id) }}">
                                @if($story->cover_image)
                                    <img src="{{ Storage::url($story->cover_image) }}" class="story-cover" alt="{{ $story->name }}">
                                @else
                                    <div class="story-cover bg-secondary d-flex align-items-center justify-content-center">
                                        <i class="fas fa-book text-white"></i>
                                    </div>
                                @endif
                            </a>
                        </div>
                        <div class="card-body p-2">
                            <h6 class="card-title mb-1 text-truncate">
                                <a href="{{ route('stories.show', $story->id) }}" class="text-decoration-none text-dark">
                                    {{ $story->name }}
                                </a>
                            </h6>
                            <div class="author-info mb-1 small text-truncate">
                                <i class="fas fa-user-edit me-1"></i>{{ $story->author->name }}
                            </div>
                            <div class="story-stats d-flex justify-content-between text-muted small">
                                <span><i class="fas fa-eye me-1"></i>{{ $story->views ?? 0 }}</span>
                                <span><i class="fas fa-clock me-1"></i>{{ $story->created_at->diffForHumans(null, true) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-book-open fa-3x mb-3 text-muted"></i>
                        <p class="text-muted">Chưa có truyện nào được đăng tải.</p>
                        <a href="{{ route('user.stories.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Tạo Truyện Mới
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $stories->links() }}
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>