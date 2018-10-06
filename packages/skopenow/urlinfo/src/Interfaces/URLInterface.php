<?php
namespace Skopenow\UrlInfo\Interfaces;

interface URLInterface
{
    public function normalize();
    public function getURL() : string;
    public function getNormalizedURL() : string;
}
