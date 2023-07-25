<?php


namespace App\Models\Admin;


use Encore\Admin\Auth\Database\Menu;
use Encore\Admin\Facades\Admin;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use \Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class PlatformMenu extends Menu
{
    use \App\Libraries\Base\Platform;
    protected $fillable = ['platform_id','parent_id', 'order', 'title', 'icon', 'uri', 'permission','is_admin'];

    /**
     * @return BelongsTo
     */
    public function platform() : BelongsTo
    {
        $platformModel = config('admin.database.platforms_model');
        return $this->belongsTo($platformModel,'platform_id','id');
    }

    public function platformConfigs() : BelongsToMany
    {
        $pivotTable = config('admin.database.platform_menu_table');
        $relatedModel = config('admin.database.platforms_model');
        return $this->belongsToMany($relatedModel, $pivotTable, 'menu_id', 'platform_id');
    }

    /**
     * @return array
     */
    public function allNodes(): array
    {
        $connection = config('admin.database.connection') ?: config('database.default');
        $orderColumn = DB::connection($connection)->getQueryGrammar()->wrap($this->orderColumn);

        $byOrder = 'ROOT ASC,'.$orderColumn;

        $query = static::query();

        if (config('admin.check_menu_roles') !== false) {
            $query->with('roles');
        }
        if ($this->isRootPlatform()) {
            return $query->selectRaw('*, '.$orderColumn.' ROOT')->orderByRaw($byOrder)->get()->toArray();
        }

        return $query->whereHas('platformConfigs',function ($query) {
                $query->where('platform_id',Admin::user()->platform_id);
            })->selectRaw('*, '.$orderColumn.' ROOT')
            ->orderByRaw($byOrder)
            ->get()->toArray();
    }
}
