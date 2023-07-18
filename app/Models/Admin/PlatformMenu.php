<?php


namespace App\Models\Admin;


use Encore\Admin\Auth\Database\Menu;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlatformMenu extends Menu
{
    protected $fillable = ['platform_id','parent_id', 'order', 'title', 'icon', 'uri', 'permission'];

    /**
     * @return BelongsTo
     */
    public function platform() : BelongsTo
    {
        $platformModel = config('admin.database.platforms_model');
        return $this->belongsTo($platformModel,'platform_id','id');
    }
}
