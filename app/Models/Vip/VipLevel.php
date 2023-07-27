<?php


namespace App\Models\Vip;


use App\Libraries\Base\BaseModel;

class VipLevel extends BaseModel
{
    protected $primaryKey = "level";
    protected $connection = "member";

    public static $defaultLevel = 0;

    protected $fillable = [
        'level',
        'name',
        'requirement_score',
        'space_capacity',
        'type_change_times'
    ];

    public function equities()
    {
        return $this->belongsToMany(VipEquity::class,VipLevelEquity::class,'level_id','equity_id')->withPivot('num','unit');
    }

    public function levelEquities()
    {
        return $this->hasMany(VipLevelEquity::class,'level_id','id');
    }
}
