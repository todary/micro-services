<?php

namespace Skopenow\Search\Models;

use Skopenow\Search\Models\SearchResultInterface;
use App\DataTypes\DataTypeInterface;

class SearchList implements SearchListInterface
{
    protected $source = null;
    protected $url = "";
    protected $screenshotUrl = "";
    protected $availableResultsCount = null;
    protected $results = null;
    protected $dataPoints = [];
    protected $accountUsed = null;

    public function __construct(string $source)
    {
        $this->source = $source;
        $this->results = new \ArrayIterator();
    }

    public function addResult(SearchResultInterface $result)
    {
        $result->searchList = $this;
        $this->results->append($result);
    }

    public function addDataPoint(string $type, DataTypeInterface $dataPoint)
    {
        if (!isset($this->dataPoints[$type])) {
            $this->dataPoints[$type] = new \ArrayIterator();
        }

        $this->dataPoints[$type]->append($dataPoint);
    }

    /**
     * @return string
     */
    public function getSource(): string
    {
        return $this->source;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return self
     */
    public function setUrl(string $url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getScreenshotUrl(): string
    {
        return $this->screenshotUrl;
    }

    /**
     * @param string $screenshotUrl
     *
     * @return self
     */
    public function setScreenshotUrl(string $screenshotUrl)
    {
        $this->screenshotUrl = $screenshotUrl;

        return $this;
    }

    /**
     * @return array
     */
    public function getDataPoints(): array
    {
        return $this->dataPoints;
    }

    /**
     * @return \Iterator
     */
    public function getResults(): \Iterator
    {
        return $this->results;
    }

    /**
     * @return int
     */
    public function getAccountUsed(): int
    {
        return $this->accountUsed;
    }

    /**
     * @param int $accountUsed
     *
     * @return self
     */
    public function setAccountUsed(int $accountUsed)
    {
        $this->accountUsed = $accountUsed;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAvailableResultsCount()
    {
        return $this->availableResultsCount;
    }

    /**
     * @param int $availableResultsCount
     *
     * @return self
     */
    public function setAvailableResultsCount(int $availableResultsCount)
    {
        $this->availableResultsCount = $availableResultsCount;

        return $this;
    }
}
