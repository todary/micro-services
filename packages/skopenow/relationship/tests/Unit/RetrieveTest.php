<?php

use App\Models\EntityDataPoint;
use App\Models\Relationship;
use App\Models\Result;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Skopenow\Relationship\Classes\Insert;
use Skopenow\Relationship\Classes\Retrieve;
use Skopenow\Relationship\Classes\Update;

class RetrieveTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Run the database migrations for the application.
     *
     * @return void
     */
    public function runDatabaseMigrations()
    {
        $this->artisan('migrate', ['--path' => 'packages/skopenow/relationship/database/migrations']);

        $this->beforeApplicationDestroyed(function () {
            $this->artisan('migrate:rollback', ['--path' => 'packages/skopenow/relationship/database/migrations']);
        });
    }

    public function setUp()
    {
        parent::setUp();
        $this->reportId = factory('App\Models\Report')->create()->id;
        config(['state.report_id' => $this->reportId]);
        $this->retrieve = new Retrieve;
        $this->insert = new Insert($this->retrieve, new Update($this->retrieve));
    }

    private function getParams($entity)
    {
        switch ($entity['type']) {
            case 'combination':
                return 'C';
                break;
            case 'datapoint':
                $entity->datapoint()->create();
                return 'D';
                break;
            case 'result':
                $entity->result()->create();
                return 'R';
                break;
        }
    }

    public function testGetRelationships()
    {
        $entities = factory('App\Models\Entity', 6)->create();
        for ($i = 0; $i < 6; $i++) {
            $params[$i]['type'] = $this->getParams($entities[$i]) . '2' . $this->getParams($entities[$i + 1]);
            $this->insert->setRelationshipWithIds($entities[$i]['id'], $entities[$i + 1]['id'], $params[$i]);
            $i++;
        }
        $data = $this->retrieve->getRelationships($entities->pluck('id')->toArray());

        $this->assertEquals(Result::count(), $data['results']->count());
        $this->assertEquals(EntityDataPoint::count(), $data['datapoints']->count());
        // dd(Relationship::all()->toArray(), /*$data, */Result::all(), EntityDataPoint::all());
    }
}
