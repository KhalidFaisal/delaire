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
                
                  <div class="form-group col-md-4">
                    <label for="pro_price">Price:</label>
                    <input type="text" class="form-control" id="pro_price" name="pro_price" placeholder="Enter Price" required>
                  </div>

                <div class="row">
                 <div class="form-group col-md-4">
                    <label for="pro_sprice">Special Price:</label>
                    <input type="text" class="form-control" id="pro_sprice" name="pro_sprice" placeholder="Enter Special Price" required>
                 </div>
                 
                 <!-- Warranty -->
                 <div class="form-group col-md-4">
                    <label for="pro_waranty">Warranty:</label>
                    <input type="text" class="form-control" id="pro_waranty" name="pro_waranty" placeholder="Warranty" required>
                 </div>
              
                <!-- Upload Size Chart (Replacing Data Sheet) -->
                <div class="form-group col-md-4">
                    <label for="pro_datasheet">Upload Size Chart</label><br>        
                    <input type="file" class="form-control-file" id="pro_datasheet" name="pro_datasheet" accept="image/*">
                </div>
                </div>

                <!-- Product Images -->
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="pro_img1">Upload Product Image 1 (Required, 800x800)</label><br>        
                        <input type="file" class="form-control-file" id="pro_img1" name="pro_img1" accept="image/*" onchange="validateImage(this)" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="pro_img2">Upload Product Image 2 (Optional, 800x800)</label><br>        
                        <input type="file" class="form-control-file" id="pro_img2" name="pro_img2" accept="image/*" onchange="validateImage(this)">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="pro_img3">Upload Product Image 3 (Optional, 800x800)</label><br>        
                        <input type="file" class="form-control-file" id="pro_img3" name="pro_img3" accept="image/*" onchange="validateImage(this)">
                    </div>
                </div>

                <!-- Sizes and Stocks -->
                <div class="form-group">
                    <label>Product Sizes & Stock</label>
                    <div id="size-container">
                        <div class="row mb-2">
                            <div class="col-md-5">
                                <input type="text" name="sizes[]" class="form-control" placeholder="Size (e.g., XL, 42)">
                            </div>
                            <div class="col-md-5">
                                <input type="number" name="stocks[]" class="form-control stock-input" placeholder="Stock" min="0" onchange="calculateTotalStock()">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-success add-size">+</button>
                            </div>
                        </div>
                    </div>
                </div>

                 <div class="form-group">
                    <label for="pro_qty">Total Quantity (Calculated from sizes):</label>
                    <input type="number" class="form-control" id="pro_qty" name="pro_qty" placeholder="0" readonly>
                </div>

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
                // User requirement said 800x800, keeping this check
                // but might be annoying for optional images if strict. 
                // Keeping it as per existing code logic.
                if (img.width === 800 && img.height === 800) {
                } else {
                    alert('Image must be 800x800 pixels.');
                    input.value = ''; 
                }
            };
        } else {
            alert('Please upload a valid image file.');
            input.value = ''; 
        }
    }

    // Dynamic Size Fields
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('size-container');
        
        container.addEventListener('click', function(e) {
            if (e.target.classList.contains('add-size')) {
                const row = document.createElement('div');
                row.className = 'row mb-2';
                row.innerHTML = `
                    <div class="col-md-5">
                        <input type="text" name="sizes[]" class="form-control" placeholder="Size">
                    </div>
                    <div class="col-md-5">
                        <input type="number" name="stocks[]" class="form-control stock-input" placeholder="Stock" min="0" onchange="calculateTotalStock()">
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger remove-size">X</button>
                    </div>
                `;
                container.appendChild(row);
            } else if (e.target.classList.contains('remove-size')) {
                e.target.closest('.row').remove();
                calculateTotalStock();
            }
        });
    });

    function calculateTotalStock() {
        let total = 0;
        document.querySelectorAll('.stock-input').forEach(input => {
            total += parseInt(input.value) || 0;
        });
        document.getElementById('pro_qty').value = total;
    }
</script>





      
 

@endsection

@section('script')
    <script>

    </script>
@endsection