<?php


namespace App\Models\Vip;


use App\Libraries\Base\BaseModel;

class VipLevelEquity extends BaseModel
{
    protected $primaryKey = "";
    protected $connection = "member";

    protected $table = "vip_level_equity";

    protected $fillable = [
        'level_id',
        'equity_id',
        'num',
        'unit',
    ];

}
