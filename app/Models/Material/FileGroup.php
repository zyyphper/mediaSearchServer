<?php


namespace App\Models\Material;


use App\Libraries\Base\BaseModel;
use App\Models\Admin\Platform;
use App\Models\Admin\PlatformUser;

class FileGroup extends BaseModel
{

    protected $connection = "business";

    protected $fillable = [
        'platform_id',
        'name',
    ];

    public function platform()
    {
        return $this->belongsTo(Platform::class);
    }

    public function templates()
    {
        return $this->belongsToMany(FileTemplate::class,FileGroupTemplate::class,'template_id','group_id');
    }


}
