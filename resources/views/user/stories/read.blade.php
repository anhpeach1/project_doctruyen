<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $story->name }} - Đọc Truyện</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            color: #343a40;
            line-height: 1.8;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .reading-container {
            width: 100%;
            max-width: 100%;
            margin: 0 auto;
            padding: 20px 5%;
            background-color: #fff;
            border-radius: 0;
            box-shadow: none;
        }
        @media (min-width: 1200px) {
            .reading-container {
                padding: 20px 15%;
            }
        }
        .story-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #212529;
        }
        .story-content {
            font-size: 1.1rem;
            white-space: pre-line;
            text-align: justify;
            margin-top: 2rem;
        }
        .story-meta {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }
        .reading-progress {
            height: 6px;
            position: fixed;
            top: 0;
            left: 0;
            width: 0%;
            background: linear-gradient(90deg, #0d6efd, #0dcaf0);
            z-index: 1000;
            transition: width 0.2s ease;
        }
        .reading-options {
            position: sticky;
            top: 0;
            background: white;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
            margin-bottom: 30px;
            z-index: 100;
        }
        .font-control button {
            padding: 5px 10px;
            background: transparent;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            font-weight: bold;
        }
        @media (prefers-color-scheme: dark) {
            .dark-mode-toggle {
                color: #fff;
                background-color: #343a40;
            }
        }
        .dark-mode {
            background-color: #212529;
            color: #f8f9fa;
        }
        .dark-mode .reading-container {
            background-color: #343a40;
            color: #f8f9fa;
        }
        .dark-mode .reading-options {
            background-color: #343a40;
            border-color: #495057;
        }
        .dark-mode .story-title {
            color: #f8f9fa;
        }
        .dark-mode-toggle {
            padding: 5px 10px;
            border-radius: 4px;
            background-color: #f0f0f0;
            border: 1px solid #dee2e6;
            color: #212529;
            transition: all 0.3s ease;
        }
        .dark-mode .dark-mode-toggle {
            background-color: #555;
            border-color: #666;
            color: #fff;
        }
        .dark-mode-toggle:hover {
            background-color: #e2e6ea;
        }
        .dark-mode .dark-mode-toggle:hover {
            background-color: #444;
        }
    </style>
</head>
<body>
    <div class="reading-progress"></div>
    
    <div class="reading-options shadow-sm">
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <a href="{{ route('stories.show', $story->id) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Quay lại
                    </a>
                </div>
                <h5 class="mb-0 d-none d-md-block">{{ $story->name }}</h5>
                <div class="font-control">
                    <button id="font-decrease" class="me-1" title="Giảm cỡ chữ">A-</button>
                    <button id="font-increase" class="me-1" title="Tăng cỡ chữ">A+</button>
                    <button id="dark-mode-toggle" class="dark-mode-toggle" title="Chế độ đọc ban đêm">
                        <i class="fas fa-moon"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid py-3">
        <div class="reading-container">
            <h1 class="story-title">{{ $story->name }}</h1>
            
            <div class="story-meta">
                <div class="d-flex align-items-center mb-2">
                    <i class="fas fa-user-edit me-2"></i>
                    <span>Tác giả: <strong>{{ $story->author->name }}</strong></span>
                </div>
                <div>
                    @foreach($story->categories as $category)
                        <span class="badge bg-secondary me-1">{{ $category->name }}</span>
                    @endforeach
                </div>
            </div>
            
            <hr>
            
            <div class="story-content" id="story-content">
                {{ $story->content }}
            </div>
            
            <hr class="my-4">
            
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div>
                    <a href="{{ route('stories.show', $story->id) }}" class="btn btn-outline-primary">
                        <i class="fas fa-info-circle me-2"></i>Thông tin truyện
                    </a>
                </div>
                <div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Save reading history
            @auth
                $.ajax({
                    url: "{{ route('user.reading-histories.store') }}",
                    type: 'POST',
                    data: {
                        '_token': "{{ csrf_token() }}",
                        'story_id': "{{ $story->id }}"
                    }
                });
            @endauth

            // Font size controls
            let fontSize = parseFloat($('#story-content').css('font-size'));
            $('#font-increase').click(function() {
                if (fontSize < 24) {
                    fontSize += 1;
                    $('#story-content').css('font-size', fontSize + 'px');
                    localStorage.setItem('story-font-size', fontSize);
                }
            });
            
            $('#font-decrease').click(function() {
                if (fontSize > 14) {
                    fontSize -= 1;
                    $('#story-content').css('font-size', fontSize + 'px');
                    localStorage.setItem('story-font-size', fontSize);
                }
            });
            
            // Remember font size preference
            const savedFontSize = localStorage.getItem('story-font-size');
            if (savedFontSize) {
                fontSize = parseFloat(savedFontSize);
                $('#story-content').css('font-size', fontSize + 'px');
            }
            
            // Dark mode toggle
            $('#dark-mode-toggle').click(function() {
                $('body').toggleClass('dark-mode');
                const isDarkMode = $('body').hasClass('dark-mode');
                localStorage.setItem('dark-mode', isDarkMode);
                
                if (isDarkMode) {
                    $('#dark-mode-toggle').html('<i class="fas fa-sun"></i>');
                } else {
                    $('#dark-mode-toggle').html('<i class="fas fa-moon"></i>');
                }
            });
            
            // Remember dark mode preference
            const savedDarkMode = localStorage.getItem('dark-mode');
            if (savedDarkMode === 'true') {
                $('body').addClass('dark-mode');
                $('#dark-mode-toggle').html('<i class="fas fa-sun"></i>');
            }
            
            // Reading progress bar
            $(window).scroll(function() {
                const scrollTop = $(window).scrollTop();
                const docHeight = $(document).height();
                const winHeight = $(window).height();
                const scrollPercent = (scrollTop) / (docHeight - winHeight);
                const scrollWidth = scrollPercent * 100;
                $('.reading-progress').css('width', scrollWidth + '%');
            });
            
            // Share functionality
            $('#share-button').click(function() {
                if (navigator.share) {
                    navigator.share({
                        title: "{{ $story->name }}",
                        text: "Đọc truyện {{ $story->name }} trên Chocopie",
                        url: window.location.href
                    })
                    .catch(console.error);
                } else {
                    alert("Copy link này để chia sẻ: " + window.location.href);
                }
            });
        });
    </script>
</body>
</html>