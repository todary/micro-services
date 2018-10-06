<?php

namespace Skopenow\Search\Models;

use Skopenow\Search\Models\SearchResultInterface;
use App\DataTypes\DataTypeInterface;

interface SearchListInterface
{
    public function __construct(string $source);

    public function addResult(SearchResultInterface $result);

    public function addDataPoint(string $type, DataTypeInterface $dataPoint);

    /**
     * @return string
     */
    public function getSource(): string;

    /**
     * @return string
     */
    public function getUrl(): string;

    /**
     * @param string $url
     */
    public function setUrl(string $url);

    /**
     * @return string
     */
    public function getScreenshotUrl(): string;

    /**
     * @param string $screenshotUrl
     */
    public function setScreenshotUrl(string $screenshotUrl);

    /**
     * @return array
     */
    public function getDataPoints(): array;

    /**
     * @return \Iterator
     */
    public function getResults(): \Iterator;

    /**
     * @return int
     */
    public function getAccountUsed(): int;

    /**
     * @param int $accountUsed
     */
    public function setAccountUsed(int $accountUsed);
}
