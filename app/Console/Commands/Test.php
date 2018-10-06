<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Events\AfterResultSaveEvent;
use App\Events\AfterResultUpdateEvent;
use App\Models\ResultData; 
use App\DataTypes;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Commands';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        
        $resultDataModel = $this->getResultModel();
        $report_id = 59947;//config("state.report_id");
        config(["state.report_id" => $report_id]);

        $this->runManager(); return;
        // return $this->saveToPending($resultDataModel);
        return $this->getFromPending();

        event(new AfterResultSaveEvent($resultDataModel,$report_id));
    }

    public function runManager()
    {
        $criteria = new \Skopenow\Search\Models\Criteria;
        $criteria->full_name = 'Rob Douglas';

        $fetcher = new \Skopenow\Search\Fetching\Fetchers\InstagramFetcher($criteria);

        $manager = new \Skopenow\Search\Managing\Managers\InstagramManager($fetcher);
        $output = $manager->execute();
        dd($output);

    }

    public function resultService()
    {
        $resultService = loadService('result');
        config(['state.report_id' => 59947]);
        $result = $resultService->getResultByUrl("https://www.youtube.com/watch?v=GaeMhlFdTMU");
        dd($result);
    }

    public function saveToPending(ResultData $result)
    {
        config(['state.combination_level_id' => 458]);
        $resultService = loadService('result');
        $output = $resultService->saveToPending($result);

        return $output;
    }

    public function getFromPending()
    {
        config(['state.combination_level_id' => 458]);
        $resultService = loadService('result');
        $output = $resultService->getPendingResults(['combination_level_id' => 458]);

        return $output;
    }


    public function getResultModel()
    {
        $resultDataModel = new ResultData("http://facebook.com/ahmedsamir732");
        $resultDataModel->id = 20201038;
        $resultDataModel->addName(DataTypes\Name::create(['full_name' => "Ahmed Samir"],"facebook"));
        $resultDataModel->addLocation(DataTypes\Address::create(['full_address' => "Cairo, Egypt"],'facebook'));
        $resultDataModel->addExperience(
            DataTypes\Work::create([
                "image" =>  'https://scontent-cai1-1.xx.fbcdn.net/v/t1.0-1/cp0/e15/q65/p48x48/15726823_1224635487621300_6458907073889939898_n.png.jpg?efg=eyJpIjoidCJ9&oh=95a04dd1968c2c2dca6478f9ac753ae2&oe=5A4F70D6',
                "company"   =>  "Queen Tech Solutions",
                "position" => "Software engineer",
                "start_date" => "2016",
                "end_date" => "Present"
            ],'facebook')
        );
        $resultDataModel->addEducation(
            DataTypes\School::create([
                "image" => 'https://scontent-cai1-1.xx.fbcdn.net/v/t1.0-1/cp0/e15/q65/p48x48/14925760_1797469510528129_6009629485481689904_n.jpg?efg=eyJpIjoidCJ9&oh=6e7f3a811a0687aa06dafb522d9291c3&oe=5A4B4830',
                "school" => "faculty of computer and information science mansoura university",
                "name" => "College",
                "start_date" => "",
                "end_date" => "",
            ],'facebook')
        );
        $resultDataModel->addEducation(
            DataTypes\School::create([
                "image" => 'https://scontent-cai1-1.xx.fbcdn.net/v/t1.0-1/cp0/e15/q65/c14.0.48.48/p48x48/580846_10149999285985791_1565762244_n.png.jpg?efg=eyJpIjoidCJ9&oh=e69fefac602378ffc3e921553e8622fe&oe=5A4E600E',
                "school" => "Ahmed Hassan Elzayat",
                "name" => "High School",
                "start_date" => "",
                "end_date" => "",
            ],'facebook')
        );
        $resultDataModel->addAge(DataTypes\Age::create(['age' => '25'],'facebook'));
        $resultDataModel->addEmail(DataTypes\Email::create(['email' => "ahmedsamir732@gmail.com"],'facebook'));
        $resultDataModel->addPhone(DataTypes\Phone::create(['phone' => "01114966047"],'facebook'));
        $resultDataModel->setUsername(DataTypes\Username::create(['username' => "ahmedsamir732"],'facebook'));
        $resultDataModel->setFlags(262151);
        return $resultDataModel;
    }
}
