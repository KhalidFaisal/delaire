@extends('backend.layout.template')

@section('body-content')
<div class="page-body">
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6">
                    <h3>General Settings</h3>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <h5>Manage Website Settings</h5>
                    </div>
                    <div class="card-body">
                        
                        <div class="accordion" id="settingsAccordion">
                            
                            <!-- Section 1: Offer Settings -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        <strong>Offers (Global Discount)</strong>
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#settingsAccordion">
                                    <div class="accordion-body">
                                        <form action="{{ route('update.settings') }}" method="POST">
                                            @csrf
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="offer_name">Offer Name</label>
                                                    <input class="form-control" id="offer_name" type="text" name="offer_name" value="{{ $setting->offer_name }}" placeholder="e.g. Eid Flash Sale">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="discount_percentage">Offer Amount (Global Discount %)</label>
                                                    <input class="form-control" id="discount_percentage" type="number" step="0.01" name="discount_percentage" value="{{ $setting->discount_percentage }}" required>
                                                    <small class="text-muted">Applied to ALL products based on adjusted price.</small>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="offer_start_date">Offer Start Date</label>
                                                    <input class="form-control" id="offer_start_date" type="datetime-local" name="offer_start_date" value="{{ $setting->offer_start_date ? \Carbon\Carbon::parse($setting->offer_start_date)->format('Y-m-d\TH:i') : '' }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="offer_end_date">Offer Expiration Date</label>
                                                    <input class="form-control" id="offer_end_date" type="datetime-local" name="offer_end_date" value="{{ $setting->offer_end_date ? \Carbon\Carbon::parse($setting->offer_end_date)->format('Y-m-d\TH:i') : '' }}">
                                                </div>
                                            </div>
                                            <div class="mt-3 text-end">
                                                <button class="btn btn-primary" type="submit">Update Offer Settings</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 2: Charges & Price Hikes -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        <strong>Charges & Global Price Hikes</strong>
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#settingsAccordion">
                                    <div class="accordion-body">
                                        <form action="{{ route('update.settings') }}" method="POST">
                                            @csrf
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="delivery_charge">Delivery Charge (Amount)</label>
                                                    <input class="form-control" id="delivery_charge" type="number" step="0.01" name="delivery_charge" value="{{ $setting->delivery_charge }}" required>
                                                    <small class="text-muted">Set to 0 for Free Delivery.</small>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="profit_percentage">Global Profit Increase (%)</label>
                                                    <input class="form-control" id="profit_percentage" type="number" step="0.01" name="profit_percentage" value="{{ $setting->profit_percentage }}" required>
                                                    <small class="text-muted">Increases base price before discount.</small>
                                                </div>
                                            </div>
                                            <!-- Hidden fields to preserve other settings when updating this section -->
                                            <input type="hidden" name="discount_percentage" value="{{ $setting->discount_percentage }}">
                                            <input type="hidden" name="offer_name" value="{{ $setting->offer_name }}">
                                            <input type="hidden" name="offer_start_date" value="{{ $setting->offer_start_date }}">
                                            <input type="hidden" name="offer_end_date" value="{{ $setting->offer_end_date }}">
                                            
                                            <div class="mt-3 text-end">
                                                <button class="btn btn-primary" type="submit">Update Charges</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 3: Promo Codes -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        <strong>Promo Codes</strong>
                                    </button>
                                </h2>
                                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#settingsAccordion">
                                    <div class="accordion-body">
                                        
                                        <!-- Create Promo Code Form -->
                                        <div class="mb-4 p-3 border rounded bg-light">
                                            <h6>Create New Promo Code</h6>
                                            <form action="{{ route('promo.store') }}" method="POST">
                                                @csrf
                                                <div class="row g-3">
                                                    <div class="col-md-3">
                                                        <label class="form-label">Code</label>
                                                        <input class="form-control" type="text" name="code" placeholder="e.g. SAVE50" required>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label">Discount Amount</label>
                                                        <input class="form-control" type="number" step="0.01" name="discount_amount" placeholder="0.00" required>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="form-label">Type</label>
                                                        <select class="form-select" name="discount_type">
                                                            <option value="fixed">Fixed Amount</option>
                                                            <option value="percentage">Percentage</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">Dates (Start - End)</label>
                                                        <div class="input-group">
                                                            <input class="form-control" type="date" name="start_date">
                                                            <span class="input-group-text">to</span>
                                                            <input class="form-control" type="date" name="end_date">
                                                        </div>
                                                    </div>
                                                    <div class="col-12 text-end">
                                                        <button class="btn btn-success btn-sm" type="submit"><i class="fa fa-plus"></i> Create Code</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>

                                        <!-- Promo Codes List -->
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Code</th>
                                                        <th>Discount</th>
                                                        <th>Dates</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($promoCodes as $promo)
                                                    <tr>
                                                        <td><strong>{{ $promo->code }}</strong></td>
                                                        <td>
                                                            {{ $promo->discount_amount }} 
                                                            {{ $promo->discount_type == 'percentage' ? '%' : 'Tk' }}
                                                        </td>
                                                        <td>
                                                            <small>
                                                                {{ $promo->start_date ? $promo->start_date->format('d M Y') : 'N/A' }} - 
                                                                {{ $promo->end_date ? $promo->end_date->format('d M Y') : 'N/A' }}
                                                            </small>
                                                        </td>
                                                        <td>
                                                            @if($promo->status)
                                                                <span class="badge bg-success">Active</span>
                                                            @else
                                                                <span class="badge bg-danger">Inactive</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('promo.status', $promo->id) }}" class="btn btn-xs {{ $promo->status ? 'btn-warning' : 'btn-success' }}">
                                                                {{ $promo->status ? 'Deactivate' : 'Activate' }}
                                                            </a>
                                                            <a href="{{ route('promo.delete', $promo->id) }}" class="btn btn-xs btn-danger" onclick="return confirm('Are you sure?')">
                                                                <i class="fa fa-trash"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center text-muted">No promo codes created yet.</td>
                                                    </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div> <!-- End Accordion -->

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Function to get query parameter
        function getQueryParam(param) {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(param);
        }

        const section = getQueryParam('section');
        
        if (section) {
            // Close all accordions first
            const allCollapses = document.querySelectorAll('.accordion-collapse');
            allCollapses.forEach(el => el.classList.remove('show'));
            
            const allButtons = document.querySelectorAll('.accordion-button');
            allButtons.forEach(btn => {
                btn.classList.add('collapsed');
                btn.setAttribute('aria-expanded', 'false');
            });

            // Determine which ID to open
            let targetId = '';
            let btnId = '';
            
            if (section === 'offers') {
                targetId = 'collapseOne';
                btnId = 'headingOne';
            } else if (section === 'charges') {
                targetId = 'collapseTwo';
                btnId = 'headingTwo';
            } else if (section === 'promocodes') {
                targetId = 'collapseThree';
                btnId = 'headingThree';
            }

            // Open the target accordion
            if (targetId) {
                const targetCollapse = document.getElementById(targetId);
                const targetBtn = document.querySelector(`#${btnId} button`);
                
                if (targetCollapse && targetBtn) {
                    targetCollapse.classList.add('show');
                    targetBtn.classList.remove('collapsed');
                    targetBtn.setAttribute('aria-expanded', 'true');
                }
            }
        }
    });
</script>
@endsection
