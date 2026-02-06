<!doctype html>
<html lang="en" class="no-js">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
    <title>Contact Us - {{ $portfolio->company_name ?? 'Pinkush' }}</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Contact {{ $portfolio->company_name ?? 'Pinkush' }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ isset($portfolio->favicon) ? asset($portfolio->favicon) : asset('main_view/assets/img/favicon.png')}}" type="image/x-icon">
    @include('main_view.include.css')
    
    <style>

        /* Example Usage: Override existing styles if necessary or use the variables */
        .text-primary { color: var(--primary-color) !important; }
        .bg-primary { background-color: var(--primary-color) !important; }
        .btn-primary { background-color: var(--primary-color) !important; border-color: var(--primary-color) !important; }
        .footer-style-2 .footer-top .footer-widget .footer-widget-inner .footer-social-wrapper .footer-social .footer-social-item a svg { fill: var(--primary-color); }
        .header-action-item svg { fill: var(--secondary-color); }
    </style>
</head>

<body>
    <div class="body-wrapper">
        @include('main_view.include.header')

        <main id="MainContent" class="content-for-layout">
           

            <div class="contact-area py-5">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6 col-md-12 col-12 mb-4 mb-lg-0">
                            <div class="contact-info-wrapper">
                                <h2 class="section-title mb-4">Get In Touch</h2>
                                <p class="mb-4">We'd love to hear from you. Whether you have a question about our products, pricing, or anything else, our team is ready to answer all your questions.</p>
                                
                                <div class="contact-item d-flex align-items-center mb-4">
                                    <div class="contact-icon me-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-map-pin"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                    </div>
                                    <div class="contact-text">
                                        <h4 class="mb-1">Our Address</h4>
                                        <p class="mb-0 text-muted">{{ $portfolio->address ?? '123 Dummy Street, Design City, DC 12345' }}</p>
                                    </div>
                                </div>

                                <div class="contact-item d-flex align-items-center mb-4">
                                    <div class="contact-icon me-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                    </div>
                                    <div class="contact-text">
                                        <h4 class="mb-1">Phone Number</h4>
                                        <p class="mb-0 text-muted">{{ $portfolio->contact_number ?? '+1 (555) 123-4567' }}</p>
                                    </div>
                                </div>

                                <div class="contact-item d-flex align-items-center">
                                    <div class="contact-icon me-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                                    </div>
                                    <div class="contact-text">
                                        <h4 class="mb-1">Email Address</h4>
                                        <p class="mb-0 text-muted">{{ $portfolio->email ?? 'info@pinkush.com' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-12 col-12">
                            <div class="contact-form-wrapper bg-light p-4 rounded">
                                <h3 class="mb-4">Send Us a Message</h3>

                                @if(session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                <form action="{{ route('contact.submit') }}" method="POST" class="contact-form">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-6 col-12 mb-3">
                                            <div class="form-group">
                                                <label for="name" class="mb-2">Your Name</label>
                                                <input type="text" class="form-control" id="name" placeholder="Enter your name">
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-12 mb-3">
                                            <div class="form-group">
                                                <label for="email" class="mb-2">Your Email</label>
                                                <input type="email" class="form-control" id="email" placeholder="Enter your email">
                                            </div>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <div class="form-group">
                                                <label for="subject" class="mb-2">Subject</label>
                                                <input type="text" class="form-control" id="subject" placeholder="Enter subject">
                                            </div>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <div class="form-group">
                                                <label for="message" class="mb-2">Message</label>
                                                <textarea class="form-control" id="message" rows="5" placeholder="Enter your message"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary w-100">Send Message</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-5">
                        <div class="col-12">
                             <div class="map-wrapper rounded overflow-hidden">
                                <iframe src="{{ $portfolio->map_url ?? 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3022.1839486940656!2d-73.98773192404069!3d40.7484405713885!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c259a9b3117469%3A0xd134e199a405a163!2sEmpire%20State%20Building!5e0!3m2!1sen!2sbd!4v1709462837289!5m2!1sen!2sbd' }}" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                             </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        @include('main_view.include.footer')
        @include('main_view.include.drawermenu')
        @include('main_view.include.drawercart')
        @include('main_view.include.script')
        <script src="{{asset('main_view/assets/js/main.js')}}"></script>
        <script src="{{asset('main_view/assets/js/vendor.js')}}"></script>
    </div>
</body>
</html>
