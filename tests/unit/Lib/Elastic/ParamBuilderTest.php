<?php

namespace Lib\Elastic;


class ParamsBuilderTest extends \Codeception\TestCase\Test
{


    public function testParamsBuilderInstance()
    {
        $obj = new \Lib\Elastic\ParamsBuilder();

        $this->assertInstanceOf('\Lib\Elastic\ParamsBuilder', $obj);
    }


    public function testParamsBuilderCallAndGet()
    {
        $obj = new \Lib\Elastic\ParamsBuilder();
        $obj->AaaBbbC_c('value');

        $paramArray = $obj->get();
        // positive
        $this->assertNotEmpty($paramArray['aaa']);
        $this->assertNotEmpty($paramArray['aaa']['bbb']);
        $this->assertNotEmpty($paramArray['aaa']['bbb']['c_c']);
        $this->assertEquals('value', $paramArray['aaa']['bbb']['c_c']);

        $this->assertArraySubset(['aaa' => ['bbb' => ['c_c' => 'value']]], $paramArray);

        // negative
        $this->assertArrayNotHasKey('undefined', $paramArray);
        $this->assertArrayNotHasKey('bbb', $paramArray);
        $this->assertArrayNotHasKey('c_c', $paramArray);
    }


    public function testParamsBuilderTest_1()
    {
        $params = [
            'index' => 'my_index',
            'type'  => 'my_type',
            'body'  => 'my_body',
            'id'    => 'my_id'
        ];

        $obj = new \Lib\Elastic\ParamsBuilder();
        $obj->index('my_index')
            ->type('my_type')
            ->body('my_body')
            ->id('my_id');

        $this->assertArraySubset($params, $obj->get());
    }


    public function testParamsBuilderTest_2()
    {

        $params = [
            'index' => 'my_index',
            'type'  => 'my_type',
            'body'  => [
                'query' => [
                    'filtered' => [
                        'filter' => [
                            'term' => ['my_field' => 'abc']
                        ],
                        'query'  => [
                            'match' => ['my_other_field' => 'xyz']
                        ]
                    ]
                ]
            ]
        ];

        $obj = new \Lib\Elastic\ParamsBuilder();
        $obj->index('my_index')
            ->type('my_type')
            ->bodyQueryFilteredFilterTerm('my_field', 'abc')
            ->bodyQueryFilteredQueryMatch('my_other_field', 'xyz');;

        $this->assertArraySubset($params, $obj->get());
    }


    public function testParamsBuilderTest_3()
    {

        $params = [
            "index"       => "my_index",
            "search_type" => "scan",        // use search_type=scan
            "scroll"      => "30s",         // how long between scroll requests. should be small!
            "size"        => 50,            // how many results *per shard* you want back
            "body"        => [
                "query" => [
                    "match_all" => []
                ]
            ]
        ];

        $obj = new \Lib\Elastic\ParamsBuilder();
        $obj->index('my_index')
            ->search_type('scan')
            ->scroll('30s')
            ->size(50)
            ->index('my_index')
            ->bodyQueryMatch_all([]);

        $this->assertArraySubset($params, $obj->get());
    }


    public function testParamsBuilderTest_4()
    {

        $params = [
            'index' => 'my_index',
            'type'  => 'my_type',
            'body'  => [
                'query' => [
                    'bool' => [
                        'must' => [
                            ['match' => ['testField' => 'abc']],
                            ['match' => ['testField2' => 'xyz']],
                        ]
                    ]
                ]
            ]
        ];

        $obj = new \Lib\Elastic\ParamsBuilder();
        $obj->index('my_index')
            ->type('my_type')
            ->bodyQueryBoolMust(
                [
                    ['match' => ['testField' => 'abc']],
                    ['match' => ['testField2' => 'xyz']],
                ]
            );

        $this->assertArraySubset($params, $obj->get());
    }

}