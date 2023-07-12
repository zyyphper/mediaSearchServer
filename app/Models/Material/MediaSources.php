<?php


namespace App\Models\Material;


use App\Libraries\Base\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class MediaSources extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'save_path',
        'tmp_path'
    ];
}
