<?php


namespace App\Models\Material\Enums;


use App\Libraries\Base\BaseEnum;

class FileHandleStatus extends BaseEnum
{
    const WAIT = 0;
    const PROCESSING = 1;
    const SUCCESS = 2;
    const FAIL = 3;

    public static $texts = [
        self::WAIT => '待处理',
        self::PROCESSING => '处理中',
        self::SUCCESS => '处理成功',
        self::FAIL => '处理失败'
    ];
}
