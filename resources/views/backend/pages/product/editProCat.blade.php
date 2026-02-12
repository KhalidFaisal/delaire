@extends('backend.layout.template')
@section('title')
    Edit Product Category
@endsection
@section('body-content')
<div class="container card ">
    <div class="content-container p-4 ">
   
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
      <h3 class=" text-center">Edit Product Category</h3><br>
      <form action="{{ route('update.proCat', $category->id)}}" method="POST"  enctype="multipart/form-data">
        @csrf
        <div class="form-group">
          <label for="pro_category">Product Category Name:</label>
          <input type="text" class="form-control" id="pro_category" name="pro_category" placeholder="Enter the Product Category" value="{{ $category->proCat_name }}" required>
        </div>
       
        <!-- SEO Section -->
        <br><h5 class="text-primary">SEO Information</h5><hr>
        <div class="form-group">
          <label for="meta_title">Meta Title:</label>
          <input type="text" class="form-control" id="meta_title" name="meta_title" placeholder="Meta Title" value="{{ $category->meta_title }}">
        </div>
        <div class="form-group">
          <label for="meta_description">Meta Description:</label>
          <textarea class="form-control" id="meta_description" name="meta_description" placeholder="Meta Description" rows="2">{{ $category->meta_description }}</textarea>
        </div>
        <div class="form-group">
          <label for="meta_keywords">Meta Keywords:</label>
          <textarea class="form-control" id="meta_keywords" name="meta_keywords" placeholder="Meta Keywords (comma separated)" rows="2">{{ $category->meta_keywords }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Update Category</button>
      </form>
    </div>
  </div>
@endsection
