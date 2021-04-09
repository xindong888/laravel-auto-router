# Laravel自动路由

**1.功能说明:根据网址自动匹配路由**

**2.特点:**
> 1.非入侵式的  
> 2.不影响现有的路由  
> 3.使用简单
>

**3.使用**

只需在现有路由下面调用   
例如在router/web.php里调用:

```php
<?php
use com\xjke\router\AutoRouter;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
/*----------调用即可-------------*/
AutoRouter::getAutoRouter(['home']);

```