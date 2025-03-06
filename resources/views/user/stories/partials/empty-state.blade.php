<!-- resources/views/user/stories/partials/empty-state.blade.php -->
<div class="col-12 text-center py-5">
    <div class="text-muted">
        <i class="fas fa-book-open fa-3x mb-3"></i>
        <p>Chưa có truyện nào được đăng tải.</p>
        <a href="{{ route('user.stories.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Tạo Truyện Mới
        </a>
    </div>
</div>