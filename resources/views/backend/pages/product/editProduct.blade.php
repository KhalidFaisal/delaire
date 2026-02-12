@extends('backend.layout.template')
@section('title')
    Edit Product
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
      <h3 class=" text-center">Edit Product</h3><br>
      <form action="{{ route('update.product', $product->id)}}" method="POST"  enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                  <label for="blogCategory">Product Title:</label>
                  <input type="text" class="form-control" id="pro_title" name="pro_title" placeholder="Enter Title" value="{{ $product->pro_title }}" required>
                </div>
                <div class="row">
                @php
                    $cats = App\Models\Procategory::orderBy('created_at', 'asc')->get();
                @endphp
                <div class="form-group col-md-4">
                    <label for="category">Choose Main Category:</label>
                    <select class="form-control" id="main_category" name="main_category" required>
                    <option >Select Main category</option>
                              @foreach( $cats as $category)
                              <option value="{{ $category->id }}" @if($category->id == $product->main_category) selected @endif>{{ $category->proCat_name }}</option>
                              @endforeach
                    </select>
                </div>


                @php
                    $subcats = App\Models\Prosubcategory::orderBy('created_at', 'asc')->get();
                @endphp
               
                  <div class="form-group col-md-4">
                    <label for="category">Choose Sub Category:</label>
                    <select class="form-control" id="sub_category" name="sub_category" required>
                    <option >Select Sub category</option>
                              @foreach( $subcats as $subcategory)
                              <option value="{{ $subcategory->id }}" @if($subcategory->id == $product->sub_category) selected @endif>{{ $subcategory->proSubCat_name }}</option>
                              @endforeach
                    </select>
                </div>


                @php
                    $brandsql = App\Models\Probrand::orderBy('created_at', 'asc')->get();
                @endphp
                <div class="form-group col-md-4">
                    <label for="category">Choose Brand:</label>
                    <select class="form-control" id="pro_brand" name="pro_brand" required>
                    <option >Select Brand</option>
                              @foreach( $brandsql as $brand)
                              <option value="{{ $brand->id }}" @if($brand->id == $product->pro_brand) selected @endif>{{ $brand->brand_name }}</option>
                              @endforeach
                    </select>
                </div>
               

                <div class="form-group col-md-4">
                  <label for="pro_model">Model:</label>
                  <input type="text" class="form-control" id="pro_model" name="pro_model" placeholder="Enter Model" value="{{ $product->pro_model }}" required>
                </div>
                
                  <div class="form-group col-md-4">
                    <label for="pro_price">Price:</label>
                    <input type="text" class="form-control" id="pro_price" name="pro_price" placeholder="Enter Price" value="{{ $product->pro_price }}" required>
                  </div>

                <div class="row">
                 <div class="form-group col-md-4">
                    <label for="pro_sprice">Special Price:</label>
                    <input type="text" class="form-control" id="pro_sprice" name="pro_sprice" placeholder="Enter Special Price" value="{{ $product->pro_sprice }}" required>
                 </div>
                 
                 <!-- Warranty -->
                 <div class="form-group col-md-4">
                    <label for="pro_waranty">Warranty:</label>
                    <input type="text" class="form-control" id="pro_waranty" name="pro_waranty" placeholder="Warranty" value="{{ $product->pro_waranty }}" required>
                 </div>
              
                <!-- Upload Size Chart -->
                <div class="form-group col-md-4">
                    <label for="pro_datasheet">Upload Size Chart</label><br>
                    @if($product->pro_size_chart)
                        <img src="{{ asset('uploads/'.$product->pro_size_chart) }}" height="50"><br>
                    @endif
                    <input type="file" class="form-control-file" id="pro_datasheet" name="pro_datasheet" accept="image/*">
                </div>
                </div>

                <!-- Product Images -->
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="pro_img1">Upload Product Image 1 (800x800)</label><br>
                        @if($product->pro_img1)
                        <img src="{{ asset('uploads/'.$product->pro_img1) }}" height="50"><br>
                        @endif        
                        <input type="file" class="form-control-file" id="pro_img1" name="pro_img1" accept="image/*" onchange="validateImage(this)">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="pro_img2">Upload Product Image 2 (800x800)</label><br>
                        @if($product->pro_img2)
                        <img src="{{ asset('uploads/'.$product->pro_img2) }}" height="50"><br>
                        @endif        
                        <input type="file" class="form-control-file" id="pro_img2" name="pro_img2" accept="image/*" onchange="validateImage(this)">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="pro_img3">Upload Product Image 3 (800x800)</label><br>
                         @if($product->pro_img3)
                        <img src="{{ asset('uploads/'.$product->pro_img3) }}" height="50"><br>
                        @endif       
                        <input type="file" class="form-control-file" id="pro_img3" name="pro_img3" accept="image/*" onchange="validateImage(this)">
                    </div>
                </div>

                <!-- Sizes and Stocks -->
                <div class="form-group">
                    <label>Product Sizes & Stock</label>
                    <div id="size-container">
                        @php
                            $sizes = App\Models\ProductSize::where('product_id', $product->id)->get();
                        @endphp
                        @if($sizes->count() > 0)
                            @foreach($sizes as $size)
                            <div class="row mb-2">
                                <div class="col-md-5">
                                    <input type="text" name="sizes[]" class="form-control" placeholder="Size (e.g., XL, 42)" value="{{ $size->size }}">
                                </div>
                                <div class="col-md-5">
                                    <input type="number" name="stocks[]" class="form-control stock-input" placeholder="Stock" min="0" value="{{ $size->stock }}" onchange="calculateTotalStock()">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger remove-size">X</button>
                                </div>
                            </div>
                            @endforeach
                        @else
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
                        @endif
                    </div>
                     <!-- Button to add new size row if there are existing ones -->
                     @if($sizes->count() > 0)
                        <button type="button" class="btn btn-success add-size mt-2">+</button>
                     @endif
                </div>

                 <div class="form-group">
                    <label for="pro_qty">Total Quantity (Calculated from sizes):</label>
                    <input type="number" class="form-control" id="pro_qty" name="pro_qty" placeholder="0" value="{{ $product->pro_qty }}" readonly>
                </div>

                 <!-- SEO Section -->
                 <br><h5 class="text-primary">SEO Information</h5><hr>
                 <div class="form-group">
                    <label for="meta_title">Meta Title:</label>
                    <input type="text" class="form-control" id="meta_title" name="meta_title" placeholder="Meta Title" value="{{ $product->meta_title }}">
                 </div>
                 <div class="form-group">
                    <label for="meta_description">Meta Description:</label>
                    <textarea class="form-control" id="meta_description" name="meta_description" placeholder="Meta Description" rows="2">{{ $product->meta_description }}</textarea>
                 </div>
                 <div class="form-group">
                    <label for="meta_keywords">Meta Keywords:</label>
                    <textarea class="form-control" id="meta_keywords" name="meta_keywords" placeholder="Meta Keywords (comma separated)" rows="2">{{ $product->meta_keywords }}</textarea>
                 </div>
                 <br>

                 <div class="form-group">
                    <label for="pro_short_desc">Short Description:</label>
                    <textarea class="form-control" id="pro_short_desc" name="pro_short_desc" placeholder="Short Description" rows="3">{{ $product->pro_short_desc }}</textarea>
                </div>

                    <div class="form-group">
                        <label for="pro_desc">Description:</label>
                        <textarea class="form-control" id="pro_desc" name="pro_desc" placeholder="Product Description" required>{{ $product->pro_desc }}</textarea>
                    </div>

                <button type="submit" class="btn btn-primary">Update Product</button>
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
        
        // Add listener for the separate add button if it exists (when sizes exist)
        const outerAddBtn = document.querySelector('.add-size.mt-2');
        if(outerAddBtn) {
            outerAddBtn.addEventListener('click', function() {
                addRow();
            });
        }

        container.addEventListener('click', function(e) {
            if (e.target.classList.contains('add-size')) {
                addRow();
            } else if (e.target.classList.contains('remove-size')) {
                e.target.closest('.row').remove();
                calculateTotalStock();
            }
        });

        function addRow() {
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
        }
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
