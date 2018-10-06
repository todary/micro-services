<?php
namespace Skopenow\UrlInfo\Interfaces;

interface ProfileInfoInterface
{
    public function getProfileInfo(string $url, array $htmlContent);
}
