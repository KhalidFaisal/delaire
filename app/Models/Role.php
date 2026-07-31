<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'permissions',
    ];

    protected $casts = [
        'permissions' => 'array',
    ];

    /**
     * Map permissions to route names.
     */
    const PERMISSION_MAP = [
        'dashboard' => ['admin.dashboard'],
        
        // Manage Stock
        'inventory' => ['admin.inventory.index', 'admin.inventory.create', 'admin.inventory.store', 'admin.inventory.get-sizes'],
        'current_stock' => ['admin.current.stock'],
        'damage_stock' => ['admin.damage.stock', 'admin.damage.create', 'admin.damage.store', 'admin.damage.restore'],
        
        // Product Management
        'pro_category' => ['manage.procat', 'create.proCategory', 'edit.proCat', 'update.proCat', 'destroy.proCat'],
        'pro_sub_category' => ['manage.proSubCat', 'create.proSubCategory', 'destroy.proSubCategory'],
        'pro_brand' => ['manage.brand', 'create.proBrand', 'destroy.ProBrand'],
        'products' => ['all.product', 'add.product', 'create.product', 'destroy.product', 'edit.product', 'update.product', 'product.bulk.upload', 'product.demo.csv'],
        'reviews' => ['manage.reviews', 'destroy.review'],
        
        // Manage Orders
        'create_order' => ['admin.orders.create', 'admin.orders.store', 'apply.promo.ajax', 'admin.orders.promo_suggestions'],
        'all_orders' => ['admin.orders.index', 'admin.orders.show', 'admin.orders.pdf', 'admin.orders.status'],
        'return_requests' => ['admin.returns.index', 'admin.returns.status'],
        
        // Manage Blog
        'blog_category' => ['create.category', 'blog.category', 'category.destroy', 'sort.category'],
        'write_blog' => ['create.blog', 'blog.store'],
        'all_blogs' => ['all.blog', 'edit.blog', 'blog.updateb', 'blog.destroy'],
        
        // Manage Website
        'portfolio' => ['manage.portfolio', 'update.portfolio'],
        'content' => ['manage.content', 'content.updatec'],
        'feature_category' => ['manage.feature.category', 'update.feature.category'],
        'content_setting' => ['manage.content.setting', 'manage.content.setting.update'],
        'testimonial' => ['manage.testimonial', 'create.testimonial', 'store.testimonial', 'edit.testimonial', 'update.testimonial', 'destroy.testimonial'],
        
        // Manage Settings
        'offers' => ['manage.offers', 'update.offers'],
        'charges' => ['manage.charges', 'update.charges'],
        'promo_codes' => ['manage.promocodes', 'promo.store', 'promo.delete', 'promo.status'],
        'app_settings' => ['manage.app.settings', 'update.app.settings'],
        'email_account' => ['manage.email.account', 'update.email.account'],
        'login_settings' => ['manage.login', 'update.login'],
        'manage_admin' => [
            'admin.manage.index', 
            'admin.manage.create', 
            'admin.manage.store', 
            'admin.manage.edit', 
            'admin.manage.update', 
            'admin.manage.destroy',
            'admin.roles.index',
            'admin.roles.create',
            'admin.roles.store',
            'admin.roles.edit',
            'admin.roles.update',
            'admin.roles.destroy'
        ],
        'logs' => ['admin.logs.index'],
        
        // Manage Customers
        'customers' => ['admin.customers.index', 'admin.customers.show']
    ];

    /**
     * Map permissions to human-readable names.
     */
    const PERMISSION_LABELS = [
        'dashboard' => 'Dashboard Access',
        'inventory' => 'Inventory',
        'current_stock' => 'Current Stock Items',
        'damage_stock' => 'Damage Stock',
        'pro_category' => 'Manage Category',
        'pro_sub_category' => 'Manage Sub Category',
        'pro_brand' => 'Manage Brands',
        'products' => 'Manage Products',
        'reviews' => 'Manage Reviews',
        'create_order' => 'Create Order',
        'all_orders' => 'All Orders',
        'return_requests' => 'Return Requests',
        'blog_category' => 'Add Category',
        'write_blog' => 'Write Blog',
        'all_blogs' => 'All Blogs',
        'portfolio' => 'Manage Portfolio',
        'content' => 'Manage Content',
        'feature_category' => 'Feature Category',
        'content_setting' => 'Content Setting',
        'testimonial' => 'Manage Testimonial',
        'offers' => 'Manage Offers',
        'charges' => 'Manage Charges',
        'promo_codes' => 'Manage Promo Codes',
        'app_settings' => 'App Settings',
        'email_account' => 'Manage Email Account',
        'login_settings' => 'Manage Login',
        'manage_admin' => 'Manage Admin & Roles',
        'logs' => 'Logs',
        'customers' => 'Manage Customers'
    ];

    /**
     * Group permissions for visual presentation in forms.
     */
    const PERMISSION_GROUPS = [
        'General' => [
            'dashboard' => 'Dashboard Access'
        ],
        'Manage Stock' => [
            'inventory' => 'Inventory',
            'current_stock' => 'Current Stock Items',
            'damage_stock' => 'Damage Stock'
        ],
        'Product Management' => [
            'pro_category' => 'Manage Category',
            'pro_sub_category' => 'Manage Sub Category',
            'pro_brand' => 'Manage Brands',
            'products' => 'Manage Products',
            'reviews' => 'Manage Reviews'
        ],
        'Manage Orders' => [
            'create_order' => 'Create Order',
            'all_orders' => 'All Orders',
            'return_requests' => 'Return Requests'
        ],
        'Manage Blog' => [
            'blog_category' => 'Add Category',
            'write_blog' => 'Write Blog',
            'all_blogs' => 'All Blogs'
        ],
        'Manage Website' => [
            'portfolio' => 'Manage Portfolio',
            'content' => 'Manage Content',
            'feature_category' => 'Feature Category',
            'content_setting' => 'Content Setting',
            'testimonial' => 'Manage Testimonial'
        ],
        'Manage Settings' => [
            'offers' => 'Manage Offers',
            'charges' => 'Manage Charges',
            'promo_codes' => 'Manage Promo Codes',
            'app_settings' => 'App Settings',
            'email_account' => 'Manage Email Account',
            'login_settings' => 'Manage Login',
            'manage_admin' => 'Manage Admin & Roles',
            'logs' => 'Logs'
        ],
        'Manage Customers' => [
            'customers' => 'Manage Customers'
        ]
    ];
}
