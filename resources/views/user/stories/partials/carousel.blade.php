<div id="mainCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
    <div class="carousel-indicators">
        @foreach($featuredStories ?? [] as $key => $story)
            <button type="button" 
                    data-bs-target="#mainCarousel" 
                    data-bs-slide-to="{{ $key }}" 
                    class="{{ $loop->first ? 'active' : '' }}"
                    aria-current="{{ $loop->first ? 'true' : 'false' }}"
                    aria-label="Slide {{ $key + 1 }}">
            </button>
        @endforeach
    </div>
    <div class="carousel-inner rounded">
        @forelse($featuredStories ?? [] as $story)
            <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                <a href="{{ route('stories.show', $story->id) }}">
                    @if($story->cover_image)
                        <img src="{{ Storage::url($story->cover_image) }}" 
                             class="d-block w-100" 
                             alt="{{ $story->name }}"
                             style="height: 400px; object-fit: cover;">
                    @else
                        <div class="bg-secondary d-flex align-items-center justify-content-center" style="height: 400px;">
                            <i class="fas fa-book fa-3x text-white"></i>
                        </div>
                    @endif
                    <div class="carousel-caption">
                        <h3>{{ $story->name }}</h3>
                        <p>{{ Str::limit($story->description, 100) }}</p>
                    </div>
                </a>
            </div>
        @empty
            <div class="carousel-item active">
                <div class="bg-secondary d-flex align-items-center justify-content-center" style="height: 400px;">
                    <div class="text-white text-center">
                        <i class="fas fa-book fa-3x mb-3"></i>
                        <h3>Chưa có truyện nổi bật</h3>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>