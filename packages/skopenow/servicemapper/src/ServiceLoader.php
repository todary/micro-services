<?php

namespace Skopenow\ServiceMapper;

use Illuminate\Support\ServiceProvider;

class ServiceLoader
{
    public static function load($serviceName, $constructorParams = [])
    {
        $serviceFolder = strtolower($serviceName) ;
        if(stripos($serviceName, "service")){
            $serviceFolder = str_ireplace("service","",$serviceFolder);
        }
    	$servicePath = __DIR__ . '/../../' . $serviceFolder;
           
        require_once $servicePath . '/vendor/autoload.php';

        $serviceName = ucfirst($serviceName);        
        $class = "Skopenow\\$serviceName\\EntryPoint";
        $service = new $class(...$constructorParams);

        return $service;
    }
}
