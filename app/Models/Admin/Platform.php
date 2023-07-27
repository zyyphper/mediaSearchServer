<?php


namespace App\Models\Admin;


use App\Libraries\Base\BaseModel;
use App\Models\Vip\VipEquity;
use App\Models\Vip\VipLevel;
use App\Models\Vip\VipLevelEquity;
use App\Models\Vip\VipPlatformEquity;
use App\Models\Vip\VipPlatformEquityLog;
use App\Models\Vip\VipPlatformLevelEquityLog;

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

    /**
     * 平台会员等级
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vipLevel()
    {
        return $this->belongsTo(VipLevel::class,'vip_level','level');
    }

    /**
     * 平台会员权益
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function vipEquities()
    {
        return $this->belongsToMany(VipEquity::class,VipPlatformEquity::class,'platform_id','equity_id');
    }

    /**
     * 平台会员权益的数据变动日志
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function vipEquityUseLog()
    {
        return $this->hasMany(VipPlatformEquityLog::class,'platform_id');
    }

}
