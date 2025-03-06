@extends('layouts.admin')

@section('title', 'Truyện Chờ Phê Duyệt')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-clock text-warning me-2"></i>Truyện Chờ Phê Duyệt
        </h1>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Quay lại Dashboard
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách truyện đang chờ duyệt</h6>
            <span class="badge bg-warning text-dark">{{ $pendingStories->total() }} truyện</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" width="5%">ID</th>
                            <th width="30%">Tên Truyện</th>
                            <th width="20%">Tác giả</th>
                            <th width="15%">Thể loại</th>
                            <th width="15%">Ngày gửi</th>
                            <th width="15%" class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingStories as $story)
                        <tr>
                            <td class="text-center">{{ $story->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($story->cover_image)
                                        <img src="{{ Storage::url($story->cover_image) }}" 
                                             class="img-thumbnail me-2" style="height: 50px; width: 35px; object-fit: cover;">
                                    @else
                                        <div class="bg-secondary me-2" style="height: 50px; width: 35px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-book text-white"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <strong>{{ $story->name }}</strong>
                                        @if(strlen($story->summary) > 0)
                                            <p class="text-muted small mb-0 text-truncate" style="max-width: 300px;">
                                                {{ Str::limit($story->summary, 50) }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-user-edit me-1 text-info"></i>
                                    {{ $story->author->name }}
                                </div>
                            </td>
                            <td>
                                @foreach($story->categories->take(2) as $category)
                                    <span class="badge bg-info text-white">{{ $category->name }}</span>
                                @endforeach
                                @if($story->categories->count() > 2)
                                    <span class="badge bg-secondary">+{{ $story->categories->count() - 2 }}</span>
                                @endif
                            </td>
                            <td>
                                <i class="far fa-clock me-1"></i>
                                {{ $story->updated_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.stories.review', $story->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye me-1"></i> Xem xét
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <div class="py-5">
                                    <i class="fas fa-check-circle text-success fa-3x mb-3"></i>
                                    <p class="text-muted">Không có truyện nào đang chờ phê duyệt</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4 d-flex justify-content-center">
                {{ $pendingStories->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Full-width layout overrides */
    body, html {
        width: 100% !important;
        max-width: 100% !important;
        overflow-x: hidden;
    }
    
    .container-fluid {
        max-width: 100% !important;
        width: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
    }
    
    #wrapper, .content, #content-wrapper, main, .container-fluid, .row {
        max-width: 100% !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 0 15px !important;
    }
    
    .card {
        margin-left: 0 !important;
        margin-right: 0 !important;
        width: 100% !important;
    }
    
    .table-responsive {
        overflow-x: auto;
    }
    
    .table {
        width: 100% !important;
    }
    
    #sidebar {
        width: auto !important;
        flex-shrink: 0;
    }
    
    #content {
        flex-grow: 1;
        width: calc(100% - sidebar-width) !important;
    }
</style>
@endpush