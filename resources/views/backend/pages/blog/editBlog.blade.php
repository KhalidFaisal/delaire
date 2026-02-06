@extends('backend.layout.template')
@section('title')
Dashboard
@endsection
@section('body-content')

@php
$blogName = App\Models\Blog::find($id);

@endphp

@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif
<div class="container card">
    <div class="content-container p-4">
        <h3 class="text-center">Edit Blog</h3><br>
        <form action="{{route('blog.updateb',$blogName->id)}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="blogTitle">Blog Title:</label>
                <input type="text" class="form-control" id="blog_title" name="blog_title" placeholder="Blog Title"
                    value="{{ $blogName->blog_title }}">
            </div>
            @php
      $cats = App\Models\Category::orderBy('created_at', 'asc')->get();
    
        $count=0;
     @endphp
            <div class="form-group">
                <label for="category">Category:</label>
                <select class="form-control" id="blog_category" name="blog_category" required>
                    @foreach( $cats as $category)
                    @php

                    $count++;
                    @endphp
                    <option  value="{{ $category->cat_name }}" {{ $category->cat_name == $blogName->blog_Cat ? 'selected' : '' }}>{{ $category->cat_name }}</option>
                    @endforeach
                    <!-- Add more category options as needed -->
                </select>
            </div>

            <div class="form-group">
                <label for="blogKey">Blog Keywords:</label>
                <input type="text" class="form-control" id="blog_key" name="blog_key" Value="{{ $blogName->blog_key }}"
                    required>
            </div>


            <div class="form-group">
                <label for="blogDescription">Blog Content:</label>
                <textarea class="form-control" id="blog_description" name="blog_description" placeholder="Write your blog content here">{{ $blogName->blog_description }}</textarea>
            </div>

            <div class="form-group">
                <label for="blogThumbnail">Blog Thumbnail:</label><br>
                <td><img src="{{ asset('uploads/'. $blogName->blog_image) }}" alt="Blog Image" width="250"></td><br><br>




                <input type="file" class="form-control-file" id="blog_image" name="blog_image" accept="image/*">
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

    </div>
</div><br>



<!-- Add Bootstrap JS and jQuery scripts (optional but may be required for certain features) -->


@endsection

@section('script')
  <!-- Add Summernote Assets -->
  <link rel="stylesheet" type="text/css" href="{{asset('backend/assets/css/summernote.css')}}">
  <script src="{{asset('backend/assets/js/editor/summernote/summernote.js')}}"></script>
  <script src="{{asset('backend/assets/js/editor/summernote/summernote.custom.js')}}"></script>

  <script>
      $(document).ready(function() {
          $('#blog_description').summernote({
              height: 300,
              placeholder: 'Write your blog content here...'
          });
      });
  </script>
@endsection