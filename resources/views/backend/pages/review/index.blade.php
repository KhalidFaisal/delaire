@extends('backend.layout.template')

@section('body-content')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h5>Manage Client Reviews</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Client Name</th>
                                <th>Phone Number</th>
                                <th>Rating</th>
                                <th>Review</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reviews as $review)
                            <tr>
                                <td>
                                    @if($review->product)
                                        <div class="d-flex align-items-center">
                                            @if($review->product->pro_img1)
                                                <img src="{{ asset('uploads/' . $review->product->pro_img1) }}" 
                                                     alt="Product" 
                                                     class="lazy-image img-fluid rounded me-2" 
                                                     style="width: 40px; height: 40px; object-fit: cover;" loading="lazy" >
                                            @endif
                                            <span class="d-block text-truncate" style="max-width: 150px;" title="{{ $review->product->pro_title }}">
                                                {{ $review->product->pro_title }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-muted">Product Deleted</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $review->user ? $review->user->name : 'Unknown User' }}
                                </td>
                                <td>
                                    @if($review->user && $review->user->orders->isNotEmpty())
                                        {{ $review->user->orders->first()->shipping_phone ?? 'N/A' }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fa fa-star{{ $i <= $review->rating ? '' : '-o' }} text-warning"></i>
                                    @endfor
                                    ({{ $review->rating }})
                                </td>
                                <td>
                                    <p class="mb-0" style="max-width: 300px;">
                                        {{ $review->comment ?? 'No comment' }}
                                    </p>
                                </td>
                                <td>{{ $review->created_at->format('d M, Y') }}</td>
                                <td>
                                    <form action="{{ route('destroy.review', $review->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this review?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete Review">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                            @if($reviews->isEmpty())
                            <tr>
                                <td colspan="7" class="text-center text-muted">No reviews found.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                    
                    <div class="mt-4">
                        {{ $reviews->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
