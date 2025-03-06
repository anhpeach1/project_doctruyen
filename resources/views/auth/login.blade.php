<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="{{ asset('css/stylelogin.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <div class="login-container">
        <div class="login-left">
            <div class="signup-section">
                <h2>Bạn chưa có tài khoản ?</h2>
                <p>Hãy đăng ký tài khoản ngay để đọc hàng ngàn bộ truyện tranh miễn phí.</p>
                <a href="{{ route('register') }}"><button class="signup-button">ĐĂNG KÝ</button></a>
            </div>
            
        </div>
        <div class="login-right">
            <div class="login-form-section">
                <h2 class="login-title">Đăng nhập</h2>
                
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
                
                <form class="login-form" method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="input-group">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required autofocus autocomplete="username">
                        @error('email')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="input-group">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" name="password" placeholder="Password" required autocomplete="current-password">
                        @error('password')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="input-group">
                        <label class="remember-me">
                            <input type="checkbox" name="remember"> 
                            <span>Ghi nhớ đăng nhập</span>
                        </label>
                    </div>
                    <div class="forgot-password">
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}">
                                Quên mật khẩu?
                            </a>
                        @endif
                    </div>
                    <button type="submit" class="login-button">ĐĂNG NHẬP</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>