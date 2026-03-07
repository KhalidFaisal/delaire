<!DOCTYPE html>
<html lang="zxx">

<head>
    <!-- metas -->
    <meta charset="utf-8">
    <meta name="author" content="About Us">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="{{ $blog->meta_description ?? Str::limit(strip_tags($blog->blog_description), 160) }}">
    <meta name="keywords" content="{{ $blog->meta_keywords ?? '' }}">
    <!-- title -->
    <title>
        {{ $blog->meta_title ?? $blog->blog_title }} - Blog
    </title>
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @include('main_view.include.css')
    <style>
        .blog-details-img img {
            width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .blog-meta {
            color: #777;
            font-size: 14px;
            margin-bottom: 15px;
        }
        .blog-meta i {
            color: var(--primary-color);
            margin-right: 5px;
        }
        .blog-meta span {
            margin-right: 15px;
        }
        .blog-content {
            font-size: 16px;
            line-height: 1.8;
            color: #444;
        }
        .blog-content img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
            margin: 10px 0;
        }
        .recent-posts-widget .media img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 5px;
        }
        .recent-posts-widget .media-body h6 {
            font-size: 15px;
            line-height: 1.4;
            margin-bottom: 5px;
        }
        .recent-posts-widget .media-body h6 a {
            color: #333;
            text-decoration: none;
        }
        .recent-posts-widget .media-body h6 a:hover {
            color: var(--primary-color);
        }
    </style>
</head>
<!-- Body Start -->

<body data-spy="scroll" data-target="#navbar-collapse-toggle" data-offset="70">
   
    @include('main_view.include.header')
     @php
    $proName = App\Models\Profile::first();
    @endphp
    
    <!-- Main -->
    <main>
        <section class="home-banner-01">
            <div class="position-relative">
                <img src="https://template.canva.com/EAENvp21inc/1/0/1600w-qt_TMRJF4m0.jpg" class="lazy-image d-block w-100" style="height: 250px; object-fit: cover; filter: brightness(0.7);" alt="Blog Banner" loading="lazy" >
                <div class="position-absolute top-50 start-50 translate-middle text-center">
                    <h1 class="text-white display-4 font-weight-bold">{{ $blog->blog_title }}</h1>
                    <ul class="breadcrumb justify-content-center bg-transparent p-0 mt-3">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('web.blog') }}" class="text-white">Blog</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Details</li>
                    </ul>
                </div>
            </div>
        </section>

        <section id="blog-details" class="section pt-5 pb-5">
            <div class="container">
                <div class="row">
                    <!-- Blog Content -->
                    <div class="col-lg-8 col-md-12 mb-4">
                        <div class="blog-details-wrapper">
                            <div class="blog-details-img">
                                <img src="{{ asset('uploads/'. $blog->blog_image) }}" alt="{{ $blog->blog_title }}" loading="lazy"  class="lazy-image" >
                            </div>
                            <div class="blog-meta d-flex align-items-center flex-wrap">
                                <span><i class="far fa-calendar-alt"></i> {{ date('d M Y', strtotime($blog->created_at)) }}</span>
                                @if($blog->blog_Cat)
                                <span><i class="far fa-folder"></i> {{ $blog->blog_Cat }}</span>
                                @endif
                                <span><i class="far fa-user"></i> {{ $proName->name }}</span>
                            </div>
                            <h2 class="mb-4">{{ $blog->blog_title }}</h2>
                            
                            <div class="blog-content">
                                {!! $blog->blog_description !!}
                            </div>
                            
                            <hr class="my-5">
                            
                            <!-- Share buttons or tags could go here -->
                            <div class="mt-4">
                                <a href="{{ route('web.blog') }}" class="btn btn-outline-primary"><i class="fas fa-arrow-left mr-2"></i> Back to Blogs</a>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="col-lg-4 col-md-12">
                        <div class="sidebar p-4 bg-light rounded sticky-top" style="top: 100px; z-index: 1;">
                            <h4 class="mb-4">Recent Posts</h4>
                            <div class="recent-posts-widget">
                                @if($recentBlogs->count() > 0)
                                    @foreach($recentBlogs as $recent)
                                        @php
                                            $hasContent = !empty($recent->blog_description);
                                            $rLink = $hasContent ? route('web.blog.details', $recent->id) : ($recent->blog_link ?? '#');
                                            $rTarget = $hasContent ? '_self' : '_blank';
                                        @endphp
                                        <div class="media mb-3 d-flex align-items-center">
                                            <a href="{{ $rLink }}" target="{{ $rTarget }}">
                                                <img src="{{ asset('uploads/'. $recent->blog_image) }}" class="lazy-image mr-3" alt="{{ $recent->blog_title }}" loading="lazy" >
                                            </a>
                                            <div class="media-body ms-3">
                                                <h6><a href="{{ $rLink }}" target="{{ $rTarget }}">{{ Str::limit($recent->blog_title, 40) }}</a></h6>
                                                <small class="text-muted">{{ date('d M Y', strtotime($recent->created_at)) }}</small>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-muted">No recent posts.</p>
                                @endif
                            </div>

                            <h4 class="mt-5 mb-3">Categories</h4>
                            <ul class="list-group list-group-flush bg-transparent">
                                @foreach($cats as $category)
                                    @php
                                        $blogCount = App\Models\Blog::where('blog_cat', $category->cat_name)->count();
                                    @endphp
                                    @if($blogCount > 0)
                                    <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0">
                                        {{ $category->cat_name }}
                                        <span class="badge bg-primary rounded-pill">{{ $blogCount }}</span>
                                    </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    
    @include('main_view.include.footer')
     <!-- all js -->
     @include('main_view.include.script')
    
</body>
</html>
