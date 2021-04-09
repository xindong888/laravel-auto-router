# Laravel自动路由

> 特点:   
> 1.非入侵式的  
> 2.不影响现有的路由  
> 3.使用简单
>

## 使用

只需要在现有的路由下面调用下就可以了   
例如在router/web.php里调用:

```php
<?php
use com\xjke\router\AutoRouter;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
AutoRouter::getAutoRouter(['home']);

```