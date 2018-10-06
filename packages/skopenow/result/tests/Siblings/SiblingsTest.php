<?php
namespace Skopenow\Result\Save;

use App\Models\ResultData;
use App\Models\SubResult;
use Illuminate\Support\Facades\Artisan;
use App\Libraries\DBCriteria;

class SiblingsTest extends \TestCase
{
    protected $result;

    public function setup()
    {
        $r = Artisan::call('migrate:refresh', ['--path' => 'packages/skopenow/result/src/database/migrations']);
        
        config(["state.report_id"=>1]);
        config(["state.combination_id"=>1]);

        $this->result = loadService("result");
    }

    public function testSaveSiblings()
    {
        $resultData = new ResultData("https://www.facebook.com/rob.douglas.7923");
        $resultData->id = 1;
        $resultData->setIsProfile(true);
        $output = $this->result->createDefaultSiblings($resultData);
        $this->assertTrue($output);

        $resultData = new ResultData("https://www.facebook.com/rob.douglas.7923");
        $resultData->id = 2;
        $resultData->setIsProfile(true);
        $output = $this->result->createDefaultSiblings($resultData);
        $this->assertTrue($output);
    }

    public function testUpdateSiblings()
    {
        $resultData = new ResultData("https://www.facebook.com/rob.douglas.7923");
        $resultData->id = 1;
        $resultData->setIsProfile(true);
        $output = $this->result->createDefaultSiblings($resultData);

        
        $data = ["is_deleted"=>1];
        
        $url = "https://www.facebook.com/rob.douglas.7923";
        $citeria = new DBCriteria();
        $citeria->compare("url", $url);
        $output = $this->result->updateSiblings($data, $citeria);
        $subResult = SubResult::find(1);
        $this->assertEquals(1, $subResult->is_deleted);
    }
}
