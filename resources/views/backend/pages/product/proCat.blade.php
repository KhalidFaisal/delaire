@extends('backend.layout.template')
@section('title')
    Dashboard
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
      <h3 class=" text-center">Create new Product Category</h3><br>
      <form action="{{ route('create.proCategory')}}" method="POST"  enctype="multipart/form-data">
        @csrf
        <div class="form-group">
          <label for="blogCategory">Product Category:</label>
          <input type="text" class="form-control" id="pro_category" name="pro_category" placeholder="Enter the Product Category" required>
        </div>
       
        <!-- SEO Section -->
        <br><h5 class="text-primary">SEO Information</h5><hr>
        <div class="form-group">
          <label for="meta_title">Meta Title:</label>
          <input type="text" class="form-control" id="meta_title" name="meta_title" placeholder="Meta Title">
        </div>
        <div class="form-group">
          <label for="meta_description">Meta Description:</label>
          <textarea class="form-control" id="meta_description" name="meta_description" placeholder="Meta Description" rows="2"></textarea>
        </div>
        <div class="form-group">
          <label for="meta_keywords">Meta Keywords:</label>
          <textarea class="form-control" id="meta_keywords" name="meta_keywords" placeholder="Meta Keywords (comma separated)" rows="2"></textarea>
        </div>
        <br>
       
        

        <button type="submit" class="btn btn-primary">Submit</button>
      </form><hr>




      <!-- <input type="text" id="searchInput" class="form-control mb-3" placeholder="Search by title...">  -->
  
    <!-- Table -->
    <div class="table-responsive"><!-- Table -->
   
    <table class="table-hover table table-striped border">
      <thead >
        <tr>
          <th>SN</th>
          <th>Category Name</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
      @php
      $cats = App\Models\Procategory::orderBy('created_at', 'asc')->get();
    
        $count=0;
     @endphp
        <!-- Table rows with data -->
        @forelse( $cats as $category)
        @php
     
        $count++;
        @endphp
        <tr>
          <td>{{ $count }}</td>
          <td> {{ $category->proCat_name }}</td>
  
          <td>
         
          <a href="{{ route('edit.proCat', $category->id) }}">
              <button class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i></button>
          </a>
         
          <form method="post" action="{{ route('destroy.proCat') }}">
            @csrf
            <input type="hidden" name="id" value="{{ $category->id }}">
            <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i></button>
          </form>  
          
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="3" class="text-center text-muted py-4">No data found in this table.</td>
        </tr>
        @endforelse
        
        <!-- Add more rows as needed -->
      </tbody>
    </table> 
    </div>
    </div>
    
  </div>
 

  <!-- Add Bootstrap JS and jQuery scripts (optional but may be required for certain features) -->
 

@endsection

@section('script')
    <script>

    </script>
@endsection