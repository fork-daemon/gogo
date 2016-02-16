<?php
namespace Lib\Elastic;

// Simple models >>> =============================================

use Codeception\Util\Stub;

class ElasticMockModel extends \Lib\Elastic\Model
{
    protected $index = 'test_index';
    protected $type = 'test_type';
    protected $timestamps = false;
}

class ElasticMockModelTimestamps extends \Lib\Elastic\Model
{
    protected $index = 'test_index';
    protected $type = 'test_type';
    protected $timestamps = true;
}

class ElasticMockModelSchema extends \Lib\Elastic\Model
{
    protected $index = 'test_index';
    protected $type = 'test_type';
    protected $schema
        = [
            'key-1' => true,
            'key-2' => true,
            'key-3' => true,
        ];
}

// Simple models <<< =============================================

class ModelTest extends \Codeception\TestCase\Test
{

    public function testModelCheckInstance()
    {
        $obj = new ElasticMockModel();

        $this->assertInstanceOf('\Lib\Elastic\Model', $obj);
        $this->assertNotNull($obj->getIndex());
        $this->assertNotNull($obj->getType());
        $this->assertNull($obj->getId());
        $this->assertFalse($obj->isStored());

    }

    public function testModelConstructor()
    {
        $obj = new ElasticMockModel(
            [
                'id'   => 'id-xxx',
                'data' => [
                    'key-1' => 'value-1',
                    'key-2' => 'value-2',
                ]]
        );

        $this->assertEquals('id-xxx', $obj->getId());
        $this->assertEquals('value-1', $obj->getData('key-1'));
        $this->assertEquals('value-2', $obj->getData('key-2'));
        $this->assertEquals('undefined', $obj->getData('key-undefined', 'undefined'));
    }

    public function testModelDataGetterSetter()
    {
        $obj = new ElasticMockModel(
            ['data' => [
                'key-1' => 'value-1',
            ]]
        );
        $obj->setData('key-2', 'value-2');
        $obj->setData('path.key', 'path-value');

        $this->assertEquals('value-1', $obj->getData('key-1'));
        $this->assertEquals('value-2', $obj->getData('key-2'));
        $this->assertEquals('path-value', $obj->getData('path.key'));
        $this->assertArraySubset(
            [
                'key-1' => 'value-1',
                'key-2' => 'value-2',
                'path'  => ['key' => 'path-value'],
            ], $obj->getData()
        );


    }

    public function testModelDataExistsDelete()
    {
        $obj = new ElasticMockModel(
            ['data' => [
                'path' => ['key' => 'path-value'],
            ]]
        );

        $this->assertTrue($obj->existsData('path'));
        $this->assertTrue($obj->existsData('path.key'));

        $obj->deleteData('path.key');
        $this->assertFalse($obj->existsData('path.key'));

        $obj->deleteData('path');
        $this->assertFalse($obj->existsData('path'));

        $this->assertEquals([], $obj->getData());
    }

    public function testElasticModelCurrentInstance()
    {
        $this->assertInstanceOf('\Lib\Elastic\Model', ElasticMockModel::getCurrentModel());
        $this->assertEquals('test_index', ElasticMockModel::getModelIndex());
        $this->assertEquals('test_type', ElasticMockModel::getModelType());
        $this->assertEquals(null, ElasticMockModel::getModelSchema());
    }

    public function testModelToArray()
    {

        $obj = new ElasticMockModel(
            [
                'id'   => 'id-xxx',
                'info' => ['score' => 123],
                'data' => [
                    'key-1' => 'value-1',
                    'key-2' => 'value-2',
                ]]
        );

        $this->assertArraySubset(
            [
                ElasticMockModel::P_INDEX => 'test_index',
                ElasticMockModel::P_TYPE  => 'test_type',
                ElasticMockModel::P_ID    => 'id-xxx',
                ElasticMockModel::P_DATA  => [
                    'key-1' => 'value-1',
                    'key-2' => 'value-2',
                ],
                ElasticMockModel::P_INFO  => ['score' => 123],
            ], $obj->toArray()
        );

    }


    public function testModelMagicMethods()
    {
        $obj = new ElasticMockModel(['id' => 'id-xxx', 'info' => ['score' => 123]]);

        // magic: get / set
        $obj->bbb = 'value-bbb';
        $this->assertEquals('value-bbb', $obj->bbb);

        $obj->{'aaa'} = 'value-aaa';
        $this->assertEquals('value-aaa', $obj->{'aaa'});

        $obj->{'xxx.yyy'} = 'value-zzz';
        $this->assertEquals('value-zzz', $obj->{'xxx.yyy'});

        // magic: clone
        $testData = [
            'bbb' => 'value-bbb',
            'aaa' => 'value-aaa',
            'xxx' => [
                'yyy' => 'value-zzz'
            ],
        ];

        $this->assertNotNull($obj->getId());
        $this->assertNotNull($obj->getInfo());
        $this->assertArraySubset($testData, $obj->getData());

        $clonedObject = clone $obj;

        $this->assertNull($clonedObject->getId());
        $this->assertNull($clonedObject->getInfo());
        $this->assertArraySubset($testData, $clonedObject->getData());
    }

    // = TIMESTAMPS ==============================

    public function testModelGetCreateTimestamp()
    {
        $obj = new ElasticMockModelTimestamps();

        // because we have not saved it yet
        $this->assertNull($obj->getUpdateTimestamp());
    }


    public function testModelGetUpdateTimestamp()
    {
        $obj = new ElasticMockModelTimestamps();

        // because we have not saved it yet
        $this->assertNull($obj->getUpdateTimestamp());
    }

    public function testModelExpectedExceptionCreateTimestamp()
    {
        $this->setExpectedException('\Lib\Elastic\Exception\ModelException');

        // because our model without timestamps
        $obj = new ElasticMockModel();
        $obj->getCreateTimestamp();
    }


    public function testModelExpectedExceptionUpdateTimestamp()
    {
        $this->setExpectedException('\Lib\Elastic\Exception\ModelException');

        // because our model without timestamps
        $obj = new ElasticMockModel();
        $obj->getUpdateTimestamp();
    }


    // = SCHEMA ==============================

    public function testModelSchemaSetSuccess()
    {
        // key exists in schema
        $obj = new ElasticMockModelSchema();
        $obj->setData('key-2', 'value-2');
        $this->assertEquals('value-2', $obj->getData('key-2'));
    }

    public function testModelExpectedExceptionSchema()
    {
        $this->setExpectedException('\Lib\Elastic\Exception\ModelException');

        // key does not exist in schema
        $obj = new ElasticMockModelSchema();
        $obj->setData('undefined.key', 'value');
    }


    // =============================================

    protected function prepareStubElasticClient($model, $data)
    {

        /** @var $elasticSearchClient \Elasticsearch\Client */

        $elasticSearchClient = Stub::make(
            '\Elasticsearch\Client', [
                'index' => function ($params) use ($data) {
                    return [
                        "_index"   => $params['index'],
                        "_type"    => $params['type'],
                        "_id"      => $params['id'],
                        "_version" => 2,
                        "created"  => null, // null - update OR true - create
                    ];

                },
                'delete' => function ($params) {
                    return [
                        '_id' => $params['id']
                        // TODO: complete this
                    ];
                },
                'get'    => function ($params) use ($data) {
                    return [
                        '_id'     => $params['id'],
                        '_source' => $data,
                    ];
                },
                'count'  => function ($params) {
                    return [
                        'count'   => 999,
                        '_shards' => [
                            'total'      => 5,
                            'successful' => 5,
                            'failed'     => 0,
                        ]
                    ];
                },
                'search' => function ($params) use ($data) {
                    return [
                        'hits' => [
                            'hits' => [
                                [
                                    '_id'     => 'id-1',
                                    '_source' => $data
                                ],
                                [
                                    '_id'     => 'id-2',
                                    '_source' => $data
                                ],
                            ]
                        ]
                    ];
                }
            ]
        );

        $client = new \Lib\Elastic\Client();
        $client->setElasticClient($elasticSearchClient);

        $model::setClient($client);
    }


    public function testModelSearchOne()
    {
        // mock ===========================
        $data = ['key-1' => 'value-1', 'key-2' => 'value-2',];
        $this->prepareStubElasticClient('\Lib\Elastic\ElasticMockModel', $data);

        // test ===========================

        $one = ElasticMockModel::searchOne(123);
        $two = ElasticMockModel::searchOne('123-xxx');

        $this->assertInstanceOf('\Lib\Elastic\ElasticMockModel', $one);
        $this->assertInstanceOf('\Lib\Elastic\ElasticMockModel', $two);

        $this->assertEquals(123, $one->getId());
        $this->assertEquals('123-xxx', $two->getId());

        $this->assertArraySubset($data, $one->getData());
        $this->assertArraySubset($data, $two->getData());

    }


    public function testModelGetById()
    {

        // mock ===========================
        $data = ['key-1' => 'value-1', 'key-2' => 'value-2',];
        $this->prepareStubElasticClient('\Lib\Elastic\ElasticMockModel', $data);

        // test ===========================

        $one = ElasticMockModel::getById(123);

        $this->assertInstanceOf('\Lib\Elastic\ElasticMockModel', $one);
        $this->assertEquals(123, $one->getId());
        $this->assertArraySubset($data, $one->getData());

    }


    public function testModelSearch()
    {

        // mock ===========================
        $data = ['key-1' => 'value-1', 'key-2' => 'value-2',];
        $this->prepareStubElasticClient('\Lib\Elastic\ElasticMockModel', $data);

        // test ===========================

        $all = ElasticMockModel::search();
        $this->assertInternalType('array', $all);

        $this->assertInstanceOf('\Lib\Elastic\ElasticMockModel', $all[0]);
        $this->assertInstanceOf('\Lib\Elastic\ElasticMockModel', $all[1]);

        $this->assertArraySubset($data, $all[0]->getData());
        $this->assertArraySubset($data, $all[1]->getData());

    }


    public function testModelCount()
    {

        // mock ===========================
        $data = ['key-1' => 'value-1', 'key-2' => 'value-2',];
        $this->prepareStubElasticClient('\Lib\Elastic\ElasticMockModel', $data);

        // test ===========================
        $this->assertEquals(999, ElasticMockModel::count());
    }


    public function testModelSave()
    {

        // mock ===========================
        $data = ['key-1' => 'value-1', 'key-2' => 'value-2',];
        $this->prepareStubElasticClient('\Lib\Elastic\ElasticMockModel', $data);

        // test ===========================

        $item = new ElasticMockModel(['data' => $data]);
        $item->save();

    }

    public function testModelDestroy()
    {

        // mock ===========================
        $data = ['key-1' => 'value-1', 'key-2' => 'value-2',];
        $this->prepareStubElasticClient('\Lib\Elastic\ElasticMockModel', $data);

        // test ===========================

        $item = new ElasticMockModel(
            [
                'id'   => 'already-saved',
                'data' => $data
            ]
        );

        $this->assertEquals('already-saved', $item->getId());
        $item->destroy();
        $this->assertEquals(null, $item->getId());

    }


}