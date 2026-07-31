<?php

use App\Http\Controllers\ProfileController;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ProController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProCatController;
use App\Http\Controllers\ProSubCatController;
use App\Http\Controllers\ProBrandController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\FeatureCategoryController;
use App\Http\Controllers\Backend\PortfolioController;
use App\Http\Controllers\Backend\TestimonialController;
use App\Http\Controllers\SitemapController;

Route::get('/sitemap.xml', [SitemapController::class, 'index']);

Route::get('/', function () {
    $featureCategories = App\Models\FeatureCategory::orderBy('order')->with('subcategory')->get();
    $wishlistProductIds = Illuminate\Support\Facades\Auth::check() 
        ? Illuminate\Support\Facades\Auth::user()->wishlists()->pluck('product_id')->toArray() 
        : [];
    $testimonials = App\Models\Testimonial::orderBy('created_at', 'desc')->get();
    return view('index', compact('featureCategories', 'wishlistProductIds', 'testimonials'));
})->name('home');

Route::get('/blogs', [BlogController::class, 'webIndex'])->name('web.blog');
Route::get('/blog/{id}', [BlogController::class, 'show'])->name('web.blog.details');

Route::get('/contact', function () {
    return view('main_view.pages.contact');
})->name('contact');

Route::post('/contact', function (\Illuminate\Http\Request $request) {
    return back()->with('success', 'Thank you! Your message has been sent (Dummy Action).');
})->name('contact.submit');

Route::view('/return-policy', 'main_view.pages.return_policy')->name('return.policy');
Route::view('/terms-and-conditions', 'main_view.pages.terms_conditions')->name('terms.conditions');
Route::view('/privacy-policy', 'main_view.pages.privacy_policy')->name('privacy.policy');
Route::view('/faq', 'main_view.pages.faq')->name('faq');
Route::view('/about-us', 'main_view.pages.about_us')->name('about');
Route::get('/Products/Search', [ProductController::class, 'search'])->name('search.product');

Route::get('/product/{id}/{slug?}', [ProductController::class, 'show'])->name('product.show')->where('id', '[0-9]+');

Route::get('/dashboard', function () {
    if (Auth::guard('admin')->check()) {
        return redirect()->route('admin.dashboard');
    } elseif (Auth::guard('web')->check()) {
        return redirect()->route('user.dashboard');
    } else {
        return redirect()->route('login');
    }
});

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__.'/auth.php';

// User Auth Routes (Accessible to guests)
Route::middleware('guest')->group(function () {
    // Google Auth Routes
    Route::get('/auth/google', [App\Http\Controllers\Auth\GoogleLoginController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [App\Http\Controllers\Auth\GoogleLoginController::class, 'handleGoogleCallback']);

    Route::get('/user-login', function () {
        return view('main_view.pages.user_login');
    })->name('user_login');
    Route::post('/user-login', [LoginController::class, 'store'])->name('login.store');

    Route::get('/user-register', function () {
        return view('main_view.pages.user_register');
    })->name('user_register');
    Route::post('/user-register', [RegisterController::class, 'store'])->name('register.store');

    Route::get('/otp_validation', function () {
        return view('main_view.pages.otp_validation');
    })->name('otp_validation');
    Route::post('/verify-otp', [RegisterController::class, 'verifyOtp'])->name('otp.verify');
    Route::post('/resend-otp', [RegisterController::class, 'resendOtp'])->name('otp.resend');
});


Route::middleware(['auth:admin', 'permission'])->prefix('admin')->group(function () {
   


    Route::get('/dashboard', [\App\Http\Controllers\Backend\AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // Notification Routes
    Route::get('/notifications/get', [\App\Http\Controllers\Backend\NotificationController::class, 'index'])->name('admin.notifications.get');
    Route::get('/notifications/unread-count', [\App\Http\Controllers\Backend\NotificationController::class, 'unreadCount'])->name('admin.notifications.count');
    Route::post('/notifications/read', [\App\Http\Controllers\Backend\NotificationController::class, 'markAsRead'])->name('admin.notifications.read');
    Route::get('/notifications/all', [\App\Http\Controllers\Backend\NotificationController::class, 'viewAll'])->name('admin.notifications.all');

    //Manage product category start here 

    Route::get('/product/all/Categories', function () {
        return view('backend.pages.product.proCat');
    })->name('manage.procat');

    Route::get('/product/all/brand', function () {
        return view('backend.pages.product.proBrand');
    })->name('manage.brand');


    Route::get('/product/Manage', function () {
        return view('backend.pages.product.allProduct');
    })->name('all.product');

    Route::get('/product/new/create', function () {
        return view('backend.pages.product.productAdd');
    })->name('add.product');

    Route::post('product/create', [ProductController::class, 'createProduct'])->name('create.product');
    
    // Restricted Delete Routes
    Route::middleware(['role:super_admin,admin'])->group(function () {
        Route::post('product/destroy', [ProductController::class,'destroyProduct'])->name('destroy.product');
        Route::post('product/category/destroy', [ProCatController::class,'destroyProCat'])->name('destroy.proCat');
        Route::post('product/category', [ProSubCatController::class,'destroySubProCat'])->name('destroy.proSubCategory');
        Route::post('product/destroy/brand', [ProBrandController::class,'destroyBrand'])->name('destroy.ProBrand');
    });

    // Product Edit & Update
    Route::get('/product/edit/{id}', [ProductController::class, 'editProduct'])->name('edit.product');
    Route::post('/product/update/{id}', [ProductController::class, 'updateProduct'])->name('update.product');

    // Bulk Upload
    Route::post('/product/bulk-upload', [ProductController::class, 'processBulkUpload'])->name('product.bulk.upload');
    Route::get('/product/download-demo-csv', [ProductController::class, 'downloadDemoCsv'])->name('product.demo.csv');
   

    //Manage product Sub category start here 

  Route::get('/product/Sub/Categories', function () {
    return view('backend.pages.product.proSubCat');})->name('manage.proSubCat');


  //product Sub Category route Start
 
    
 //manage product categoryEnd here

  //product Category route Start
  Route::post('product/create/category', [ProCatController::class, 'createProCategory'])->name('create.proCategory');
  Route::get('/product/category/edit/{id}', [ProCatController::class, 'editProCat'])->name('edit.proCat');
  Route::post('/product/category/update/{id}', [ProCatController::class, 'updateProCat'])->name('update.proCat');
  
  
  //product Category route End


  
  Route::post('product/create/sub/category', [ProSubCatController::class, 'createSubProCategory'])->name('create.proSubCategory');
  
  

  Route::post('product/create/brand', [ProBrandController::class, 'createBrand'])->name('create.proBrand');
  
  //product Category route End

//manage product categoryEnd here

});

// User dashboard (logged-in users)
Route::middleware('auth')->prefix('user')->name('user.')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile/edit', [UserDashboardController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [UserDashboardController::class, 'updateProfile'])->name('profile.update');
    Route::get('/change-password', [UserDashboardController::class, 'showChangePassword'])->name('change_password');
    Route::post('/change-password', [UserDashboardController::class, 'updatePassword'])->name('update_password');
    Route::get('/orders', [UserDashboardController::class, 'orders'])->name('orders');
    Route::get('/orders/{id}/invoice', [UserDashboardController::class, 'downloadInvoice'])->name('orders.invoice');
    Route::get('/returns', [UserDashboardController::class, 'returns'])->name('returns');
    Route::post('/returns', [UserDashboardController::class, 'storeReturn'])->name('returns.store');
    Route::delete('/returns/{id}', [UserDashboardController::class, 'destroyReturn'])->name('returns.destroy');
    Route::get('/wishlist', [UserDashboardController::class, 'wishlist'])->name('wishlist');
    Route::post('/wishlist', [UserDashboardController::class, 'storeWishlist'])->name('wishlist.store');
    Route::post('/wishlist/toggle', [UserDashboardController::class, 'toggleWishlist'])->name('wishlist.toggle');
    Route::delete('/wishlist/{id}', [UserDashboardController::class, 'destroyWishlist'])->name('wishlist.destroy');
    Route::get('/reviews', [UserDashboardController::class, 'reviews'])->name('reviews');
    Route::post('/reviews', [UserDashboardController::class, 'storeReview'])->name('reviews.store');
    Route::put('/reviews/{id}', [UserDashboardController::class, 'updateReview'])->name('reviews.update');
    Route::put('/reviews/{id}', [UserDashboardController::class, 'updateReview'])->name('reviews.update');
    Route::delete('/reviews/{id}', [UserDashboardController::class, 'destroyReview'])->name('reviews.destroy');
    Route::get('/orders/{id}/edit', [UserDashboardController::class, 'editOrder'])->name('order.edit');
    Route::post('/orders/{id}/update', [UserDashboardController::class, 'updateOrder'])->name('order.update');
    Route::post('/orders/{id}/cancel', [UserDashboardController::class, 'cancelOrder'])->name('order.cancel');
});

// Test mail (only when APP_DEBUG=true) — visit /test-mail?email=your@email.com to send a test OTP
if (config('app.debug')) {
    Route::get('/test-mail', function (\Illuminate\Http\Request $request) {
        $email = $request->query('email');
        if (! $email || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return 'Add ?email=your@email.com to the URL (e.g. /test-mail?email=you@gmail.com)';
        }
        $otp = (string) random_int(100000, 999999);
        try {
            Mail::to($email)->send(new OtpMail($otp, $email));
            return "Test OTP email sent to {$email}. Code: {$otp} (check inbox or spam)";
        } catch (\Throwable $e) {
            return 'Mail failed: ' . $e->getMessage();
        }
    });
}

// Checkout
Route::get('/checkout', [App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [App\Http\Controllers\CheckoutController::class, 'store'])->name('checkout.store');
Route::post('/checkout/apply-promo', [App\Http\Controllers\CheckoutController::class, 'applyPromo'])->name('checkout.promo');
Route::get('/checkout/success', [App\Http\Controllers\CheckoutController::class, 'success'])->name('checkout.success');

Route::group([], function () {
    Route::get('/orders/{id}', function ($id) {
        return redirect()->route('admin.orders.show', $id);
    });
    Route::get('/return-requests', function () {
        return redirect()->route('admin.returns.index');
    });
    Route::get('/product/edit/{id}', function ($id) {
        return redirect()->route('edit.product', $id);
    });
});

Route::middleware(['auth:admin', 'permission'])->prefix('admin')->group(function () {
                Route::get('/blog', function () {
                    return view('backend.pages.blog.allBlog');
                })->name('all.blog');
    
                Route::get('/create/category', function () {
                    return view('backend.pages.blog.createCategory');
                 })->name('create.category');
                Route::get('/create/blog', function () {
                    return view('backend.pages.blog.createBlog');
                 })->name('create.blog');
    
                Route::get('/manage/edit/blog/{id}', function ($id) {
                    return view('backend.pages.blog.editBlog', compact('id'));
                })->name('edit.blog');
    
                Route::post('/store/blog', [BlogController::class, 'store'])->name('blog.store');
                Route::post('/blog/update/{id}', [BlogController::class, 'updateb'])->name('blog.updateb');
                Route::post('/blog/{id}', [BlogController::class,'destroy'])->name('blog.destroy');
    
            
    
    //blog route end
    
    //Category route Start
    
    Route::post('/create/category', [CategoryController::class, 'category'])->name('blog.category');
    Route::post('/category/{id}', [CategoryController::class,'destroy'])->name('category.destroy');
    
    Route::get('/show/result/', [CategoryController::class, 'sort'])->name('sort.category');
    //Category route End
    
    //Profile route Start
    
                Route::get('manage/edit/profile', function () {
                    return view('backend.pages.profile.editProfile');
                     })->name('edit.profile');
                    
                   
                     Route::post('/store/profile', [ProController::class, 'store'])->name('profile.store');
                     Route::post('/profile/update', [ProController::class, 'updatep'])->name('profile.updatep');
                
               
    //Profile route end
    
    
    
    //Content route Start
                        Route::get('/manage/content', function () {
                    return view('backend.pages.website.manageContent');
                    })->name('manage.content');
                    Route::post('/content/update', [ContentController::class, 'updatec'])->name('content.updatec');
                    Route::get('/manage/content-setting', [\App\Http\Controllers\Backend\ContentSettingController::class, 'index'])->name('manage.content.setting');
                    Route::post('/manage/content-setting/update', [\App\Http\Controllers\Backend\ContentSettingController::class, 'update'])->name('manage.content.setting.update');
    
    //Content route End
    
    
    //Content route Start
    
    Route::get('/manage/admin', function () {
        return view('backend.pages.admin.manageAdmin');
        })->name('manage.admin');

    // Feature Category Routes
    Route::get('/manage/feature-category', [FeatureCategoryController::class, 'index'])->name('manage.feature.category');
    Route::post('/manage/feature-category', [FeatureCategoryController::class, 'update'])->name('update.feature.category');

    // Portfolio Management Routes
    Route::get('/manage/portfolio', [PortfolioController::class, 'index'])->name('manage.portfolio');
    Route::post('/manage/portfolio', [PortfolioController::class, 'update'])->name('update.portfolio');

    // General Settings Routes
    Route::get('/general-settings', [App\Http\Controllers\Backend\GeneralSettingController::class, 'index'])->name('manage.settings'); // redirects to manage.offers
    
    // manage.offers
    Route::get('/general-settings/offers', [App\Http\Controllers\Backend\GeneralSettingController::class, 'manageOffers'])->name('manage.offers');
    Route::post('/general-settings/offers/update', [App\Http\Controllers\Backend\GeneralSettingController::class, 'updateOffers'])->name('update.offers');

    // manage.charges
    Route::get('/general-settings/charges', [App\Http\Controllers\Backend\GeneralSettingController::class, 'manageCharges'])->name('manage.charges');
    Route::post('/general-settings/charges/update', [App\Http\Controllers\Backend\GeneralSettingController::class, 'updateCharges'])->name('update.charges');

    // manage.promocodes
    Route::get('/general-settings/promocodes', [App\Http\Controllers\Backend\PromoCodeController::class, 'index'])->name('manage.promocodes');
    
    // manage.app.settings
    Route::get('/general-settings/app-settings', [App\Http\Controllers\Backend\AppSettingsController::class, 'index'])->name('manage.app.settings');
    Route::post('/general-settings/app-settings/update', [App\Http\Controllers\Backend\AppSettingsController::class, 'update'])->name('update.app.settings');

    // manage.email.account
    Route::get('/general-settings/email-account', [App\Http\Controllers\Backend\GeneralSettingController::class, 'manageEmailAccount'])->name('manage.email.account');
    Route::post('/general-settings/email-account/update', [App\Http\Controllers\Backend\GeneralSettingController::class, 'updateEmailAccount'])->name('update.email.account');

    // manage.login
    Route::get('/general-settings/login', [App\Http\Controllers\Backend\GeneralSettingController::class, 'manageLogin'])->name('manage.login');
    Route::post('/general-settings/login/update', [App\Http\Controllers\Backend\GeneralSettingController::class, 'updateLogin'])->name('update.login');

    // manage.logs
    Route::get('/general-settings/logs', [App\Http\Controllers\Backend\AdminLogController::class, 'index'])->name('admin.logs.index');

    // Promo Code Actions
    Route::post('/promo-codes/store', [App\Http\Controllers\Backend\PromoCodeController::class, 'store'])->name('promo.store');
    Route::get('/promo-codes/delete/{id}', [App\Http\Controllers\Backend\PromoCodeController::class, 'destroy'])->name('promo.delete');
    Route::get('/promo-codes/status/{id}', [App\Http\Controllers\Backend\PromoCodeController::class, 'updateStatus'])->name('promo.status');
    
    // Order Management Routes
    // Route::get('/product/ajax-search', [\App\Http\Controllers\ProductController::class, 'ajaxSearch'])->name('product.search.ajax');
    Route::get('/orders/create', [\App\Http\Controllers\Backend\AdminOrderCreationController::class, 'create'])->name('admin.orders.create');
    Route::post('/orders/store', [\App\Http\Controllers\Backend\AdminOrderCreationController::class, 'store'])->name('admin.orders.store');
    Route::post('/orders/apply-promo', [\App\Http\Controllers\Backend\AdminOrderCreationController::class, 'applyPromo'])->name('apply.promo.ajax');
    Route::get('/orders/promo-suggestions', [\App\Http\Controllers\Backend\AdminOrderCreationController::class, 'getPromoSuggestions'])->name('admin.orders.promo_suggestions');

    Route::get('/orders', [App\Http\Controllers\OrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/orders/{id}', [App\Http\Controllers\OrderController::class, 'show'])->name('admin.orders.show');
    Route::get('/orders/{id}/pdf', [App\Http\Controllers\OrderController::class, 'downloadPdf'])->name('admin.orders.pdf');
    Route::post('/orders/{id}/status', [App\Http\Controllers\OrderController::class, 'updateStatus'])->name('admin.orders.status');

    // Return Request Management Routes
    Route::get('/return-requests', [App\Http\Controllers\Backend\AdminReturnController::class, 'index'])->name('admin.returns.index');
    Route::post('/return-requests/{id}/status', [App\Http\Controllers\Backend\AdminReturnController::class, 'updateStatus'])->name('admin.returns.status');

    // Testimonial Management Routes
    Route::get('/manage/testimonial', [TestimonialController::class, 'index'])->name('manage.testimonial');
    Route::get('/manage/testimonial/create', [TestimonialController::class, 'create'])->name('create.testimonial');
    Route::post('/manage/testimonial/store', [TestimonialController::class, 'store'])->name('store.testimonial');
    Route::get('/manage/testimonial/edit/{id}', [TestimonialController::class, 'edit'])->name('edit.testimonial');
    Route::put('/manage/testimonial/update/{id}', [TestimonialController::class, 'update'])->name('update.testimonial');
    Route::delete('/manage/testimonial/destroy/{id}', [TestimonialController::class, 'destroy'])->name('destroy.testimonial');

    //Login Profile route Start
    
    // });
    
    
    
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        //Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::post('/admin/update', [ProfileController::class, 'update'])->name('profile.update');
        Route::post('/logout', [ProfileController::class, 'destroy'])->name('admin.logout');
  
        // Manage Reviews
        Route::get('/manage/reviews', [\App\Http\Controllers\Backend\ReviewController::class, 'index'])->name('manage.reviews');
        Route::delete('/manage/reviews/{id}', [\App\Http\Controllers\Backend\ReviewController::class, 'destroy'])->name('destroy.review');

        // Inventory Management Routes
        Route::get('/inventory', [\App\Http\Controllers\Backend\InventoryController::class, 'index'])->name('admin.inventory.index');
        Route::get('/inventory/create', [\App\Http\Controllers\Backend\InventoryController::class, 'create'])->name('admin.inventory.create');
        Route::post('/inventory/store', [\App\Http\Controllers\Backend\InventoryController::class, 'store'])->name('admin.inventory.store');
        Route::get('/inventory/get-sizes/{productId}', [\App\Http\Controllers\Backend\InventoryController::class, 'getProductSizes'])->name('admin.inventory.get-sizes');

        // Damage Stock Management Routes
        Route::get('/damage-stock', [\App\Http\Controllers\Backend\DamageStockController::class, 'index'])->name('admin.damage.stock');
        Route::get('/damage-stock/create', [\App\Http\Controllers\Backend\DamageStockController::class, 'create'])->name('admin.damage.create');
        Route::post('/damage-stock/store', [\App\Http\Controllers\Backend\DamageStockController::class, 'store'])->name('admin.damage.store');
        Route::get('/damage-stock/restore/{id}', [\App\Http\Controllers\Backend\DamageStockController::class, 'restore'])->name('admin.damage.restore');

        // Current Stock Management Routes
        Route::get('/current-stock', [\App\Http\Controllers\Backend\StockController::class, 'index'])->name('admin.current.stock');

        // Manage Admins
        Route::get('/manage-admins', [\App\Http\Controllers\Backend\AdminManagementController::class, 'index'])->name('admin.manage.index');

        // Manage Roles
        Route::get('/manage-roles', [\App\Http\Controllers\Backend\RoleController::class, 'index'])->name('admin.roles.index');
        Route::get('/manage-roles/create', [\App\Http\Controllers\Backend\RoleController::class, 'create'])->name('admin.roles.create');
        Route::post('/manage-roles', [\App\Http\Controllers\Backend\RoleController::class, 'store'])->name('admin.roles.store');
        Route::get('/manage-roles/{id}/edit', [\App\Http\Controllers\Backend\RoleController::class, 'edit'])->name('admin.roles.edit');
        Route::put('/manage-roles/{id}', [\App\Http\Controllers\Backend\RoleController::class, 'update'])->name('admin.roles.update');
        Route::delete('/manage-roles/{id}', [\App\Http\Controllers\Backend\RoleController::class, 'destroy'])->name('admin.roles.destroy');
        
        Route::middleware(['role:super_admin'])->group(function () {
            Route::get('/manage-admins/create', [\App\Http\Controllers\Backend\AdminManagementController::class, 'create'])->name('admin.manage.create');
            Route::post('/manage-admins', [\App\Http\Controllers\Backend\AdminManagementController::class, 'store'])->name('admin.manage.store');
        });

        Route::get('/manage-admins/{id}/edit', [\App\Http\Controllers\Backend\AdminManagementController::class, 'edit'])->name('admin.manage.edit');
        Route::put('/manage-admins/{id}', [\App\Http\Controllers\Backend\AdminManagementController::class, 'update'])->name('admin.manage.update');
        
        Route::delete('/manage-admins/{id}', [\App\Http\Controllers\Backend\AdminManagementController::class, 'destroy'])
             ->middleware('role:super_admin,admin')
             ->name('admin.manage.destroy');

        // Customer Management (Super Admin only)
        Route::middleware(['role:super_admin'])->group(function () {
            Route::get('/manage-customers', [\App\Http\Controllers\Backend\CustomerController::class, 'index'])->name('admin.customers.index');
            Route::get('/manage-customers/{id}', [\App\Http\Controllers\Backend\CustomerController::class, 'show'])->name('admin.customers.show');
        });
    });

    Route::post('/items/filter', [ProductController::class,'filterProduct']);
    Route::get('/ajax/search', [ProductController::class, 'ajaxSearch'])->name('product.search.ajax');
   