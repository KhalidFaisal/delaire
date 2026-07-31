@extends('backend.layout.template')
@section('title')
    Manage Products
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
     <h3 class=" text-center">All Products</h3><br>
     <div class="row" style="margin-bottom:20px;">
        <div class="col-md-3">
        <a href="{{route('add.product')}}" class=" col-md-12 btn btn-primary"><i class="fa fa-plus"></i>     Add Product</a><br>
        <button type="button" class="col-md-12 btn btn-success mt-2" data-toggle="modal" data-target="#bulkUploadModal" data-bs-toggle="modal" data-bs-target="#bulkUploadModal">
            <i class="fa fa-upload"></i> Bulk Upload
        </button>
        </div>
        <div class="col-md-3">
        <a href="{{route('manage.procat')}}" class=" col-md-12 btn btn-secondary"><i class="fa fa-plus"></i>     Add Category</a><br>
        </div>
        <div class="col-md-3">
        <a href="{{route('manage.proSubCat')}}" class=" col-md-12 btn btn-primary"><i class="fa fa-plus"></i> SubCategory</a><br>
        </div>
        <div class="col-md-3">
        <a href="{{route('manage.brand')}}" class=" col-md-12 btn btn-primary"><i class="fa fa-plus"></i>     Add Brand</a><br>
        </div>

     </div>
     <div class="row">
        <div class="col-md-12">
        <input type="text" id="searchInput" class="form-control mb-3" placeholder="Search by title, model or brand...">
        </div>

     </div>
    
      
  
    <!-- Table -->
    <div class="table-responsive">
    <table class="table table-striped" id="productTable">
      <thead>
        <tr>
          <th>SN</th>
          <th>Title</th>
          <th>Category</th>
          <th>Model</th>
          <th>Brand</th>
          <th>Price</th>
          <th>S.Price</th>
          <th>Image</th>
          <th>Stock</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody> 
      @php
        $products = App\Models\Product::orderBy('created_at', 'asc')->get();
        $count = 0;
    @endphp

    <!-- Table rows with data -->
    @forelse($products as $product)
        @php
            $count++;
            $brand = App\Models\Probrand::find($product->pro_brand);
            $brandName = $brand ? $brand->brand_name : $product->pro_brand;
        @endphp
        <tr>
            <td>{{ $count }}</td>
            <td>{{ $product->pro_title }}</td>
            <td>
                {{ $product->category->proCat_name ?? '' }} 
                @if($product->subCategory)
                    <br><small>({{ $product->subCategory->proSubCat_name }})</small>
                @endif
            </td>
            <td>{{ $product->pro_model }}</td>
            <td>{{ $brandName }}</td>
            <td>{{ $product->pro_price }}</td>
            <td>{{ $product->pro_sprice }}</td>
            <td><img src="{{ asset('uploads/'. $product->pro_img1) }}" alt="product_Image" width="50" loading="lazy"  class="lazy-image" ></td>
            <td>{{ $product->pro_qty }}</td>
            
            <td>
               <div class="d-flex">
                 <a href="{{ route('edit.product', $product->id) }}" class="btn btn-primary btn-sm mr-2">
                     <i class="fa fa-edit"></i>
                 </a> 
            
                 <form method="post" action="{{ route('destroy.product') }}">
                   @csrf
                   <input type="hidden" name="id" value="{{ $product->id }}">
                   <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i></button>
                 </form>  
               </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="10" class="text-center text-muted py-4">No data found in this table.</td>
          </tr>
          @endforelse
        <!-- Add more rows as needed -->
      </tbody>
    </table> 
    </div>
    </div>
    
  </div>
 

  <!-- Add Bootstrap JS and jQuery scripts (optional but may be required for certain features) -->
 

@section('modal')
<!-- Bulk Upload Modal -->
<div class="modal fade" id="bulkUploadModal" tabindex="-1" role="dialog" aria-labelledby="bulkUploadModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="bulkUploadModalLabel">Bulk Product Upload</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('product.bulk.upload') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="modal-body">
            <p>1. Download the demo CSV file to understand the format.</p>
            <a href="{{ route('product.demo.csv') }}" class="btn btn-info btn-sm mb-3"><i class="fa fa-download"></i> Download Demo CSV</a>
            
            <p>2. Upload your filled CSV file.</p>
            <div class="form-group">
                <label for="csv_file">Choose CSV File</label>
                <input type="file" class="form-control-file" id="csv_file" name="csv_file" accept=".csv" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Upload Products</button>
          </div>
      </form>
    </div>
  </div>
</div>
@endsection

@endsection

@section('script')
    <script>
        document.getElementById('searchInput').addEventListener('keyup', function() {
            var input = document.getElementById('searchInput');
            var filter = input.value.toLowerCase();
            var table = document.getElementById('productTable');
            var tr = table.getElementsByTagName('tr');
            var hasVisibleRows = false;

            for (var i = 1; i < tr.length; i++) {
                if (tr[i].classList.contains('no-data-row')) continue;
                // Search in Title (1), Category (2), Model (3), Brand (4) columns
                var tdTitle = tr[i].getElementsByTagName('td')[1];
                var tdCategory = tr[i].getElementsByTagName('td')[2];
                var tdModel = tr[i].getElementsByTagName('td')[3];
                var tdBrand = tr[i].getElementsByTagName('td')[4];
                
                if (tdTitle || tdCategory || tdModel || tdBrand) {
                    var txtTitle = tdTitle ? tdTitle.textContent || tdTitle.innerText : "";
                    var txtCategory = tdCategory ? tdCategory.textContent || tdCategory.innerText : "";
                    var txtModel = tdModel ? tdModel.textContent || tdModel.innerText : "";
                    var txtBrand = tdBrand ? tdBrand.textContent || tdBrand.innerText : "";
                    
                    if (txtTitle.toLowerCase().indexOf(filter) > -1 || 
                        txtCategory.toLowerCase().indexOf(filter) > -1 ||
                        txtModel.toLowerCase().indexOf(filter) > -1 ||
                        txtBrand.toLowerCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                        hasVisibleRows = true;
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }

            // Handle "No data found" row
            let noDataRow = table.querySelector('.no-data-row');
            if (!hasVisibleRows) {
                if (!noDataRow) {
                    const tbody = table.querySelector('tbody');
                    if (tbody) {
                        const colCount = tr.length > 1 ? tr[1].cells.length : 10;
                        const newTr = document.createElement('tr');
                        newTr.className = 'no-data-row';
                        newTr.innerHTML = `<td colspan="${colCount}" class="text-center text-muted py-4">No data found</td>`;
                        tbody.appendChild(newTr);
                    }
                } else {
                    noDataRow.style.display = '';
                }
            } else if (noDataRow) {
                noDataRow.style.display = 'none';
            }
        });
    </script>
@endsection