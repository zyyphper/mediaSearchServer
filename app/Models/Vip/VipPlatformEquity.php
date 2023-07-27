<?php


namespace App\Models\Vip;


use App\Libraries\Base\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VipPlatformEquity extends BaseModel
{
    protected $connection = "member";

    protected $fillable = [
        'equity_id',
        'platform_id',
        'total_num',
        'total_unit',
    ];

    /**
     * @return BelongsTo
     */
    public function platform() : BelongsTo
    {
        $platformModel = config('admin.database.platforms_model');
        return $this->belongsTo($platformModel,'platform_id','id');
    }

    public function equity() : BelongsTo
    {
        return $this->belongsTo(VipEquity::class,'equity_id','id');
    }
}
