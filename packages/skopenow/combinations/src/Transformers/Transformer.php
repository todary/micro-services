<?php
namespace Skopenow\Combinations\Transformers;

/**
*
*/
abstract class Transformer
{
    public function transformAll($items)
    {
        $data = [];
        foreach ($items as $item) {
            $data[] = $this->transform($item);
        }

        return $data;
    }
}
