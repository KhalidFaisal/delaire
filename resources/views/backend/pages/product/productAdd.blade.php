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
      <h3 class=" text-center">Add new Product</h3><br>
      <form action="{{ route('create.product')}}" method="POST"  enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                  <label for="blogCategory">Product Title:</label>
                  <input type="text" class="form-control" id="pro_title" name="pro_title" placeholder="Enter Title" required>
                </div>
                <div class="row">
                @php
                    $cats = App\Models\Procategory::orderBy('created_at', 'asc')->get();
                  
                      $count=0;
                @endphp
                <div class="form-group col-md-4">
                    <label for="category">Choose Main Category:</label>
                    <select class="form-control" id="main_category" name="main_category" required>
                    <option >Select Main category</option>
                              @foreach( $cats as $category)
                                  @php
                              
                                  $count++;
                                  @endphp    
                              <option value="{{ $category->id }}">{{ $category->proCat_name }}</option>
                              
                              @endforeach
                    </select>
                </div>


                @php
                
                    $subcats = App\Models\Prosubcategory::orderBy('created_at', 'asc')->get();
                  
                      $count=0;
                @endphp
               
                  <div class="form-group col-md-4">
                    <label for="category">Choose Sub Category:</label>
                    <select class="form-control" id="sub_category" name="sub_category" required>
                    <option >Select Sub category</option>
                              @foreach( $subcats as $subcategory)
                                  @php
                              
                                  $count++;
                                  @endphp    
                              <option value="{{ $subcategory->id }}">{{ $subcategory->proSubCat_name }}</option>
                              
                              @endforeach
                    </select>
                </div>


                @php
                
                    $brandsql = App\Models\Probrand::orderBy('created_at', 'asc')->get();
                  
                      $count=0;
                @endphp
                <div class="form-group col-md-4">
                    <label for="category">Choose Brand:</label>
                    <select class="form-control" id="pro_brand" name="pro_brand" required>
                    <option >Select Brand</option>
                              @foreach( $brandsql as $brand)
                                  @php
                              
                                  $count++;
                                  @endphp    
                              <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                              
                              @endforeach
                    </select>
                </div>
               

                <div class="form-group col-md-4">
                  <label for="pro_model">Model:</label>
                  <input type="text" class="form-control" id="pro_model" name="pro_model" placeholder="Enter Model" required>
                </div>
                

                 
                 <!-- Warranty -->
                 <div class="form-group col-md-4">
                    <label for="pro_waranty">Warranty:</label>
                    <input type="text" class="form-control" id="pro_waranty" name="pro_waranty" placeholder="Warranty" required>
                 </div>
              
                <!-- Upload Chart (Replacing Data Sheet) -->
                <div class="form-group col-md-4">
                    <label for="pro_datasheet">Upload Chart</label><br>        
                    <input type="file" class="form-control-file" id="pro_datasheet" name="pro_datasheet" accept="image/*">
                </div>

                <div class="form-group col-md-4">
                  <label for="pro_price">Regular Price (Required):</label>
                  <input type="number" step="0.01" class="form-control" id="pro_price" name="pro_price" placeholder="Enter Regular Price" required>
                </div>

                <div class="form-group col-md-4">
                  <label for="pro_sprice">Special Price (Optional):</label>
                  <input type="number" step="0.01" class="form-control" id="pro_sprice" name="pro_sprice" placeholder="Enter Special Price">
                </div>
                </div>

                <!-- Product Images -->
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="pro_img1">Upload Product Image 1 (Required, 800x800)</label><br>        
                        <input type="file" class="form-control-file" id="pro_img1" name="pro_img1" accept="image/*" onchange="validateImage(this)" data-crop="true" data-crop-ratio="1" required>
                    </div>
                    <div class="col-md-4">
                        <label for="pro_img2">Product Image 2</label>
                        <input type="file" class="form-control-file" id="pro_img2" name="pro_img2" accept="image/*" onchange="validateImage(this)" data-crop="true" data-crop-ratio="1">
                    </div>
                    <div class="col-md-4">
                        <label for="pro_img3" >Product Image 3</label>
                        <input type="file" class="form-control-file" id="pro_img3" name="pro_img3" accept="image/*" onchange="validateImage(this)" data-crop="true" data-crop-ratio="1">
                    </div>
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

                 <div class="form-group">
                    <label for="pro_short_desc">Short Description:</label>
                    <textarea class="form-control" id="pro_short_desc" name="pro_short_desc" placeholder="Short Description" rows="3"></textarea>
                </div>

                    <div class="form-group">
                        <label for="pro_desc">Description:</label>
                        <textarea class="form-control" id="pro_desc" name="pro_desc" placeholder="Product Description" required></textarea>
                    </div>

                <button type="submit" class="btn btn-primary">Submit</button>
                
      

                
                <!-- <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>

                  <script>
                      tinymce.init({
                          selector: '#pro_desc',
                          plugins: 'advlist autolink lists link image charmap print preview anchor',
                          toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | outdent indent | table',
                          height: 300,
                      });
                  </script> -->
      </form>

<script>
    // Valid image dimensions
    function validateImage(input) {
        var file = input.files[0];
        if (file && file.type.startsWith('image/')) {
            var img = new Image();
            var reader = new FileReader();
            reader.onload = function (e) {
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
            img.onload = function () {
                // The cropper now handles resizing and aspect ratios,
                // so we no longer need to restrict uploaded width/height here.
                return true;
            };
        } else {
            alert('Please upload a valid image file.');
            input.value = ''; 
        }
    }


</script>





      
 

@endsection

@section('script')
    <script>

    </script>
@endsection