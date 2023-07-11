<?php
/**
 * 基类控制器
 */

namespace App\Libraries\Base;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{

    /**
     * 获取select组件所需参数
     * @param string $textField [option-文本列]
     * @param string $idField [option-ID列]
     * @return mixed
     */
    public static function getSelectOptionData(string $textField = 'name', string $idField = 'id',array $where = [])
    {
        return self::where($where)->pluck("{$textField} as text", $idField);
    }

    /**
     * 获取key-value数组,提供select控间使用
     * @param string $keyField
     * @param string $valueField
     * @param array $getKeyIn
     * @return mixed
     */
    public static function getKeyValueArr($keyField = 'id', $valueField = 'name', $getKeyIn = [])
    {
        if (!empty($getKeyIn)) {
            $keyValueArr = static::whereIn('id', $getKeyIn)->pluck("{$valueField} as text", $keyField);
        } else {
            $keyValueArr = static::pluck("{$valueField} as text", $keyField);
        }

        return $keyValueArr;
    }


}
