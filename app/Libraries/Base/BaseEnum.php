<?php
/**
 * 模型基础枚举
 *
 * @return mixed
 */
namespace App\Libraries\Base;

abstract class BaseEnum
{
    /**
     * @var array
     */
    public static $texts = [];

    public static function getName($value, bool $toLower = false): ?string
    {
        $constArr = array_flip(self::getConstList());
        if (!isset($constArr[$value])) {
            return null;
        }
        return $toLower ? strtolower($constArr[$value]) : $constArr[$value];
    }

    public static function getText($value): ?string
    {
        if (!isset(static::$texts[$value])) {
            return null;
        }
        return static::$texts[$value];
    }

    public static function getByName(string $constName): ?int
    {
        $constName = strtoupper($constName);
        $constArr = self::getConstList();
        if (!isset($constArr[$constName])) {
            return null;
        }
        return $constArr[$constName];
    }

    public static function getByText(string $text): ?int
    {
        $textMapValue = array_flip(static::$texts);
        if (!isset($textMapValue[$text])) {
            return null;
        }
        return $textMapValue[$text];
    }

    public static function getTextByName($name): ?string
    {
        if (!isset(static::$texts[$name])) {
            return null;
        }
        return static::$texts[$name];
    }


    public static function getConstList(string $valueType = ''): array
    {
        $reflectionClass = new \ReflectionClass(static::class);
        $constArr = $reflectionClass->getConstants();

        if ($valueType) {
            $constArr = array_map(function ($value) use ($valueType) {
                settype($value, $valueType);
                return $value;
            }, $constArr);
        }

        return $constArr;
    }

    public static function getNameList(): array
    {
        $constNames = array_keys(static::getConstList());

        return array_map(function ($constName) {
            return mb_strtolower($constName);
        }, $constNames);
    }

    public static function getTextList(): array
    {
        return static::$texts;
    }
}
