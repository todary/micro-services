<?php

use App\Models\Relationship;
use App\Models\RelationshipLinear;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Skopenow\Relationship\Classes\Insert;
use Skopenow\Relationship\Classes\Retrieve;
use Skopenow\Relationship\Classes\Update;

class InsertTest extends TestCase
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
        $retriever = new Retrieve;

        $this->insert = new Insert($retriever, new Update($retriever));
    }
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testInsertRelationshipUsingEntityIds()
    {
        $sourceEntity = factory('App\Models\Entity')->create()->id;
        $targetEntity = factory('App\Models\Entity')->create()->id;

        $params['type'] = 'D2R';
        $this->insert->setRelationshipWithIds($sourceEntity, $targetEntity, $params);
        $relationships = Relationship::all()->toArray();
        $relationshipsLinear = RelationshipLinear::all()->toArray();

        $expectedRelationship = [
            [
                'id' => 1,
                'report_id' => $this->reportId,
                'type' => $params['type'],
                'source_entity' => $sourceEntity,
                'target_entity' => $targetEntity,
                'reason' => '0',
            ],
        ];
        $this->assertEquals($expectedRelationship, $relationships);

        $expectedRelationshipLinear = [
            [
                'id' => 1,
                'report_id' => $this->reportId,
                'relationship_id' => $expectedRelationship[0]['id'],
                'first_party' => $sourceEntity,
                'second_party' => $targetEntity,
                'reason' => '0',
            ],
        ];
        $this->assertEquals($expectedRelationshipLinear, $relationshipsLinear);
    }

    public function testCreateRelationshipUsingEntityIdsWithTheSameEntity()
    {
        $sourceEntity = factory('App\Models\Entity')->create()->id;
        $params['type'] = 'D2R';
        $relationship = $this->insert->setRelationshipWithIds($sourceEntity, $sourceEntity, $params);
        $this->assertFalse($relationship);
    }

    public function testCreateRelationshipUsingEntityIdsWithNotExsitsEntity()
    {
        $sourceEntity = factory('App\Models\Entity')->create()->id;
        $params['type'] = 'D2R';
        $relationship = $this->insert->setRelationshipWithIds($sourceEntity, 2, $params);
        $this->assertNull($relationship);
    }

    private function getParams($entity)
    {
        switch ($entity['type']) {
            case 'combination':
                return 'C';
                break;
            case 'datapoint':
                return 'D';
                break;
            default:
                return 'R';
                break;
        }
    }

    public function testInsertLinearRelationship()
    {
        $entities = factory('App\Models\Entity', 6)->create()->toArray();
        for ($i = 0; $i < 6; $i++) {
            $params[$i]['type'] = $this->getParams($entities[$i]) . '2' . $this->getParams($entities[$i + 1]);
            $this->insert->setRelationshipWithIds($entities[$i]['id'], $entities[$i + 1]['id'], $params[$i]);
            $i++;
        }

        $expectedRelationship = [
            [
                "id" => 1,
                "report_id" => $this->reportId,
                "type" => $params[0]['type'],
                "source_entity" => "1",
                "target_entity" => "2",
                "reason" => "0",
            ],
            [
                "id" => 2,
                "report_id" => $this->reportId,
                "type" => $params[2]['type'],
                "source_entity" => "3",
                "target_entity" => "4",
                "reason" => "0",
            ],
            [
                "id" => 3,
                "report_id" => $this->reportId,
                "type" => $params[4]['type'],
                "source_entity" => "5",
                "target_entity" => "6",
                "reason" => "0",
            ],
        ];

        $relationships = Relationship::all()->toArray();
        $this->assertEquals($expectedRelationship, $relationships);

        $relationshipsLinear = RelationshipLinear::all()->toArray();

        $expectedRelationshipLinear = [
            [
                'id' => 1,
                'report_id' => '12555',
                'relationship_id' => '1',
                'first_party' => '1',
                'second_party' => '2',
                'reason' => '0',
            ],
            [
                'id' => 2,
                'report_id' => '12555',
                'relationship_id' => '2',
                'first_party' => '3',
                'second_party' => '4',
                'reason' => '0',
            ],
            [
                'id' => 3,
                'report_id' => '12555',
                'relationship_id' => '3',
                'first_party' => '5',
                'second_party' => '6',
                'reason' => '0',
            ],
        ];
        $this->assertEquals($expectedRelationshipLinear, $relationshipsLinear);

        $param['type'] = $this->getParams($entities[0]) . '2' . $this->getParams($entities[2]);
        $this->insert->setRelationshipWithIds(1, 3, $param);
        $expectedRelationshipLinear = array_merge($expectedRelationshipLinear, [
            [
                'id' => 4,
                'report_id' => '12555',
                'relationship_id' => '4',
                'first_party' => '1',
                'second_party' => '3',
                'reason' => '0',
            ],
            [
                'id' => 5,
                'report_id' => '12555',
                'relationship_id' => '4',
                'first_party' => '3',
                'second_party' => '2',
                'reason' => '0',
            ],
            [
                'id' => 6,
                'report_id' => '12555',
                'relationship_id' => '4',
                'first_party' => '1',
                'second_party' => '4',
                'reason' => '0',
            ],
        ]);

        $this->assertEquals($expectedRelationshipLinear, RelationshipLinear::all()->toArray());

        $param['type'] = $this->getParams($entities[1]) . '2' . $this->getParams($entities[3]);
        $this->insert->setRelationshipWithIds(2, 4, $param);
        $expectedRelationshipLinear = array_merge($expectedRelationshipLinear, [
            [
                'id' => 7,
                'report_id' => '12555',
                'relationship_id' => '5',
                'first_party' => '2',
                'second_party' => '4',
                'reason' => '0',
            ],
        ]);
        $this->assertEquals($expectedRelationshipLinear, RelationshipLinear::all()->toArray());

        // $param['type'] = $this->getParams($entities[0]) . '2' . $this->getParams($entities[5]);
        $param['type'] = 'C2R';
        $this->insert->setRelationshipWithIds(1, 6, $param);
        $expectedRelationshipLinear = array_merge($expectedRelationshipLinear, [
            [
                'id' => 8,
                'report_id' => '12555',
                'relationship_id' => '6',
                'first_party' => '1',
                'second_party' => '6',
                'reason' => '0',
            ],
            /*[
                'id' => 9,
                'report_id' => '12555',
                'relationship_id' => '6',
                'first_party' => '6',
                'second_party' => '2',
                'reason' => '0',
            ],
            [
                'id' => 10,
                'report_id' => '12555',
                'relationship_id' => '6',
                'first_party' => '6',
                'second_party' => '3',
                'reason' => '0',
            ],
            [
                'id' => 11,
                'report_id' => '12555',
                'relationship_id' => '6',
                'first_party' => '6',
                'second_party' => '4',
                'reason' => '0',
            ],*/
            [
                'id' => 9,
                'report_id' => '12555',
                'relationship_id' => '6',
                'first_party' => '1',
                'second_party' => '5',
                'reason' => '0',
            ],
        ]);
        $this->assertEquals($expectedRelationshipLinear, RelationshipLinear::all()->toArray());
    }

    public function testInsertRelationshipIfSourceIsList()
    {
        $sourceEntity = factory('App\Models\Entity')->create();
        $targetEntity = factory('App\Models\Entity')->create();

        $params['type'] = 'D2R';
        $params['source'] = 'list';
        $this->insert->setRelationship($sourceEntity, $targetEntity, $params);

        $relationships = Relationship::all()->toArray();
        $expectedRelationship = [
            [
                'id' => 1,
                'report_id' => $this->reportId,
                'type' => $params['type'],
                'source_entity' => $sourceEntity->id,
                'target_entity' => $targetEntity->id,
                'reason' => '1',
            ],
        ];
        $this->assertEquals($expectedRelationship, $relationships);

        $relationshipsLinear = RelationshipLinear::all()->toArray();
        $expectedRelationshipLinear = [
            [
                'id' => 1,
                'report_id' => $this->reportId,
                'relationship_id' => $expectedRelationship[0]['id'],
                'first_party' => $sourceEntity,
                'second_party' => $targetEntity,
                'reason' => '0',
            ],
        ];
        $this->assertEquals([], $relationshipsLinear);
    }

    public function testInsertRelationshipWithFlags()
    {
        $sourceEntity = factory('App\Models\Entity')->create();
        $targetEntity = factory('App\Models\Entity')->create();

        $params['type'] = 'D2R';
        $params['reason_flags'] = 1;
        $params['is_relative'] = 1;
        $this->insert->setRelationship($sourceEntity, $targetEntity, $params);

        $relationships = Relationship::all()->toArray();
        $expectedRelationship = [
            [
                'id' => 1,
                'report_id' => $this->reportId,
                'type' => $params['type'],
                'source_entity' => $sourceEntity->id,
                'target_entity' => $targetEntity->id,
                'reason' => '65',
            ],
        ];
        $this->assertEquals($expectedRelationship, $relationships);

        $relationshipsLinear = RelationshipLinear::all()->toArray();
        $expectedRelationshipLinear = [
            [
                'id' => 1,
                'report_id' => $this->reportId,
                'relationship_id' => $expectedRelationship[0]['id'],
                'first_party' => $sourceEntity->id,
                'second_party' => $targetEntity->id,
                'reason' => '65',
            ],
        ];
        $this->assertEquals($expectedRelationshipLinear, $relationshipsLinear);
    }

    public function testInsertRelationshipWithIsRelativeFlag()
    {
        $sourceEntity = factory('App\Models\Entity')->create();
        $targetEntity = factory('App\Models\Entity')->create();

        $params['type'] = 'D2R';
        $params['is_relative'] = 1;
        $this->insert->setRelationship($sourceEntity, $targetEntity, $params);

        $relationships = Relationship::all()->toArray();
        $expectedRelationship = [
            [
                'id' => 1,
                'report_id' => $this->reportId,
                'type' => $params['type'],
                'source_entity' => $sourceEntity->id,
                'target_entity' => $targetEntity->id,
                'reason' => '64',
            ],
        ];
        $this->assertEquals($expectedRelationship, $relationships);

        $relationshipsLinear = RelationshipLinear::all()->toArray();
        $expectedRelationshipLinear = [
            [
                'id' => 1,
                'report_id' => $this->reportId,
                'relationship_id' => $expectedRelationship[0]['id'],
                'first_party' => $sourceEntity->id,
                'second_party' => $targetEntity->id,
                'reason' => '64',
            ],
        ];
        $this->assertEquals($expectedRelationshipLinear, $relationshipsLinear);
    }

    public function testInsertDuplicateRelationship()
    {
        $sourceEntity = factory('App\Models\Entity')->create();
        $targetEntity = factory('App\Models\Entity')->create();

        $params['type'] = 'D2R';
        $this->insert->setRelationship($sourceEntity, $targetEntity, $params);

        $relationships = Relationship::all()->toArray();
        $expectedRelationship = [
            [
                'id' => 1,
                'report_id' => $this->reportId,
                'type' => $params['type'],
                'source_entity' => $sourceEntity->id,
                'target_entity' => $targetEntity->id,
                'reason' => '0',
            ],
        ];
        $this->assertEquals($expectedRelationship, $relationships);

        $relationshipsLinear = RelationshipLinear::all()->toArray();
        $expectedRelationshipLinear = [
            [
                'id' => 1,
                'report_id' => $this->reportId,
                'relationship_id' => $expectedRelationship[0]['id'],
                'first_party' => $sourceEntity->id,
                'second_party' => $targetEntity->id,
                'reason' => '0',
            ],
        ];
        $this->assertEquals($expectedRelationshipLinear, $relationshipsLinear);

        $params['reason'] = 65;
        $this->insert->setRelationship($sourceEntity, $targetEntity, $params);

        $relationships = Relationship::all()->toArray();
        $expectedRelationship = [
            [
                'id' => 1,
                'report_id' => $this->reportId,
                'type' => $params['type'],
                'source_entity' => $sourceEntity->id,
                'target_entity' => $targetEntity->id,
                'reason' => '65',
            ],
        ];
        $this->assertEquals($expectedRelationship, $relationships);

        $relationshipsLinear = RelationshipLinear::all()->toArray();
        $expectedRelationshipLinear = [
            [
                'id' => 1,
                'report_id' => $this->reportId,
                'relationship_id' => $expectedRelationship[0]['id'],
                'first_party' => $sourceEntity->id,
                'second_party' => $targetEntity->id,
                'reason' => '0',
            ],
        ];
        $this->assertEquals($expectedRelationshipLinear, $relationshipsLinear);
    }
}
