@extends('main_view.pages.user_dashboard.layout')

@section('title', 'My Reviews')

@section('content')
<h4 class="mb-4">My Reviews</h4>

<div class="card mb-4">
    <div class="card-header">Add a review</div>
    <div class="card-body">
        <form action="{{ route('user.reviews.store') }}" method="POST">
            @csrf
            <div class="row g-2">
                <div class="col-md-4">
                    <label class="form-label">Product</label>
                    <select name="product_id" class="form-select form-select-sm" required>
                        <option value="">Select product</option>
                        @foreach(\App\Models\Product::orderBy('pro_title')->get() as $p)
                            <option value="{{ $p->id }}">{{ $p->pro_title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Rating (1-5)</label>
                    <select name="rating" class="form-select form-select-sm" required>
                        @for($i=1;$i<=5;$i++) <option value="{{ $i }}">{{ $i }} star</option> @endfor
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Comment</label>
                    <input type="text" name="comment" class="form-control form-control-sm" placeholder="Your review">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary btn-sm">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

@if($reviews->isEmpty())
    <p class="text-muted">No reviews yet.</p>
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
