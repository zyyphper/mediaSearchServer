<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');

    $router->group(['prefix' => 'auth','namespace' => 'Admin'], function () use ($router) {
        $router->resource('platforms', 'PlatformController');
        $router->resource('users', 'PlatformUserController');
        $router->resource('roles', 'PlatformRoleController');
        $router->resource('menu', 'PlatformMenuController');
        $router->resource('logs', 'PlatformLogController');
    });

    // 媒资信息模块
    $router->group(['prefix' => 'material','namespace' => 'Material'], function () use ($router) {
        //文件
        $router->group(['prefix' => 'file','namespace' => 'File'], function () use ($router) {
            $router->group(['prefix' => 'template','namespace' => 'Template'],function() use ($router) {
                // 模板导入
                $router->post('import', 'TemplateController@import')->name('material_files_import');
                //资源
                $router->resource('/', 'TemplateController');
            });
            $router->resource('groups', 'GroupController');
        });
    });

});
