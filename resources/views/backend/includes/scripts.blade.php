

    <!-- latest jquery-->
    <script src="{{ asset('backend/assets/js/jquery-3.5.1.min.js') }}"></script>
    <!-- feather icon js-->
    <script src="{{ asset('backend/assets/js/icons/feather-icon/feather.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/icons/feather-icon/feather-icon.js') }}"></script>
    <!-- Sidebar jquery-->
    <script src="{{ asset('backend/assets/js/sidebar-menu.js') }}"></script>
    <script src="{{ asset('backend/assets/js/config.js') }}"></script>
    <!-- Bootstrap js-->
    <script src="{{ asset('backend/assets/js/bootstrap/popper.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/bootstrap/bootstrap.min.js') }}"></script>
    <!-- Plugins JS start-->
    {{-- <script src="{{ asset('backend/assets/js/chart/chartist/chartist.js') }}"></script>
    <script src="{{ asset('backend/assets/js/chart/chartist/chartist-plugin-tooltip.js') }}"></script>
    <script src="{{ asset('backend/assets/js/chart/knob/knob.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/chart/knob/knob-chart.js') }}"></script>
    <script src="{{ asset('backend/assets/js/chart/apex-chart/apex-chart.js') }}"></script>
    <script src="{{ asset('backend/assets/js/chart/apex-chart/stock-prices.js') }}"></script> --}}

    <script src="{{ asset('backend/assets/js/prism/prism.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/clipboard/clipboard.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/counter/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/counter/jquery.counterup.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/counter/counter-custom.js') }}"></script>
    <script src="{{ asset('backend/assets/js/custom-card/custom-card.js') }}"></script>
    {{-- <script src="{{ asset('backend/assets/js/notify/bootstrap-notify.min.js') }}"></script>
     --}}
    <script src="{{ asset('backend/assets/js/vector-map/jquery-jvectormap-2.0.2.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/vector-map/map/jquery-jvectormap-world-mill-en.js') }}"></script>
    <script src="{{ asset('backend/assets/js/vector-map/map/jquery-jvectormap-us-aea-en.js') }}"></script>
    <script src="{{ asset('backend/assets/js/vector-map/map/jquery-jvectormap-uk-mill-en.js') }}"></script>
    <script src="{{ asset('backend/assets/js/vector-map/map/jquery-jvectormap-au-mill.js') }}"></script>
    <script src="{{ asset('backend/assets/js/vector-map/map/jquery-jvectormap-chicago-mill-en.js') }}"></script>
    <script src="{{ asset('backend/assets/js/vector-map/map/jquery-jvectormap-in-mill.js') }}"></script>
    <script src="{{ asset('backend/assets/js/vector-map/map/jquery-jvectormap-asia-mill.js') }}"></script>
    <script src="{{ asset('backend/assets/js/dashboard/default.js') }}"></script>
    {{-- <script src="{{ asset('backend/assets/js/notify/index.js') }}"></script>
     --}}

     {{-- <script src="{{ asset('backend/assets/js/notify/notify.js') }}"></script> --}}
<script src="{{ asset('backend/assets/js/notify/bootstrap-notify.js') }}"></script>
{{-- <script src="{{ asset('backend/assets/js/notify/bootstrap-notify.min.js') }}"></script> --}}
{{-- <script src="{{ asset('backend/assets/js/notify/notify-script.js') }}"></script> --}}
<script src="{{ asset('backend/assets/js/tooltip-init.js') }}"></script>

    <script src="{{ asset('backend/assets/js/datepicker/date-picker/datepicker.js') }}"></script>
    <script src="{{ asset('backend/assets/js/datepicker/date-picker/datepicker.en.js') }}"></script>
    <script src="{{ asset('backend/assets/js/datepicker/date-picker/datepicker.custom.js') }}"></script>
    <!-- Plugins JS Ends-->
    <!-- Theme js-->
    <script src="{{ asset('backend/assets/js/script.js') }}"></script>
    {{-- <script src="{{ asset('backend/assets/js/theme-customizer/customizer.js') }}"></script> --}}
    <!-- login js-->
    <!-- Plugin used-->
    <script src="{{ asset('backend/assets/js/admin_notifications.js') }}"></script>

    @if (isset($successMessage))
    <script>
        $(document).ready(function() {
            $.notify({
                // options
                message: '{{ $successMessage }}'
            },{
                // settings
                type: 'success'
            });
        });
    </script>
    @endif


        @yield('script')
        <script>
  function togglePasswordVisibility() {
    var passwordInput = document.querySelector('input[name="password"]');
    var toggleButton = document.querySelector('.toggle');

    if (passwordInput.type === 'password') {
      passwordInput.type = 'text';
      toggleButton.innerHTML = '<i class="fa fa-eye-slash" aria-hidden="true"></i>';
    } else {
      passwordInput.type = 'password';
      toggleButton.innerHTML = '<i class="fa fa-eye" aria-hidden="true"></i>';
    }
  }
</script>





  <script>
    // JavaScript for search functionality
    document.addEventListener('DOMContentLoaded', function() {
      const searchInput = document.getElementById('searchInput');
      const tableRows = document.querySelectorAll('tbody tr');

      if (searchInput) {
          searchInput.addEventListener('input', function() {
            const searchTerm = searchInput.value.toLowerCase();
            let hasVisibleRows = false;

            tableRows.forEach(row => {
              if (row.classList.contains('no-data-row')) return;
              
              const title = row.querySelector('td:nth-child(3)')?.textContent.toLowerCase() || '';
              if (title.includes(searchTerm)) {
                row.style.display = '';
                hasVisibleRows = true;
              } else {
                row.style.display = 'none';
              }
            });

            // Handle "No data found" row
            let noDataRow = document.querySelector('.no-data-row');
            if (!hasVisibleRows) {
                if (!noDataRow) {
                    const tbody = document.querySelector('tbody');
                    if (tbody) {
                        const colCount = tableRows.length > 0 ? tableRows[0].cells.length : 10;
                        const tr = document.createElement('tr');
                        tr.className = 'no-data-row';
                        tr.innerHTML = `<td colspan="${colCount}" class="text-center text-muted py-4">No data found in this table.</td>`;
                        tbody.appendChild(tr);
                    }
                } else {
                    noDataRow.style.display = '';
                }
            } else if (noDataRow) {
                noDataRow.style.display = 'none';
            }
          });
      }
    });
  </script>

<!-- Cropper JS and Logic -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<!-- Global Cropper Modal -->
<div class="modal fade" id="cropperModal" tabindex="-1" role="dialog" aria-labelledby="cropperModalLabel" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="cropperModalLabel">Crop Image</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="$('#cropperModal').modal('hide')">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="img-container" style="max-height: 500px;">
          <img id="cropperImage" src="" style="max-width: 100%; display: block;">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="$('#cropperModal').modal('hide')">Cancel</button>
        <button type="button" class="btn btn-primary" id="cropBtn">Crop & Save</button>
      </div>
    </div>
  </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let cropper;
        let originalImageInput;

        document.body.addEventListener('change', function (e) {
            if (e.target && e.target.tagName === 'INPUT' && e.target.type === 'file' && e.target.dataset.crop === "true") {
                const files = e.target.files;
                if (!files || files.length === 0) return;

                const file = files[0];
                if (!file.type.match(/^image\//)) return;

                originalImageInput = e.target;
                const ratioStr = originalImageInput.dataset.cropRatio;
                let ratio = NaN;
                if (ratioStr) {
                    if (ratioStr.includes('/')) {
                        const parts = ratioStr.split('/');
                        ratio = parseFloat(parts[0]) / parseFloat(parts[1]);
                    } else {
                        ratio = parseFloat(ratioStr);
                    }
                }

                const reader = new FileReader();
                reader.onload = function (event) {
                    const image = document.getElementById('cropperImage');
                    image.src = event.target.result;
                    
                    $('#cropperModal').modal('show');
                    
                    $('#cropperModal').one('shown.bs.modal', function () {
                        if (cropper) { cropper.destroy(); }
                        cropper = new Cropper(image, {
                            aspectRatio: ratio,
                            viewMode: 1,
                            autoCropArea: 1,
                            responsive: true,
                        });
                    }).one('hidden.bs.modal', function () {
                        if (cropper) { cropper.destroy(); cropper = null; }
                    });
                };
                reader.readAsDataURL(file);
            }
        });

        document.getElementById('cropBtn').addEventListener('click', function () {
            if (!cropper || !originalImageInput) return;
            
            // Determine correct MIME type from original input to preserve transparency for PNGs/WebP
            let originalNameParts = originalImageInput.files[0].name.split('.');
            let ext = originalNameParts.length > 1 ? originalNameParts.pop().toLowerCase() : 'jpg';
            if (ext !== 'png' && ext !== 'webp') ext = 'jpg';
            let mimeType = ext === 'png' ? 'image/png' : (ext === 'webp' ? 'image/webp' : 'image/jpeg');

            // Get original natural crop to not degrade the quality down to tiny modal preview size.
            cropper.getCroppedCanvas({
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high',
            }).toBlob(function (blob) {
                const file = new File([blob], originalImageInput.files[0].name, { type: mimeType });
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                originalImageInput.files = dataTransfer.files;
                
                $('#cropperModal').modal('hide');
            }, mimeType, 0.9);
        });
    });
</script>

