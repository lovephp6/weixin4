<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Wechat extends Model
{
    const APPID = 'wx69e5d223d07ad5e9';
    const APPSECRET = 'a1526b1dee0caa38ebb2426cc286cc3e';
    //缓存时间
    const CACHE_TIME = 7000;
    public static $redis;
    public static function getRedis()
    {
        if (!self::$redis instanceof \Redis) {
            self::$redis = new \Redis();
            self::$redis->connect('127.0.0.1', 6379);
        }
        return self::$redis;
    }

    public static function existToken(Request $request)
    {
        $token = Wechat::getRedis()->get('access_token');
        if ($token) {
            return $token;
        } else {
            return Wechat::getToekn($request);
        }
    }

    public static function getToekn(Request $request)
    {
        $curl = curl_init();
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . self::APPID . "&secret=" . self::APPSECRET;
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        $output = curl_exec($curl);
        curl_close($curl);
        $obj = json_decode($output, true);

//        $redis = new \Redis();
//        $redis->connect('127.0.0.1', '6379');
        Wechat::getRedis()->setex('access_token', self::CACHE_TIME, $obj['access_token']);
        return Wechat::getRedis()->get('access_token');
    }
}
