<?php

namespace App\Http\Controllers;

use App\Model\Wechat;
use Illuminate\Http\Request;

class WechatController extends Controller
{

    public function index(Request $request)
    {
//        $access_token = Wechat::existToken($request);
//        echo  $access_token;
        $postStr = $GLOBALS['HTTP_RAW_POST_DATA'];
        file_put_contents('a.txt', $postStr);
        exit;
        // 将timestamp, nonce, token按字典序排序
        $timestamp = $request['timestamp'];
        $nonce = $request['nonce'];
        $echostr = $request['echostr'];
        $token = 'lovephpforweixin';
        $signature = $request['signature'];
        $arr = [$timestamp, $nonce, $token];
        sort($arr);

        // 将排序后的三个参数拼接之后用sha1加密
        $str = sha1(implode('',$arr));

        //将加密后的字符串与signature进行对比,判断该请求是否来自微信
        if ($str == $signature && $echostr) {
            echo $echostr;
            exit;
        } else {
            echo 22222;
        }
    }


}
