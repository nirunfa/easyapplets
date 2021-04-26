<?php

namespace tests;

use EasyApplets\BaiduApplets;
use PHPUnit\Framework\TestCase;

class testBaiduMinigrams extends TestCase{

    public function testAccessToken(){
        //断言 
        $this->assertTrue(true);

        $app = new BaiduApplets([
            'appid'=>'Z769o94Ot2Yqt15cWcPnfkO91z3e97BT',
            'appkey'=>'ZrQT3jGnS1G7OInG5ZgMNFiDHHvvd2LV',
            // 'cache-path'=>__DIR__.'/logs/',
        ]);
        $token = $app->getAccessToken();
        var_dump($token);
        return $token;
    }

    public function testGetUnionid(){
        //断言 
        $this->assertTrue(true);
        $app = new BaiduApplets([
            'appid'=>'Z769o94Ot2Yqt15cWcPnfkO91z3e97BT',
            'appkey'=>'ZrQT3jGnS1G7OInG5ZgMNFiDHHvvd2LV',
            // 'cache-path'=>__DIR__.'/logs/',
        ]);
        $openid='Xl4ehcoZpyvNGsdpqYOHyuuKCk';
        $sessionData = $app->getSession('ba7cf598b5d846ab1b11fc9abd1af7c3NW');
        if($sessionData['code']==0){
            $unioindRes = $app->getUnioid($sessionData['data']['openid']);
            var_export($unioindRes);
        }else{
            //断言 
            $this->assertTrue(false,$sessionData['code'].','.$sessionData['msg']);
        }
        return $unioindRes ?? [];
    }

    public function testRedisCache(){
        //断言 
        $this->assertTrue(true);

        $app = new BaiduApplets([
            'appid'=>'Z769o94Ot2Yqt15cWcPnfkO91z3e97BT',
            'appkey'=>'ZrQT3jGnS1G7OInG5ZgMNFiDHHvvd2LV',
            // 'cache-path'=>__DIR__.'/logs/',
            'redis'=>[
                'host'=>'127.0.0.1',
                'port'=>6379,
                'password'=>'',
                'database'=>1,
            ]
        ]);
        $token = $app->getAccessToken();
        var_export($token);
        return $token;
    }
}
