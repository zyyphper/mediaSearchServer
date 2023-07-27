<?php


namespace App\Models\Vip;


use App\Libraries\Base\BaseModel;

class VipDict extends BaseModel
{
    protected $primaryKey = "code";
    protected $connection = "member";


    protected $fillable = [
        'code',
        'name',
        'type',
        'is_show',
        'type_desc'
    ];

}
