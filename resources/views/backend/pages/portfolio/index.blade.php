@extends('backend.layout.template')
@section('title')
    Manage Portfolio
@endsection
@section('body-content')

@php
    //$portfolio is passed from controller
@endphp

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="container card">
    <div class="content-container p-4">
        <h3 class="text-center">Manage Portfolio</h3><br>
        <form action="{{ route('update.portfolio')}}" method="post" enctype="multipart/form-data">
            @csrf
            
            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="company_name">Company Name</label>
                    <input type="text" class="form-control" id="company_name" name="company_name" value="{{ $portfolio->company_name ?? '' }}">
                </div>
                <div class="col-md-6 form-group">
                    <label for="contact_number">Contact Number</label>
                    <input type="text" class="form-control" id="contact_number" name="contact_number" value="{{ $portfolio->contact_number ?? '' }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ $portfolio->email ?? '' }}">
                </div>
                 <div class="col-md-6 form-group">
                    <label for="logo">Logo</label><br>
                    @if(isset($portfolio->logo))
                        <img src="{{ asset($portfolio->logo) }}" alt="Logo" width="100" class="mb-2">
                    @endif
                    <input type="file" class="form-control-file" id="logo" name="logo" accept="image/*">
                </div>
            </div>
             <div class="row">
                 <div class="col-md-6 form-group">
                    <label for="favicon">Favicon</label><br>
                     @if(isset($portfolio->favicon))
                        <img src="{{ asset($portfolio->favicon) }}" alt="Favicon" width="32" class="mb-2">
                    @endif
                    <input type="file" class="form-control-file" id="favicon" name="favicon" accept="image/*">
                </div>
            </div>

            <hr>
            <h4>Brand Colors</h4>
            <div class="row">
                 <div class="col-md-4 form-group">
                    <label for="brand_color_1">Primary Color</label>
                    <input type="color" class="form-control" id="brand_color_1" name="brand_color_1" value="{{ $portfolio->brand_color_1 ?? '#000000' }}">
                </div>
                 <div class="col-md-4 form-group">
                    <label for="brand_color_2">Seccondary Color</label>
                    <input type="color" class="form-control" id="brand_color_2" name="brand_color_2" value="{{ $portfolio->brand_color_2 ?? '#000000' }}">
                </div>
                 <div class="col-md-4 form-group">
                    <label for="brand_color_3">Footer Color</label>
                    <input type="color" class="form-control" id="brand_color_3" name="brand_color_3" value="{{ $portfolio->brand_color_3 ?? '#000000' }}">
                </div>
            </div>

            <hr>
            <h4>Social Media</h4>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="facebook_link">Facebook Link</label>
                    <input type="text" class="form-control" id="facebook_link" name="facebook_link" value="{{ $portfolio->facebook_link ?? '' }}">
                </div>
                 <div class="col-md-6 form-group">
                    <label for="instagram_link">Instagram Link</label>
                    <input type="text" class="form-control" id="instagram_link" name="instagram_link" value="{{ $portfolio->instagram_link ?? '' }}">
                </div>
            </div>
             <div class="row">
                <div class="col-md-6 form-group">
                    <label for="youtube_link">Youtube Link</label>
                    <input type="text" class="form-control" id="youtube_link" name="youtube_link" value="{{ $portfolio->youtube_link ?? '' }}">
                </div>
                 <div class="col-md-6 form-group">
                    <label for="linkedin_link">Linkedin Link</label>
                    <input type="text" class="form-control" id="linkedin_link" name="linkedin_link" value="{{ $portfolio->linkedin_link ?? '' }}">
                </div>
            </div>

            <hr>
            <div class="form-group">
                <label for="address">Address</label>
                <textarea class="form-control" id="address" name="address" rows="3">{{ $portfolio->address ?? '' }}</textarea>
            </div>
             <div class="form-group">
                <label for="map_url">Map URL</label>
                <textarea class="form-control" id="map_url" name="map_url" rows="3">{{ $portfolio->map_url ?? '' }}</textarea>
            </div>

            <div class="form-group">
                <label for="about">About</label>
                <textarea class="form-control" id="about" name="about" rows="5">{{ $portfolio->about ?? '' }}</textarea>
            </div>
             <div class="form-group">
                <label for="terms_condition">Terms & Conditions</label>
                <textarea class="form-control" id="terms_condition" name="terms_condition" rows="5">{{ $portfolio->terms_condition ?? '' }}</textarea>
            </div>
             <div class="form-group">
                <label for="return_policy">Return Policy</label>
                <textarea class="form-control" id="return_policy" name="return_policy" rows="5">{{ $portfolio->return_policy ?? '' }}</textarea>
            </div>


            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>
</div><br>

@endsection

@section('script')
    <script>
        // Optional: Add client-side validation or other scripts if needed
    </script>
@endsection
