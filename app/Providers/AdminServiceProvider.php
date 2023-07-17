<?php


namespace App\Providers;


use App\Admin\Middleware\PlatformLogOperation;
use Encore\Admin\Middleware\Authenticate;
use Encore\Admin\Middleware\Bootstrap;
use Encore\Admin\Middleware\Permission;
use Encore\Admin\Middleware\Pjax;
use Encore\Admin\Middleware\Session;

class AdminServiceProvider extends \Encore\Admin\AdminServiceProvider
{
    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'admin.auth'       => Authenticate::class,
        'admin.pjax'       => Pjax::class,
        'admin.log'        => PlatformLogOperation::class,
        'admin.permission' => Permission::class,
        'admin.bootstrap'  => Bootstrap::class,
        'admin.session'    => Session::class,
    ];
}
