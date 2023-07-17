<?php


namespace App\Models\Admin;

use Encore\Admin\Auth\Database\Administrator;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class PlatformUser extends Administrator
{
    protected $fillable = ['platform_id','username', 'password', 'name', 'avatar'];

    /**
     * @return BelongsTo
     */
    public function platform() : BelongsTo
    {
        $platformModel = config('admin.database.platforms_model');
        return $this->belongsTo($platformModel,'platform_id','id');
    }
}
