<?php

namespace App\Http\Controllers;

use App\Model\Wechat;
use Illuminate\Http\Request;

class WechatController extends Controller
{

    public function __construct(Request $request)
    {
	$this->index($request);
    }

    public function index(Request $request)
    {
    //    $access_token = Wechat::existToken($request);
      //  echo  $access_token;exit;
        $postStr = file_get_contents("php://input");
	file_put_contents(resource_path('/views/test.blade.php'), $postStr);
	if (!empty($postStr)) {
            libxml_disable_entity_loader(true);
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUserName = $postObj->FromUserName;
            $toUserName = $postObj->ToUserName;
            $keyword = trim($postObj->Content);
            $time = time();
            $textTpl = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[%s]]></MsgType>
                            <Content><![CDATA[%s]]></Content>
                        </xml>";

            if ($keyword) {
                $res = sprintf($textTpl, $fromUserName, $toUserName, $time, 'text', '欢迎dudu来到这里');
                echo $res;
            } else {
                echo "nothing....";
            }
        }else{
            echo '';exit;
        }

    }
}
