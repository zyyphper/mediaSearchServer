<?php


namespace App\Models\Admin;


use App\Libraries\Base\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlatformMenuConfig extends BaseModel
{
    protected $table = "admin_menu_configs";
    protected $primaryKey = "menu_id";
    protected $fillable = ['menu_id','platform_id','status'];


    /**
     * @return BelongsTo
     */
    public function platform() : BelongsTo
    {
        $platformModel = config('admin.database.platforms_model');
        return $this->belongsTo($platformModel,'platform_id','id');
    }

    public function menu() : BelongsTo
    {
        $menuModel = config('admin.database.menus_model');
        return $this->belongsTo($menuModel,'menu_id','id');
    }
}
