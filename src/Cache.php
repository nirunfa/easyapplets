<?php

namespace EasyApplets;

use Phpfastcache\CacheManager;
use Phpfastcache\Drivers\Files\Config;
use Phpfastcache\Drivers\Predis\Config as PredisConfig;
use Phpfastcache\Helper\Psr16Adapter;

//cachemanager 缓存管理设置默认配置
CacheManager::setDefaultConfig(new Config());

class Cache{

    private $Psr16Adapter = null;

    /**
     * 构造函数
     */
    public function __construct(string $defaultDriver,Array $cacheConfig)
    {
        $config = new Config(['path'=>$cacheConfig['path'],'secureFileManipulation'=>false,'htaccess'=>false,'securityKey'=>'']);
        $config->setDefaultFileNameHashFunction(function(){
            return 'caches';
        });
        $config->setDefaultKeyHashFunction(function(){
            return date('Ymd');
        });
        if(isset($cacheConfig['predis'])){
            $prefix = $cacheConfig['predis']['prefix'];
            unset($cacheConfig['predis']['prefix']);
            $cacheConfig['predis']['port'] = intval($cacheConfig['predis']['port']);
            $cacheConfig['predis']['database'] = intval($cacheConfig['predis']['database']);
            $config = new PredisConfig($cacheConfig['predis']);
            $config->setOptPrefix($prefix);
        }
        $this->Psr16Adapter = new Psr16Adapter($defaultDriver, $config);
    }

    public function get($key,$default=''){
        return $this->Psr16Adapter->get($key,$default);
    }

    public function set($key,$val,$ttl=null){
        return $this->Psr16Adapter->set($key,$val,$ttl);
    }

    public function delete($key){
        return $this->Psr16Adapter->delete($key);
    }

    public function clear(){
        return $this->Psr16Adapter->clear();
    }

}