<?php


namespace App\Models\Admin;

use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Auth\Database\OperationLog;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class PlatformOperationLog extends OperationLog
{
    protected $fillable = ['platform_id','user_id', 'path', 'method', 'ip', 'input'];

    /**
     * @return BelongsTo
     */
    public function platform() : BelongsTo
    {
        $platformModel = config('admin.database.platforms_model');
        return $this->belongsTo($platformModel,'platform_id','id');
    }
}
