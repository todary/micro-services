<?php
namespace Skopenow\Reports\CombinationGenerators;

use Skopenow\Reports\CombinationCreators\CombinationsMaker;
use Skopenow\Reports\CombinationGenerators\Generators\{
    LinkedinCombinationsGenerator,
    GoogleCombinationsGenerator,
    DefaultCombinationsGenerator,
    UpdatedDatapointCombinationsGenerator,
    DatapointCombinationsGenerator,
    FacebookCombinationsGenerator,
    GoogleplusCombinationsGenerator,
    CommonSourcesCombinationsGenerator,
    YellowpagesCombinationsGenerator,
    WhoisCombinationsGenerator,
    MainResultCombinationsGenerator,
    PeopleDataCombinationsGenerator
};

/**
*
*/
class CombinationsGeneratorsFactory
{
    public function __construct()
    {
        $this->combinationsMaker = new CombinationsMaker();
        $this->combinationsService = loadService('combinations');
    }

    public function make($source, $data = null)
    {
        \Log::info('BRAIN: FACTORY ' . $source);
        switch ($source) {
            case 'linkedin':
                \Log::info('BRAIN: LinkedinCombinationsGenerator');
                $generator = new LinkedinCombinationsGenerator($this->combinationsMaker, $this->combinationsService);
                break;

            case 'google':
                \Log::info('BRAIN: GoogleCombinationsGenerator');
                $generator = new GoogleCombinationsGenerator(
                    $this->combinationsMaker,
                    $this->combinationsService
                );
                $generator->setData($data);
                break;

            case 'yellowpages':
                \Log::info('BRAIN: YellowpagesCombinationsGenerator');
                $generator = new YellowpagesCombinationsGenerator(
                    $this->combinationsMaker,
                    $this->combinationsService
                );
                $generator->setData($data);
                break;

            case 'commonCombinations':
                \Log::info('BRAIN: CommonSourcesCombinationsGenerator');
                $generator = new CommonSourcesCombinationsGenerator(
                    $this->combinationsMaker,
                    $this->combinationsService
                );
                $generator->setData($data);
                break;

            case 'peopledata':
                \Log::info('BRAIN: PeopleDataCombinationsGenerator');
                $generator = new PeopleDataCombinationsGenerator(
                    $this->combinationsMaker,
                    $this->combinationsService
                );
                $generator->setData($data);
                break;

            case 'facebook':
                \Log::info('BRAIN: FacebookCombinationsGenerator');
                $generator = new FacebookCombinationsGenerator(
                    $this->combinationsMaker,
                    $this->combinationsService
                );
                $generator->setData($data);
                break;

            case 'googleplus':
                \Log::info('BRAIN: GoogleplusCombinationsGenerator');
                $generator = new GoogleplusCombinationsGenerator(
                    $this->combinationsMaker,
                    $this->combinationsService
                );
                $generator->setData($data);
                break;

            case 'websites':
                \Log::info('BRAIN: WhoisCombinationsGenerator');
                $generator = new WhoisCombinationsGenerator(
                    $this->combinationsMaker,
                    $this->combinationsService
                );
                $generator->setData($data);
                break;

            case 'datapoint':
                \Log::info('BRAIN: DatapointCombinationsGenerator');
                $generator = new DatapointCombinationsGenerator(
                    $this->combinationsMaker,
                    $this->combinationsService
                );
                $generator->setData($data);
                break;

            case 'updatedDatapoint':
                \Log::info('BRAIN: UpdatedDatapointCombinationsGenerator');
                $generator = new UpdatedDatapointCombinationsGenerator(
                    $this->combinationsMaker,
                    $this->combinationsService
                );
                $generator->setData($data);
                break;

            case 'mainResult':
                \Log::info('BRAIN: MainResultCombinationsGenerator');
                $generator = new MainResultCombinationsGenerator(
                    $this->combinationsMaker,
                    $this->combinationsService
                );
                $generator->setData($data);
                break;

            default:
                $generator = new DefaultCombinationsGenerator($this->combinationsMaker, $this->combinationsService);
                $generator->setSource($source);
        }
        return $generator;
    }
}
