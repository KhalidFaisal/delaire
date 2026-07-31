@extends('backend.layout.template')

@section('title')
    Manage Login Settings
@endsection

@section('body-content')
<div class="container card">
    <div class="content-container p-4">
        <h3 class="text-center mb-4">Manage Login Settings</h3>

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
                <h5 class="mb-1">Customer Checkout & Authentication</h5>
                <p class="text-muted small mb-0">Configure whether customers are forced to sign in/register before completing a purchase.</p>
            </div>
            <div class="card-body">
                <form action="{{ route('update.login') }}" method="POST">
                    @csrf
                    
                    <!-- Login Requirement Setting Toggle -->
                    <div class="p-4 border rounded mb-4 {{ $setting->require_login ? 'bg-light border-primary-subtle' : 'bg-light border-secondary-subtle' }} transition-all">
                        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
                            <div>
                                <h6 class="mb-1 fw-bold">
                                    <i class="fa fa-sign-in me-2 text-primary"></i> Require Login to Purchase
                                </h6>
                                <p class="text-muted small mb-0 mt-1">
                                    <strong>ON (Enabled):</strong> Customers must sign in or register an account before proceeding to checkout (Current Default).<br>
                                    <strong>OFF (Disabled):</strong> Customers can click checkout and purchase instantly as a guest. The system will auto-create a user account for them using their checkout details.
                                </p>
                            </div>
                            <div class="form-check form-switch form-switch-lg mb-0 mt-2 mt-md-0">
                                <input class="form-check-input" type="checkbox" id="require_login" name="require_login" style="width: 3.5em; height: 1.75em; cursor: pointer;" {{ $setting->require_login ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden fields to preserve other settings in general_settings table -->
                    <input type="hidden" name="delivery_charge" value="{{ $setting->delivery_charge }}">
                    <input type="hidden" name="profit_percentage" value="{{ $setting->profit_percentage }}">
                    <input type="hidden" name="discount_percentage" value="{{ $setting->discount_percentage }}">
                    <input type="hidden" name="offer_name" value="{{ $setting->offer_name }}">
                    <input type="hidden" name="offer_start_date" value="{{ $setting->offer_start_date }}">
                    <input type="hidden" name="offer_end_date" value="{{ $setting->offer_end_date }}">
                    <input type="hidden" name="admin_notification_email" value="{{ $setting->admin_notification_email }}">

                    <div class="text-center mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-primary px-5 py-2 fw-bold">
                            <i class="fa fa-save me-2"></i> Save Settings
                        </button>
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
    .transition-all {
        transition: all 0.2s ease;
    }
    .gap-3 {
        gap: 1rem !important;
    }
</style>
@endsection
