@extends('backend.layout.template')
@section('title')
    Manage Testimonials
@endsection
@section('body-content')
<div class="container card">
    <div class="content-container p-4">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <h3 class="text-center">Manage Testimonials</h3><br>
        <div class="row" style="margin-bottom:20px;">
            <div class="col-md-3">
                <a href="{{ route('create.testimonial') }}" class="col-md-12 btn btn-primary"><i class="fa fa-plus"></i> Add Testimonial</a>
            </div>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-striped" id="testimonialTable">
                <thead>
                    <tr>
                        <th>SN</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Designation</th>
                        <th>Message</th>
                        <th>Rating</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $count = 0;
                    @endphp
                    @foreach($testimonials as $testimonial)
                        @php $count++; @endphp
                        <tr>
                            <td>{{ $count }}</td>
                            <td>
                                @if($testimonial->image)
                                    <img src="{{ asset($testimonial->image) }}" alt="image" width="50" loading="lazy"  class="lazy-image" >
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>{{ $testimonial->name }}</td>
                            <td>{{ $testimonial->designation }}</td>
                            <td>{{ Str::limit($testimonial->message, 50) }}</td>
                            <td>{{ $testimonial->rating }}</td>
                            <td>
                                <div class="d-flex">
                                    <a href="{{ route('edit.testimonial', $testimonial->id) }}" class="btn btn-primary btn-sm mr-2">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form method="post" action="{{ route('destroy.testimonial', $testimonial->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
