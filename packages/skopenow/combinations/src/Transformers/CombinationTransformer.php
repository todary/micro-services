<?php
namespace Skopenow\Combinations\Transformers;

use Skopenow\Combinations\Models\Combination;

/**
*
*/
class CombinationTransformer extends Transformer
{
    protected $includeCombinationLevels = false;

    public function transform(Combination $combination)
    {
        $data = [
            'id' => $combination->id,
            'report_id' => $combination->report_id,
            'source_id' => $combination->source_id,
            'unique_name' => $combination->unique_name,
            'big_city' => $combination->big_city,
            'is_generated' => $combination->is_generated,
            'additional' => $combination->additional,
            'username' => $combination->username,
            'extra_data' => $combination->extra_data
        ];

        if ($this->includeCombinationLevels) {
            $data['levels'] = (new CombinationLevelTransformer())->transformAll($combination->levels);
        }

        return $data;
    }

    public function includeCombinationLevels()
    {
        $this->includeCombinationLevels = true;
    }
}
