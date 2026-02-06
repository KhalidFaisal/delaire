@extends('main_view.pages.user_dashboard.layout')

@section('title', 'Wishlist')

@section('content')
<h4 class="mb-4">Wishlist</h4>
@if($items->isEmpty())
    <p class="text-muted">Your wishlist is empty.</p>
@else
    <div class="row g-3">
        @foreach($items as $item)
        <div class="col-6 col-md-4 col-lg-3">
            <div class="card h-100">
                @if($item->product->pro_img1 ?? null)
                    <img src="{{ asset('uploads/' . $item->product->pro_img1) }}" class="card-img-top" alt="" style="height:140px;object-fit:cover;">
                @else
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height:140px;"><i class="fas fa-image fa-2x text-muted"></i></div>
                @endif
                <div class="card-body py-2">
                    <h6 class="card-title small">{{ $item->product->pro_title ?? 'Product' }}</h6>
                    <p class="card-text small text-muted mb-1">{{ $item->product->pro_price ?? '—' }}</p>
                    <div class="d-flex gap-2 justify-content-center mt-2">
                        <a href="{{ route('product.show', $item->product->id) }}" class="btn btn-sm btn-primary">Shop Now</a>
                        <form action="{{ route('user.wishlist.destroy', $item->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    {{ $items->links() }}
@endif
@endsection
