<?php
namespace Skopenow\UrlInfo\Interfaces;

interface ProfileImageInterface
{
    /**
     * Get The profile Image from given profile URL
     *
     * @return string
     */

    public function getProfileImage() : string;
}
