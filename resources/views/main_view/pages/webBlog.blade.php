<!DOCTYPE html>
<html lang="zxx">

<head>
    <!-- metas -->
    <meta charset="utf-8">
    <meta name="author" content="themepaa">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="keywords" content="Sajal">
    <meta name="description" content="Sajal">
    @php
    $proName = App\Models\Profile::first();
    @endphp

    <!-- title -->
    <title>
        {{ $proName->name }} - Blog
    </title>
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
    @include('main_view.include.css')
    <style>
        .blog-grid-item {
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            height: 100%;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        .blog-grid-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }
        .blog-grid-img {
            height: 200px;
            overflow: hidden;
        }
        .blog-grid-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: all 0.5s ease;
        }
        .blog-grid-item:hover .blog-grid-img img {
            transform: scale(1.1);
        }
        .blog-gird-info {
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        .blog-gird-info h5 {
            margin-top: 10px;
            margin-bottom: 15px;
            font-size: 18px;
            line-height: 1.4;
        }
        .blog-gird-info h5 a {
            color: #333;
            text-decoration: none;
        }
        .blog-gird-info h5 a:hover {
            color: var(--primary-color);
        }
        .b-meta {
            font-size: 13px;
            color: #777;
            margin-bottom: 5px;
        }
        .btn-grid {
            margin-top: auto;
        }
        .m-btn-link {
            color: var(--primary-color);
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
        }
        .m-btn-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<!-- Body Start -->

<body data-spy="scroll" data-target="#navbar-collapse-toggle" data-offset="70">
   
    @include('main_view.include.header')
    
    <!-- Main -->
    <main>
        <section class="home-banner-01">
            <div class="position-relative">
                <img src="https://template.canva.com/EAENvp21inc/1/0/1600w-qt_TMRJF4m0.jpg" class="lazy-image d-block w-100" style="height: 300px; object-fit: cover;" alt="Blog Banner" loading="lazy" >
                <div class="position-absolute top-50 start-50 translate-middle text-center">
                    <h1 class="text-white display-4 font-weight-bold">Our Magazine</h1>
                </div>
            </div>
        </section>

        <section id="blog" class="section">
            <div class="container">
                <div class="row pt-5">
                    <div class="col-lg-3 col-md-4 mb-4">
                        <!-- Search and Category Sidebar -->
                        <div class="sidebar p-4 bg-light rounded">
                            <h4 class="mb-3">Search</h4>
                            <form method="GET" action="{{route('sort.category')}}">
                                <div class="form-group mb-3">
                                    <select class="form-control" id="blog_category" name="blog_category" required>
                                        <option value="">All Categories</option>
                                        @foreach($cats as $category)
                                            @php
                                                $blogCount = App\Models\Blog::where('blog_cat', $category->cat_name)->count();
                                            @endphp
                                            @if($blogCount > 0)
                                                <option value="{{ $category->cat_name }}">{{ $category->cat_name }} ({{$blogCount}})</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary btn-block w-100">Filter</button>
                            </form>

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

                    <div class="col-lg-9 col-md-8">
                        <div class="row">
                            @if($blogs->count() > 0)
                                @foreach($blogs as $blog)
                                    @php
                                        // Always link to internal details page
                                        $link = route('web.blog.details', $blog->id);
                                        $target = '_self';
                                    @endphp
                                    <div class="col-lg-4 col-md-6 mb-4">
                                        <div class="blog-grid-item">
                                            <div class="blog-grid-img">
                                                <a href="{{ $link }}" target="{{ $target }}">
                                                    <img src="{{ asset('uploads/'. $blog->blog_image) }}" alt="{{ $blog->blog_title }}" loading="lazy"  class="lazy-image" >
                                                </a>
                                            </div>
                                            <div class="blog-gird-info">
                                                <div class="b-meta">
                                                    <span class="date"><i class="far fa-calendar-alt text-primary mr-1"></i> {{ date('d M Y', strtotime($blog->created_at)) }}</span>
                                                    @if($blog->blog_Cat)
                                                    <span class="meta-sep">|</span>
                                                    <span class="category">{{ $blog->blog_Cat }}</span>
                                                    @endif
                                                </div>
                                                <h5>
                                                    <a href="{{ $link }}" target="{{ $target }}">{{ Str::limit($blog->blog_title, 50) }}</a>
                                                </h5>
                                                <div class="btn-grid">
                                                    <a class="m-btn-link" href="{{ $link }}" target="{{ $target }}">Read More <i class="fas fa-arrow-right ml-1"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div> 
                                @endforeach
                            @else
                                <div class="col-12 text-center">
                                    <h3 class="text-muted">No Blog Posts Found</h3>
                                </div>
                            @endif
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