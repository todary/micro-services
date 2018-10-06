<?php
namespace Skopenow\UrlInfo\Interfaces;

interface UsernameInterface
{
    /**
     * Get the Username from the given URL
     * @return string
     */
    public function getUsername();

    /**
     * Get the Facebook Profile URL
     * @param string   $url
     * @param int|null $personID
     *
     * @return string
     */
    public function getProfileLink(string $url, int $personID = null) : string;
}
