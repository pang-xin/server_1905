<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\User;
use Illuminate\Support\Facades\Redis;

class ApiController extends Controller
{
    /**
     * @param Request $request
     * @return false|string
     * 登陆
     */
    public function index(Request $request)
    {
        $name = $request->input('name');
        $pwd = $request->input('pwd');
        $info = User::where(['name' => $name])->first();
        if ($info) {
            $pass = $info->pwd;
            if (password_verify($pwd, $pass)) {
                $token=md5($info['id'].time());
                User::where(['id'=>$info['id']])->update(['token'=>$token]);
                return json_encode(['find'=>'登陆成功','code'=>'200','token'=>$token]);
            } else {
                return json_encode(['find'=>'密码有误','code'=>'201']);
            }
        } else {
                return json_encode(['find'=>'用户名有误','code'=>'202']);
        }
    }
    /**
     * @param Request $request
     * @return false|string
     * 注册
     */
    public function create(Request $request)
    {
        $data = $request->all();
        $data['pwd'] = password_hash($data['pwd'], PASSWORD_BCRYPT);
        $data['las_login'] = time();
        $res = User::create($data);
        if ($res) {
            return json_encode(['find'=>'注册成功','code'=>'200']);
        } else {
            return json_encode(['find'=>'注册失败','code'=>'201']);
        }
    }
    /**
     * 防刷
     */
    public function brush(Request $request)
    {
        $token=$request->input('token');
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
    }

    public function encryption(Request $request)
    {
        $str=$request->input('str');
        $lengh=strlen($str);
        $data="";
        for($i=0;$i<$lengh;$i++)
        {
            $a=ord($str[$i])+3;
            $chr =chr($a);
            $data .=$chr;
        }
        echo $data;
    }

    /**
     * 解密
     */
    public function decrypt(Request $request)
    {
        $str=$request->input('str');
        $lengh=strlen($str);
        $data="";
        for($i=0;$i<$lengh;$i++)
        {
            $a=ord($str[$i])-3;
            $chr =chr($a);
            $data .=$chr;
        }
        echo $data;
    }

}
