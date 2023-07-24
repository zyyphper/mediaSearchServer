<?php


namespace App\Models\Material;


use App\Libraries\Base\BaseModel;
use App\Models\Admin\Platform;
use App\Models\Admin\PlatformUser;

class FileGroupTemplate extends BaseModel
{

    protected $connection = "business";

    protected $fillable = [
        'group_id',
        'template_id',
    ];

}
