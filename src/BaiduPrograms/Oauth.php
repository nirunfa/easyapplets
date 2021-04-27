<?php

namespace EasyApplets\BaiduPrograms;

trait Oauth {

    private $BASE_URI = 'https://openapi.baidu.com';
    private $NEW_BASE_URI = 'https://spapi.baidu.com';

    /**
     * 获取session
     * @param string $code
     * @return array
     */
    public function getSession(string $code){
        $configs = $this->configs;
        $url = "/oauth/jscode2sessionkey";
        $reponse = $this->POST($url,[
            'base_uri' => $this->NEW_BASE_URI,
            'json' => [
                'code' => $code,
                'client_id' => $configs['appid'],
                'sk' => $configs['appkey']
            ],
        ]);
        $state = 0;
        $msg = 'ok';
        if(isset($reponse['errno'])){
            $state = $reponse['errno'];
            $msg = 'error:'.$reponse['error'].',descrip:'.$reponse['error_description'];
        }
        return [
            'code'=>$state,
            'msg'=>$msg,
            'data'=>$reponse,
        ];
    }

    /**
     * 获取token
     * @return array
     */
    public function getAccessToken(){
        $configs = $this->configs;

        $reponse = $this->cache->get('baidu_tokens',[]);
        if( empty($reponse) || (isset($reponse['expire_time']) && $reponse['expire_time'] <= time()) ){
            $url = "/oauth/2.0/token?grant_type=client_credentials&client_id={$configs['appid']}&client_secret={$configs['appkey']}&scope=smartapp_snsapi_base";
            $reponse = $this->GET($url,[
                'base_uri' => $this->BASE_URI,
            ]);
            if(isset($reponse['access_token'])){
                $time = 29.5*24*60*60;
                $expireTime = time() + $time;
                $reponse['expire_time'] = $expireTime;
                $this->cache->set('baidu_tokens',$reponse,$time);
            }
        }
        if(isset($reponse['error'])){
            return [
                'code'=>1,
                'msg'=>'error:'.$reponse['error'].',description:'.$reponse['error_description'],
                'data'=>$reponse,
            ];
        }
        return [
            'code'=>0,
            'msg'=>'ok',
            'data'=>$reponse,
        ];
    }

    /**
     * 获取智能小程序提供的 unionid
     * @param string $openid
     * @return array
     */
    public function getUnioid(string $openid){
        $configs = $this->configs;
        $tokenData = $this->getAccessToken();
        $url = "/rest/2.0/smartapp/getunionid?access_token={$tokenData['data']['access_token']}";
        $reponse = $this->POST($url,[
            'base_uri' => $this->BASE_URI,
            'json' => [
                'openid' => $openid,
            ],
        ]);
        $state = 0;
        $msg = 'ok';
        if($reponse['errno'] != 0){
            $state = $reponse['errno'];
            $msg = $reponse['errmsg'];
        }
        return [
            'code'=>$state,
            'msg'=>$msg,
            'data'=>$reponse['data'],
        ];
    }
}