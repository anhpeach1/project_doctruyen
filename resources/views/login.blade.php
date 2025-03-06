<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập / Đăng ký</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <div class="container">
        <div class="left-side">
            <div class="left-content">
                <h2>Bạn chưa có tài khoản ?</h2>
                <p>Hãy đăng ký tài khoản ngay để đọc hàng ngàn bộ truyện tranh miễn phí.</p>
                <a href="{{ route('register') }}">
                    <button class="button">
                        <div><span>Đăng Ký</span></div>
                    </button>
                </a>
                <img src="{{ asset('images/anh_trai.png') }}" alt="Trái">
            </div>
        </div>
        <div class="right-side">
            <div class="login-content">
                <h2>Đăng nhập</h2>
                <div class="profile-image">
                <img src="{{ asset('images/anh_phai.png') }}" alt="Phải">
                </div>
                
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="input-group">
                        <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required autofocus>
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="input-group">
                        <input type="password" name="password" placeholder="Password" required>
                        @error('password')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit" class="btn">
                        <span>Đăng nhập</span>
                    </button>
                    <div class="register-option mobile-only">
                        <p>Chưa có tài khoản? <a href="{{ route('register') }}" class="register-link">Đăng ký ngay</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>