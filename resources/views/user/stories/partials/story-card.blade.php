<div class="col-md-3 mb-4">
    <div class="card story-card">
        <a href="{{ route('stories.show', $story->id) }}">
            @if($story->cover_image)
                <img src="{{ Storage::url($story->cover_image) }}" class="story-cover" alt="{{ $story->name }}">
            @else
                <div class="story-cover bg-secondary d-flex align-items-center justify-content-center">
                    <i class="fas fa-book fa-2x text-white"></i>
                </div>
            @endif
        </a>
        <div class="card-body">
            <h5 class="card-title">
                <a href="{{ route('stories.show', $story->id) }}" class="text-decoration-none text-dark">
                    {{ $story->name }}
                </a>
            </h5>
            <div class="d-flex align-items-center mb-2">
                <small class="text-muted">
                    <i class="fas fa-user-edit me-1"></i>{{ $story->author->name }}
                </small>
            </div>
            <div class="mb-2">
                @foreach($story->categories as $category)
                    <span class="badge category-badge">{{ $category->name }}</span>
                @endforeach
            </div>
            <div class="d-flex justify-content-between text-muted small">
                <span><i class="fas fa-eye me-1"></i>{{ $story->views ?? 0 }}</span>
                <span><i class="fas fa-comments me-1"></i>{{ $story->comments->count() }}</span>
                <span><i class="fas fa-clock me-1"></i>{{ $story->created_at->diffForHumans() }}</span>
            </div>
        </div>
    </div>
</div>