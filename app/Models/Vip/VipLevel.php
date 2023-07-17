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

}
