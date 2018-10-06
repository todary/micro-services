<?php
namespace Skopenow\UrlInfo\Interfaces;

interface ProfileInterface
{
    /**
     * Check if the URL represents a Profile
     *
     * @param string $url
     *
     * @return bool
     */
    public function isProfile(string $url) : bool;

    /**
     * Check if the profile represents an existed profile
     *
     * @param string $url
     *
     * @return bool
     */
    public function profileExists(string $url) : bool;
}
