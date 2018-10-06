<?php
namespace Skopenow\Reports\CombinationCreators;

/**
*
*/
class Combination
{
    protected $data;

    public function __construct($data = [])
    {
        $this->data = $data;
    }

    public function add($key, $value)
    {
        if (!isset($this->data[$key])) {
            $this->data[$key] = [];
        }
        $this->data[$key][] = $value;
    }

    public function has($key)
    {
        if (array_key_exists($key, $this->data)) {
            if (!empty($this->data[$key])) {
                return true;
            }
        }
        return false;
    }

    public function exclude(array $keys) :Combination
    {
        $data = array_filter($this->data, function ($key) use ($keys) {
            return !in_array($key, $keys);
        }, ARRAY_FILTER_USE_KEY);
        return new Combination($data);
    }

    public function with(array $keys) :Combination
    {
        $data = array_filter($this->data, function ($key) use ($keys) {
            return in_array($key, $keys);
        }, ARRAY_FILTER_USE_KEY);
        return new Combination($data);
    }

    public function getData()
    {
        return $this->data;
    }
}
