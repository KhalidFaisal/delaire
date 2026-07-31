@extends('backend.layout.template')
@section('title')
    Add Testimonial
@endsection
@section('body-content')
<div class="container card">
    <div class="content-container p-4">
        <h3 class="text-center">Add Testimonial</h3><br>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('store.testimonial') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="name">Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            
            <div class="form-group">
                <label for="designation">Designation</label>
                <input type="text" class="form-control" id="designation" name="designation">
            </div>

            <div class="form-group">
                <label for="message">Message <span class="text-danger">*</span></label>
                <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
            </div>

            <div class="form-group">
                <label for="rating">Rating (1-5) <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="rating" name="rating" min="1" max="5" value="5" required>
            </div>

            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" class="form-control-file" id="image" name="image" accept="image/*" data-crop="true">
            </div>

            <button type="submit" class="btn btn-primary">Save Testimonial</button>
            <a href="{{ route('manage.testimonial') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
