<?php
namespace Skopenow\HttpRequests\Interfaces;

interface ProxyDataInterface
{
    public function getProxyURL() :string;
    public function getCookiesPath() :string;
}
