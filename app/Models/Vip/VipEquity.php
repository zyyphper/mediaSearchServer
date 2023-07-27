<?php


namespace App\Models\Vip;


use App\Libraries\Base\BaseModel;

class VipEquity extends BaseModel
{
    protected $connection = "member";

    protected $fillable = [
        'id',
        'name',
        'unit',
    ];

    const SPACE_CAPACITY = 1;
    const FILE_SEARCH = 2;
    const TYPE_CONVERT = 3;

}
