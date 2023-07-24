<?php


namespace App\Models\Admin;


use App\Libraries\Base\BaseModel;
use App\Models\Vip\VipLevel;

class Platform extends BaseModel
{
    protected $connection = "system";
    protected $fillable = [
        'id',
        'name',
        'status',
        'vip_level'
    ];

    protected $table = "admin_platforms";

    public function vipLevel()
    {
        return $this->belongsTo(VipLevel::class,'vip_level','level');
    }
}
