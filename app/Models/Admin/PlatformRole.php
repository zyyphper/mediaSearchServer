<?php


namespace App\Models\Admin;

use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Auth\Database\Role;
use Encore\Admin\Facades\Admin;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class PlatformRole extends Role
{
    protected $fillable = ['platform_id','name', 'slug'];

    /**
     * @return BelongsTo
     */
    public function platform() : BelongsTo
    {
        $platformModel = config('admin.database.platforms_model');
        return $this->belongsTo($platformModel,'platform_id','id');
    }

    public static function getPlatformRole($platformId)
    {
        if ($platformId === 0) {
            return self::all()->pluck('name','id');
        }
        return self::where('platform_id',$platformId)->pluck('name', 'id');
    }
}
