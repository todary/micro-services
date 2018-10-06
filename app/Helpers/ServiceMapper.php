<?php

/**
 * Load service from packages
 * @param type $serviceName Name of the server to load
  */
function loadService($serviceName, $constructorParams = [])
{
    return \Skopenow\ServiceMapper\ServiceLoader::load($serviceName, $constructorParams);
}
