<?php


namespace App\Models\Material\Enums;


use App\Libraries\Base\BaseEnum;

class FileStatus extends BaseEnum
{
    const ENABLE = 1;
    const DISABLE = 2;

    public static $texts = [
        self::ENABLE => '启用',
        self::DISABLE => '禁用'
    ];
}
