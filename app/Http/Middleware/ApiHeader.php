<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;

class ApiHeader
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->input('token');
        //当前url
        $current_url=$_SERVER['REQUEST_URI'];
        $redis_key='str:count:u:'.$token.':url:'.md5($current_url);

        $count=Redis::get($redis_key);
        echo '访问次数:'.$count;echo '<br>';
        if($count > 20){
            echo '访问次数已上限，请停一会在试';
            Redis::expire($redis_key,60);
            die;
        }

        $count=Redis::incr($redis_key);
        echo 'count:'.$count;
        return $next($request);
    }
}
