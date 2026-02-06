    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&amp;display=swap"
        rel="stylesheet">
        
    <style>
        :root {
            /* Dynamic Brand Colors */
            --primary-color: {{ $portfolio->brand_color_1 ?? '#de2e79' }};
            --secondary-color: {{ $portfolio->brand_color_2 ?? '#000000' }};
            --accent-color: {{ $portfolio->brand_color_3 ?? '#ffffff' }};

            /* Derived Bootstrap/Component Variables */
            --bs-primary: var(--primary-color);
            --bs-secondary: var(--secondary-color);

            --btn-primary-border-radius: 0.25rem;
            --btn-primary-color: #fff;
            --btn-primary-background-color: var(--primary-color);
            --btn-primary-border-color: var(--primary-color);
            --btn-primary-hover-color: #fff;
            --btn-primary-background-hover-color: var(--secondary-color);
            --btn-primary-border-hover-color: var(--secondary-color);
            --btn-primary-font-weight: 500;

            --btn-secondary-border-radius: 0.25rem;
            --btn-secondary-color: var(--primary-color);
            --btn-secondary-background-color: transparent;
            --btn-secondary-border-color: var(--primary-color);
            --btn-secondary-hover-color: #fff;
            --btn-secondary-background-hover-color: var(--primary-color);
            --btn-secondary-border-hover-color: var(--primary-color);
            --btn-secondary-font-weight: 500;

            --heading-color: #000;
            --heading-font-family: 'Poppins', sans-serif;
            --heading-font-weight: 700;

            --title-color: #000;
            --title-font-family: 'Poppins', sans-serif;
            --title-font-weight: 400;

            --body-color: #000;
            --body-background-color: #fff;
            --body-font-family: 'Poppins', sans-serif;
            --body-font-size: 14px;
            --body-font-weight: 400;

            --section-heading-color: #000;
            --section-heading-font-family: 'Poppins', sans-serif;
            --section-heading-font-size: 48px;
            --section-heading-font-weight: 600;

            --section-subheading-color: #000;
            --section-subheading-font-family: 'Poppins', sans-serif;
            --section-subheading-font-size: 16px;
            --section-subheading-font-weight: 400;
        }

        /* Utility Classes forcing Brand Colors */
        .text-primary, .primary-color { color: var(--primary-color) !important; }
        .text-secondary, .secondary-color { color: var(--secondary-color) !important; }
        .bg-primary { background-color: var(--primary-color) !important; }
        .bg-secondary { background-color: var(--secondary-color) !important; }
        
        /* Button Overrides */
        .btn-primary { 
            background-color: var(--primary-color) !important; 
            border-color: var(--primary-color) !important; 
            color: #fff !important;
        }
        .btn-primary:hover {
            background-color: var(--secondary-color) !important;
            border-color: var(--secondary-color) !important;
        }

        /* Common Elements Override */
        .star-rating, .testimonial-icon-quote svg path, .wishlist-btn.active svg { 
            fill: var(--primary-color); 
            color: var(--primary-color); 
            stroke: var(--primary-color);
        }
    </style>

    <link href="{{asset('main_view/assets/css/vendor.css')}} " rel="stylesheet">
    <link href="{{asset('main_view/assets/css/style.css')}} " rel="stylesheet">