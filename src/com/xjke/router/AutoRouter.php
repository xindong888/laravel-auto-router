<?php


namespace com\xjke\router;


use Exception;
use Illuminate\Routing\Contracts\ControllerDispatcher;
use Illuminate\Support\Facades\Route;
use ReflectionMethod;

/**
 * Class AutoRouter  根据网址自动匹配控制器
 * @package com\xjke\router
 */
class AutoRouter
{
    /**
     * 获取自动路由功能
     * @param array $modules 需要匹配的模块,也就是控制器的文件夹
     * @param string $namespace 控制器的命名空间
     * @param array $middleware 中间件
     * @param string $view 返回的视图,可以填写视图地址
     */
    public static function getAutoRouter($modules = [], $namespace = 'App\\Http\\Controllers\\', $middleware = [], $view = '404')
    {
        //获取带有模块的命名空间
        foreach ($modules as $module) {
            Route::any('/' . $module . '/{controller?}/{action?}/{params?}',
                function ($controller = 'index', $action = 'index', $params = '') use ($namespace, $view, $module) {
                    //获取控制的类路径
                    $class = $namespace . $module . '\\' . ucfirst(strtolower($controller));
                    $class1 = $namespace . $module . '\\' . ucfirst(strtolower($controller)) . 'Controller';
                    return self::getController($class, $class1, $action, $params, $view);
                })->where('params', '.*')->middleware($middleware);
        }
        //不带模块的
        Route::any('/{controller?}/{action?}/{params?}',
            function ($controller = 'index', $action = 'index', $params = '') use ($namespace, $view) {
                //获取控制的类路径
                $class = $namespace . ucfirst(strtolower($controller));
                $class1 = $namespace . ucfirst(strtolower($controller)) . 'Controller';
                return self::getController($class, $class1, $action, $params, $view);
            })->where('params', '.*')->middleware($middleware);
    }

    /**
     * 解析控制器及其参数
     * @return false|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|string[]
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public static function getController($class, $class1, $action, $params, $view)
    {
        //判断是不是一个类
        if (class_exists($class) || class_exists($class1)) {
            if (class_exists($class)) {
                $controller = new $class();//实例化控制器类
            } else {
                $controller = new $class1();//实例化控制器类
            }
            $p = explode('/', $params);//拆分参数
            //获取控制器调度
            $cd = app()->make(ControllerDispatcher::class);
            try {
                //包装控制器方法里的参数
                $param = $cd->resolveMethodDependencies($p, new ReflectionMethod($controller, $action));
                //调用控制器里的方法并传入参数
                return $controller->{$action}(...array_values($param));
            } catch (Exception $exception) {
                //echo $exception->getMessage();
            }
        }
        if ($view == '404') {
            return $view;
        } else {
            return view($view);
        }
    }
}