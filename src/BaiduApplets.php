<?php

namespace EasyApplets;

use EasyApplets\BaiduPrograms\Oauth as BaiduProgramsOauth;
use GuzzleHttp\Client;

/**
 * 百度小程序app
 */
class BaiduApplets implements BaseApplets {

    use BaiduProgramsOauth;

    private $configs = [];
    private $cache = null;

    /*
    * 构造函数
    */
    public function __construct($configs)
    {
        $this->configs=$configs;

        $CacheDriver = 'Files';
        $CacheConfigs = [];
        if(isset($configs['redis'])){
            $CacheDriver='predis';
            $redisConfig=$configs['redis'];
            $redisConfig['prefix']='easyapplets:';
            $CacheConfigs['predis']=$redisConfig;
        }
        
        $CacheConfigs['path'] = getcwd().'/eacaches/';
        if(isset($configs['cache-path'])){
            $CacheConfigs['path'] = $configs['cache-path'];
        }
        $this->cache = new Cache($CacheDriver,$CacheConfigs);
    }


    /**
     * GET请求
     * 
     * @param $url
     * @param $clientConfig 
     *         [redis配置=>[host|port|username|password|database],cache-path缓存配置=>string]
     */
    public function GET($url,$clientConfig): array
    {
        $client = new Client($clientConfig);
        $response = $client->get($url);
        $array = json_decode($response->getBody()->getContents(), true);
        return $array;
    }

    /**
     * GET请求
     * 
     * @param $url
     * @param $clientConfig
     */
    public function POST($url,$clientConfig): array
    {
        $client = new Client($clientConfig);
        $response = $client->post($url);
        $array = json_decode($response->getBody()->getContents(), true);
        return $array;
    }

    /**
     * GET请求
     * 
     * @param $url
     * @param $clientConfig
     */
    public function PUT($url,$clientConfig): array
    {
        $client = new Client($clientConfig);
        $response = $client->put($url);
        $array = json_decode($response->getBody()->getContents(), true);
        return $array;
    }

    public function DELETE($url,$clientConfig){

    }

}