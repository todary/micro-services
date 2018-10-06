<?php
namespace Skopenow\Reports\CombinationCreators;

/**
*
*/
class Combinator
{
    protected $exchangableNodesGroups = [];
    protected $optionalNodesGroups = [];
    protected $eachGroups = [];
    protected $nodes = [];

    public function addNodes($key, $values)
    {
        foreach ($values as $value) {
            $this->nodes[] = ['key' => $key, 'value' => $value];
        }
        return $this;
    }

    public function addEachGroupNodes(string $key, array $values, $optional = false)
    {
        $nodes = [];
        foreach ($values as $value) {
            $nodes[] = ['key' => $key, 'value' => $value];
        }

        if ($optional) {
            $nodes[] = ['key' => null];
        }

        $this->eachGroups[] = $nodes;
        return $this;
    }

    public function addOptionalNodesGroup(string $key, array $values)
    {
        foreach ($values as $value) {
            $nodes[] = ['key' => $key, 'value' => $value];
        }

        $this->optionalNodesGroups[] = $nodes;
        return $this;
    }


    public function addExchangableNodesGroup(string $key, array $values, $optional = false)
    {
        foreach ($values as $value) {
            $nodes[] = ['key' => $key, 'value' => $value];
        }

        $this->exchangableNodesGroups[] = ['nodes' => $nodes, 'is_optional'=>$optional];
        return $this;
    }

    public function traverse()
    {
        $firstPath = new Combination();
        $firstPath = $this->resolveNodes($this->nodes, $firstPath);

        $pathes = [$firstPath];
        $pathes = $this->resolveOptionalNodesGroups($this->optionalNodesGroups, $pathes);
        $pathes = $this->resolveExchangableNodesGroups($this->exchangableNodesGroups, $pathes);
        $pathes = $this->resolveEachGroups($this->eachGroups, $pathes);

        return $pathes;
    }


    protected function resolveNodes($nodes, $combination)
    {
        if (count($nodes) > 0) {
            foreach ($nodes as $node) {
                //call function
                $combination = $this->resolve($node, $combination);
            }
        }
        return $combination;
    }

    protected function resolveOptionalNodesGroups($groups, $pathes)
    {
        if (count($groups) > 0) {
            foreach ($groups as $level) {
                $oldPathes = $pathes;
                $pathes = [];

                foreach ($oldPathes as $path) {
                    $pathes[] = clone $path;
                    $path = clone $path;
                    foreach ($level as $node) {
                        $path = $this->resolve($node, $path);
                    }
                    $pathes[] = $path;
                }
            }
        }
        return $pathes;
    }

    protected function resolveExchangableNodesGroups($groups, $pathes)
    {
        if (count($groups) > 0) {
            foreach ($groups as $group) {
                $nodes = $group['nodes'];
                $isOptional = $group['is_optional'];
                $nodesCount = count($nodes);
                foreach ($nodes as $i => $node) {
                    $oldPathes = $pathes;
                    $pathes = [];
                    $pathesCount = count($oldPathes);
                    foreach ($oldPathes as $j => $path) {
                        $pathes[] = $this->resolve($node, clone $path);

                        if (!$isOptional && $i == ($nodesCount-1) && $j == ($pathesCount-1)) {
                        } else {
                            $pathes[] = clone $path;
                        }
                    }
                }
            }
        }
        return $pathes;
    }

    protected function resolveEachGroups($groups, $pathes)
    {
        if (count($groups) > 0) {
            foreach ($groups as $level) {
                $oldPathes = $pathes;
                $pathes = [];

                foreach ($oldPathes as $path) {
                    foreach ($level as $node) {
                        $pathes[] = $this->resolve($node, clone $path);
                    }
                }
            }
        }

        return $pathes;
    }

    protected function resolve($node, $combination)
    {
        if (!$node['key']) {
            return $combination;
        }
        $combination->add($node['key'], $node['value']);
        return $combination;
        // if (!isset($data[$node['key']])) {
        //     $data[$node['key']] = [];
        // }

        // $data[$node['key']][] = $node['value'];
        // return $data;
    }
}
