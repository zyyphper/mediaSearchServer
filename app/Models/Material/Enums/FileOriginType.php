<?php


namespace App\Models\Material\Enums;


use App\Libraries\Base\BaseEnum;

class FileOriginType extends BaseEnum
{
    const IMPORT = 1;
    const TEMPLATE_GENERATE = 2;

    public static $texts = [
        self::IMPORT => '导入',
        self::TEMPLATE_GENERATE => '模板生成'
    ];
}
