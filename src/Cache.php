<?php

namespace EasyApplets;

use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;
use Phpfastcache\Drivers\Files\Config;
use Phpfastcache\Drivers\Predis\Config as PredisConfig;
use Phpfastcache\Helper\Psr16Adapter;

class Cache{

    private $Psr16Adapter = null;

    /**
     * 构造函数
     */
    public function __construct(string $defaultDriver,Array $cacheConfig)
    {
        CacheManager::setDefaultConfig(new Config([
            'secureFileManipulation'=>false,
            'defaultFileNameHashFunction'=>function(){
                return 'caches';
            },
            'htaccess'=>false,
            'securityKey'=>'',
        ]));

        $config = new Config(['path'=>$cacheConfig['path']]);
        if(isset($cacheConfig['predis'])){
            $prefix = $cacheConfig['predis']['prefix'];
            unset($cacheConfig['predis']['prefix']);
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