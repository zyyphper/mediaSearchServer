<?php


namespace App\Models\Admin;

use Encore\Admin\Auth\Database\Administrator;

class PlatformUsers extends Administrator
{
    protected $fillable = ['platform_id','username', 'password', 'name', 'avatar'];

    public function platform()
    {
        return $this->belongsTo(Platform::class);
    }
}
