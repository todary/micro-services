<?php
/**
 * Created by PhpStorm.
 * User: todary
 * Date: 22/10/17
 * Time: 12:39 م
 */

namespace Skopenow\Api\RequestFactory;
/**
 * Class FactoryMethod
 * @package Skopenow\Api\requestFactory
 */
abstract class FactoryMethod
{
    abstract protected function createRequest(string $path, string $method, array $headers, $data);

    public function create(string $path, string $method, array $headers, $data)
    {
        $object = $this->createRequest($path, $method, $headers, $data);
        return $object;
    }

}