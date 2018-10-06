<?php
namespace Skopenow\UrlInfo\Interfaces;

interface SourceInterface
{
    /**
     * return the source name
     *
     * @return string $this->source source name
     */
    public function getSource() : string;

    /**
     * return the main source name
     *
     * @return string $this->mainSource main source name
     */
    public function getMainSource() : string;

    /**
     * Get an instance of URLInterface and extract the source and main source
     *
     * @param  URLInterface $url url to extract the source and main source from
     *
     * @return null
     */
    public function determineSource(URLInterface $url) : string;

    /**
     * Cjange the default source suffix
     *
     * @param string $suffix
     *
     * @return void
     */
    public function setSourceSuffix(string $suffix);
}
