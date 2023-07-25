<?php


namespace App\Models\Material\Enums;


use App\Libraries\Base\BaseEnum;

class FileType extends BaseEnum
{
    const WORD = 1;
    const PDF = 2;
    const EXCEL = 3;

    public static $texts = [
        self::WORD => 'word',
        self::PDF => 'pdf',
        self::EXCEL => 'excel'
    ];

    public static array $extensionMap = [
        'docx' => self::WORD,
        'doc' => self::WORD,
        'pdf' => self::PDF,
        'xlsx' => self::EXCEL
    ];
}
