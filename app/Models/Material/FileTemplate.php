<?php


namespace App\Models\Material;


use App\Libraries\Base\BaseModel;
use App\Models\Admin\Platform;
use App\Models\Admin\PlatformUser;

class FileTemplate extends BaseModel
{

    protected $connection = "business";

    protected $fillable = [
        'platform_id',
        'name',
        'file_type',
        'status',
        'operator'
    ];

    public function platform()
    {
        return $this->belongsTo(Platform::class);
    }

    public function operator()
    {
        return $this->belongsTo(PlatformUser::class);
    }

    public function groups()
    {
        return $this->morphMany(FileGroup::class,"groupable");
    }

    public function sources()
    {
        return $this->hasMany(FileSource::class,'origin_tpl_id');
    }


}
