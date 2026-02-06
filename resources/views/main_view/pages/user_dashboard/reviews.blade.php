@extends('main_view.pages.user_dashboard.layout')

@section('title', 'My Reviews')

@section('content')
<h4 class="mb-4 fw-bold">My Reviews</h4>

<!-- Products Waiting for Review -->
@if(isset($productsToReview) && $productsToReview->count() > 0)
<div class="mb-5">
    <h5 class="mb-3 text-muted">Waiting for Review</h5>
    <div class="row g-3">
        @foreach($productsToReview as $product)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm p-3">
                <div class="d-flex align-items-center mb-3">
                    <img src="{{ asset('uploads/'. $product->pro_img1) }}" alt="{{ $product->pro_title }}" class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                    <div class="ms-3">
                        <h6 class="mb-0 fw-bold text-dark">{{ Str::limit($product->pro_title, 30) }}</h6>
                        <small class="text-muted">Purchased</small>
                    </div>
                </div>
                <button type="button" class="btn btn-primary w-100 btn-sm" data-bs-toggle="modal" data-bs-target="#writeReview{{ $product->id }}">
                    Write a Review
                </button>
            </div>
        </div>

        <!-- Write Review Modal -->
        <div class="modal fade" id="writeReview{{ $product->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('user.reviews.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div class="modal-header border-0 pb-0">
                            <h5 class="modal-title fw-bold">Review Product</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="d-flex align-items-center mb-4 p-3 bg-light rounded">
                                <img src="{{ asset('uploads/'. $product->pro_img1) }}" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                <div class="ms-3">
                                    <h6 class="mb-0 fw-bold">{{ $product->pro_title }}</h6>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Rating</label>
                                <div class="rating-css">
                                    <div class="star-icon">
                                        <input type="radio" value="1" name="rating" checked id="rating1-{{$product->id}}">
                                        <label for="rating1-{{$product->id}}" class="fa fa-star"></label>
                                        <input type="radio" value="2" name="rating" id="rating2-{{$product->id}}">
                                        <label for="rating2-{{$product->id}}" class="fa fa-star"></label>
                                        <input type="radio" value="3" name="rating" id="rating3-{{$product->id}}">
                                        <label for="rating3-{{$product->id}}" class="fa fa-star"></label>
                                        <input type="radio" value="4" name="rating" id="rating4-{{$product->id}}">
                                        <label for="rating4-{{$product->id}}" class="fa fa-star"></label>
                                        <input type="radio" value="5" name="rating" id="rating5-{{$product->id}}">
                                        <label for="rating5-{{$product->id}}" class="fa fa-star"></label>
                                    </div>
                                </div>
                                <style>
                                    .rating-css div { color: #ffe400; font-size: 20px; font-family: sans-serif; font-weight: 800; text-transform: uppercase; padding: 10px 0; }
                                    .rating-css input { display: none; }
                                    .rating-css input + label { font-size: 20px; text-shadow: 1px 1px 0 #ffe400; cursor: pointer; }
                                    .rating-css input:checked + label ~ label { color: #838383; }
                                    .rating-css label:active { transform: scale(0.8); transition: 0.3s all; }
                                </style>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Your Review</label>
                                <textarea name="comment" class="form-control" rows="4" placeholder="Share your experience..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Submit Review</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
<hr class="my-5 opacity-25">
@endif

<h5 class="mb-3 text-muted">My Past Reviews</h5>
@if($reviews->isEmpty())
    <div class="text-center py-5 bg-light rounded text-muted">
        <i class="fas fa-star fa-2x mb-3 opacity-25"></i>
        <p>You haven't posted any reviews yet.</p>
    </div>
@else
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Rating</th>
                    <th>Comment</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reviews as $r)
                <tr>
                    <td>{{ $r->product->pro_title ?? '—' }}</td>
                    <td>{{ $r->rating }} <i class="fas fa-star text-warning"></i></td>
                    <td>{{ Str::limit($r->comment, 40) }}</td>
                    <td>{{ $r->created_at->format('M d, Y') }}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editReview{{ $r->id }}">Edit</button>
                        <form action="{{ route('user.reviews.destroy', $r->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this review?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                <div class="modal fade" id="editReview{{ $r->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('user.reviews.update', $r->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit review</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-2">
                                        <label class="form-label">Rating</label>
                                        <select name="rating" class="form-select" required>
                                            @for($i=1;$i<=5;$i++) <option value="{{ $i }}" {{ $r->rating == $i ? 'selected' : '' }}>{{ $i }} star</option> @endfor
                                        </select>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">Comment</label>
                                        <textarea name="comment" class="form-control" rows="3">{{ $r->comment }}</textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $reviews->links() }}
@endif
@endsection
