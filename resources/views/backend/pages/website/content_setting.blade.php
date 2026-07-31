@extends('backend.layout.template')

@section('title')
    Content Settings
@endsection

@section('body-content')
<div class="container card">
    <div class="content-container p-4">
        <h3 class="text-center mb-4">Content Settings</h3>

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

        <div class="card border-0 shadow-sm mt-3">
            <div class="card-header bg-white pb-0 pt-2 border-0">
                <h5 class="mb-1">Manage Website Features</h5>
                <p class="text-muted small mb-0">Toggle the visibility of front store components. Changes are applied instantly.</p>
            </div>
            <div class="card-body">
                <form action="{{ route('manage.content.setting.update') }}" method="POST">
                    @csrf
                    
                    <!-- Critical Setting -->
                    <div class="p-3 border rounded mb-4 {{ $setting->website_shutdown ? 'bg-danger-light border-danger' : 'bg-light' }} transition-all">
                        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
                            <div>
                                <h6 class="mb-1 {{ $setting->website_shutdown ? 'text-danger fw-bold' : 'fw-bold' }}">
                                    <i class="fa fa-power-off me-2"></i> Website Shutdown
                                </h6>
                                <small class="text-muted">If turned ON, the front store will display a maintenance page. The admin panel remains accessible.</small>
                            </div>
                            <div class="form-check form-switch form-switch-lg mb-0 mt-2 mt-md-0">
                                <input class="form-check-input" type="checkbox" id="website_shutdown" name="website_shutdown" style="width: 3em; height: 1.5em;" {{ $setting->website_shutdown ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>
                    
                    <h6 class="text-uppercase text-muted fw-bold mb-3 mt-4" style="font-size: 0.8rem; letter-spacing: 1px;">Section Visibility</h6>

                    <!-- Slider Section -->
                    <div class="p-3 border rounded mb-3 bg-white transition-all hover-shadow">
                        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
                            <div>
                                <h6 class="mb-1 fw-bold"><i class="fa fa-picture-o me-2 text-primary"></i> Slider Section</h6>
                                <small class="text-muted">Show or hide the main image slider on the homepage.</small>
                            </div>
                            <div class="form-check form-switch form-switch-lg mb-0 mt-2 mt-md-0">
                                <input class="form-check-input" type="checkbox" id="slider_active" name="slider_active" style="width: 3em; height: 1.5em; cursor: pointer;" {{ $setting->slider_active ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>

                    <!-- Feature Category Section -->
                    <div class="p-3 border rounded mb-3 bg-white transition-all hover-shadow">
                        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
                            <div>
                                <h6 class="mb-1 fw-bold"><i class="fa fa-th-large me-2 text-success"></i> Feature Category</h6>
                                <small class="text-muted">Show or hide the feature categories grid below the slider.</small>
                            </div>
                            <div class="form-check form-switch form-switch-lg mb-0 mt-2 mt-md-0">
                                <input class="form-check-input" type="checkbox" id="feature_content_active" name="feature_content_active" style="width: 3em; height: 1.5em; cursor: pointer;" {{ $setting->feature_content_active ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>

                    <!-- Testimonials Section -->
                    <div class="p-3 border rounded mb-3 bg-white transition-all hover-shadow">
                        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
                            <div>
                                <h6 class="mb-1 fw-bold"><i class="fa fa-comments-o me-2 text-warning"></i> User Testimonials</h6>
                                <small class="text-muted">Show or hide the customer reviews section.</small>
                            </div>
                            <div class="form-check form-switch form-switch-lg mb-0 mt-2 mt-md-0">
                                <input class="form-check-input" type="checkbox" id="testimonial_active" name="testimonial_active" style="width: 3em; height: 1.5em; cursor: pointer;" {{ $setting->testimonial_active ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>

                    <!-- Banner Section -->
                    <div class="p-3 border rounded mb-4 bg-white transition-all hover-shadow">
                        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
                            <div>
                                <h6 class="mb-1 fw-bold"><i class="fa fa-bullhorn me-2 text-info"></i> Single Banner</h6>
                                <small class="text-muted">Show or hide the promotional banner above testimonials.</small>
                            </div>
                            <div class="form-check form-switch form-switch-lg mb-0 mt-2 mt-md-0">
                                <input class="form-check-input" type="checkbox" id="banner_active" name="banner_active" style="width: 3em; height: 1.5em; cursor: pointer;" {{ $setting->banner_active ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-primary px-5 py-2 fw-bold"><i class="fa fa-save me-2"></i> Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .form-switch-lg .form-check-input {
        cursor: pointer;
        transition: background-color 0.2s ease-in-out, border-color 0.2s ease-in-out;
    }
    .form-switch-lg .form-check-input:checked {
        background-color: #198754;
        border-color: #198754;
    }
    #website_shutdown:checked {
        background-color: #dc3545 !important;
        border-color: #dc3545 !important;
    }
    .bg-danger-light {
        background-color: #fce8e8 !important;
    }
    .transition-all {
        transition: all 0.2s ease;
    }
    .hover-shadow:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        border-color: #dee2e6 !important;
    }
    .gap-3 {
        gap: 1rem !important;
    }
</style>
@endsection
