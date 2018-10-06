<?php
/**
 * Facebook Operator operates over the result to return its siblings.
 *getOperator
 * @author  Ahmed Samir <ahmed.samir@queentechsolution.net>
 *
 */
namespace Skopenow\Result\Siblings\Operators;

use Skopenow\Result\Siblings\Operators\AbstractOperator;
use App\Models\ResultData;
use App\Models\SubResultData;

class FacebookOperator extends AbstractOperator
{

    protected $result;

    public function __construct(ResultData $result)
    {
        $this->result = $result;
    }

    public function getDefaultSiblings(): \Iterator
    {
        if ($this->result->getIsRelative() || !$this->result->getIsProfile()) {
            return new \ArrayIterator();
        }

        $mainUrl = $this->result->url;
        $suffixes = [
            20=>'about', 30=>'friends', 40=>'photos', 50=>'events', 60=>'likes', 70=>'sports', 80=>'music', 90=>'groups', 100=>'reviews'
        ];
        $data = new \ArrayIterator();
        $parentResult = $this->buildResult($mainUrl);

        $parentResult->type = 'result';
        $parentResult->parent_id = $this->result->id;
        $parentResult->rank = 0;
        $parentResult->is_parent = 1;
        $data->append($parentResult);
        foreach ($suffixes as $key => $suffix) {
            $url = $mainUrl.'/'.$suffix;
            $subResult = $this->buildResult($url);
            $subResult->type = $suffix;
            $subResult->parent_id = $this->result->id;
            $subResult->rank = $key;
            
            $data->append($subResult);
        }
        return $data;
    }
}
