<?php


namespace App\Models\Material\Enums;


use App\Libraries\Base\BaseEnum;

class FileType extends BaseEnum
{
    const WORD = 1;
    const PDF = 2;
    const EXCEL = 3;

    public static array $texts = [
        self::WORD => 'word',
        self::PDF => 'pdf',
        self::EXCEL => 'excel'
    ];
}
