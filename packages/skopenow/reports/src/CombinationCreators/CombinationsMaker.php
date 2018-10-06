<?php
namespace Skopenow\Reports\CombinationCreators;

use Skopenow\Reports\Models\Report;

/**
*
*/
class CombinationsMaker
{
    protected $tree;
    protected $data = [];

    public function __construct()
    {
        $this->tree = new Combinator();
    }

    public function set($type, $values, $key = null)
    {
        if (!$key) {
            $key = $type;
        }
        $this->data[$key] = ['type' => $type, 'values' => $values];
    }

    public function getByKey($key)
    {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }
    }

    public function with($keys, bool $optional = false)
    {
        foreach ($keys as $key) {
            $data = $this->getByKey($key);
            if (!$data) {
                continue;
            }
            if ($optional) {
                $this->tree->addOptionalNodesGroup($data['type'], $data['values']);
            } else {
                $this->tree->addNodes($data['type'], $data['values']);
            }
        }
        return $this;
    }

    public function withEach($keys, bool $optional = false)
    {
        foreach ($keys as $key) {
            $data = $this->getByKey($key);
            if (!$data) {
                continue;
            }
            $this->tree->addEachGroupNodes($data['type'], $data['values'], $optional);
        }
        return $this;
    }

    public function withEachTogether($keys, bool $optional = false)
    {
        foreach ($keys as $key) {
            $data = $this->getByKey($key);
            if (!$data) {
                continue;
            }
            $this->tree->addExchangableNodesGroup($data['type'], $data['values'], $optional);
        }
        return $this;
    }

    public function get()
    {
        $pathes = $this->tree->traverse();
        $this->tree = new Combinator();
        return $pathes;
    }

}
