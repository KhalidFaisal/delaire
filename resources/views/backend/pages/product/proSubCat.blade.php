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
      <h3 class=" text-center">Add new Sub Category</h3><br>
      
      <form action="{{ route('create.proSubCategory')}}" method="POST"  enctype="multipart/form-data">
        @csrf
        @php
      $cats = App\Models\Procategory::orderBy('created_at', 'asc')->get();
    
        $count=0;
     @endphp
        <div class="form-group">
            <label for="category">Choose Main Category:</label>
            
            <select class="form-control" id="main_category" name="main_category" required>
            <option >Choose a category</option>
            @foreach( $cats as $category)
        @php
     
        $count++;
        @endphp    
            <option value="{{ $category->id }}">{{ $category->proCat_name }}</option>
            
            @endforeach
                <!-- Add more category options as needed -->
            </select>
        </div>

        <div class="form-group">
          <label for="blogCategory">Product Sub Category:</label>
          <input type="text" class="form-control" id="pro_Subcategory" name="pro_Subcategory" placeholder="Enter the Product Category" required>
        </div>
       
        

        <button type="submit" class="btn btn-primary">Submit</button>
      </form><hr>


    <!-- Table -->
    <table class="table table-striped">
    <thead>
        <tr>
            <th>SN</th>
            <th>Main Category</th>
            <th>Sub Category</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>
        @php
            $subcats = App\Models\Prosubcategory::orderBy('created_at', 'desc')->get();
        @endphp
        @forelse ($subcats as $index => $subcat)
        <tr>
            <td>{{ $index + 1 }}</td>

            <td>
                {{ $subcat->mainCategory->proCat_name ?? 'N/A' }}
            </td>

            <td>
                {{ $subcat->proSubCat_name }}
            </td>

            <td>
                <form method="POST" action="{{ route('destroy.proSubCategory') }}">
                    @csrf
                    <input type="hidden" name="id" value="{{ $subcat->id }}">
                    <button type="submit" class="btn btn-danger btn-sm">
                        Delete
                    </button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="4" class="text-center text-muted py-4">
                No data found in this table.
            </td>
        </tr>
        @endforelse
    </tbody>
</table>
    </div>
    
  </div>
 

  <!-- Add Bootstrap JS and jQuery scripts (optional but may be required for certain features) -->
 

@endsection

@section('script')
    <script>

    </script>
@endsection