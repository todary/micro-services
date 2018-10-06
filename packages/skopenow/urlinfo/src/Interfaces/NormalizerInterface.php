<?php
namespace Skopenow\UrlInfo\Interfaces;

interface NormalizerInterface
{
    public function normalize(string $url) : string;
}
