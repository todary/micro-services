<?php
namespace Skopenow\Combinations\Transformers;

use Skopenow\Combinations\Models\CombinationLevel;

/**
*
*/
class CombinationLevelTransformer extends Transformer
{
    protected $includeCombination = false;

    public function transform(CombinationLevel $combinationLevel)
    {
        $data = [
            'id' => $combinationLevel->id,
            'report_id' => $combinationLevel->report_id,
            'combination_id' => $combinationLevel->comb_id,
            'level_no' => $combinationLevel->level_no,
            'source' => $combinationLevel->source,
            'data' => $combinationLevel->data,
            'is_completed' => $combinationLevel->is_completed,
            'start_time' => $combinationLevel->start_time,
            'end_time' => $combinationLevel->end_time,
            'exec_time' => $combinationLevel->exec_time,
            'log_stream' => $combinationLevel->log_stream,
        ];

        if ($this->includeCombination) {
            $data['combination'] = (new CombinationTransformer())->transform($combinationLevel->combination);
        }

        return $data;
    }

    public function includeCombination()
    {
        $this->includeCombination = true;
    }
}
