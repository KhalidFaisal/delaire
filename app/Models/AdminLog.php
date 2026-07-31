<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AdminLog extends Model
{
    protected $fillable = [
        'admin_id',
        'admin_name',
        'action',
        'description',
        'ip_address',
        'user_agent',
        'details',
    ];

    protected $casts = [
        'details' => 'array',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    /**
     * Helper to write an admin log entry
     *
     * @param string $action Category/action name (e.g. "Login", "Stock In")
     * @param string $description Clean, user-friendly details of the action
     * @param array|null $details Extra payloads or metadata
     * @return AdminLog
     */
    public static function log($action, $description, $details = null)
    {
        $admin = Auth::guard('admin')->user();
        
        return self::create([
            'admin_id' => $admin ? $admin->id : null,
            'admin_name' => $admin ? $admin->name : 'System/Guest',
            'action' => $action,
            'description' => $description,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'details' => $details,
        ]);
    }
}
