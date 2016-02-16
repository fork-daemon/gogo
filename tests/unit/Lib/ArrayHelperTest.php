<?php

namespace Lib;

class ArrayHelperTest extends \Codeception\TestCase\Test
{

    protected $arrayData;
    protected $paths;

    protected function _before()
    {
        $this->arrayData = [
            '1' => [
                '1-1' => 'value-1-1',
                '1-2' => 'value-1-2',
            ],
            '2' => [
                '2-1' => [
                    '2-1-1' => 'value-2-1-1',
                ],
            ],
            '3' => 'value-3',
            'count' => ['x','y','z'],
        ];

        $this->paths = [
            '1',
            '1.1-1',
            '1.1-2',
            '2.2-1.2-1-1',
            '3',
            'count',
        ];

    }


    public function testArrayGetterSetterByPath()
    {
        $uniqueValue = uniqid();

        ArrayHelper::setByPath($this->arrayData, '2.2-1.2-1-1', $uniqueValue);
        $getValue = \Lib\ArrayHelper::getByPath($this->arrayData, '2.2-1.2-1-1');
        $this->assertEquals($uniqueValue , $getValue);

        $array = \Lib\ArrayHelper::getByPath($this->arrayData, '2.2-1');
        $this->assertInternalType('array', $array);
    }

    public function testArrayExistsByPath()
    {
        foreach ($this->paths as $path) {
            $exists = \Lib\ArrayHelper::existsByPath($this->arrayData, $path);
            $this->assertTrue($exists);

            $notExists = \Lib\ArrayHelper::existsByPath($this->arrayData, 'notExistsPath.' . $path);
            $this->assertFalse($notExists);
        }
    }

    public function testArrayUnsetByPath()
    {
        \Lib\ArrayHelper::unsetByPath($this->arrayData, '2.2-1.2-1-1');
        $nullValue = \Lib\ArrayHelper::getByPath($this->arrayData, '2.2-1.2-1-1');
        $this->assertEquals(null , $nullValue);
    }

    public function testArrayCountByPath()
    {
        $count = \Lib\ArrayHelper::countByPath($this->arrayData, 'count');
        $this->assertEquals(3 , $count);
    }


    public function testArrayPushPopShiftByPath()
    {
        $count = \Lib\ArrayHelper::countByPath($this->arrayData, 'count');
        $this->assertEquals(3 , $count);

        \Lib\ArrayHelper::pushByPath($this->arrayData, 'count' , 'superValue');
        $count = \Lib\ArrayHelper::countByPath($this->arrayData, 'count');
        $this->assertEquals(4 , $count);

        $value = \Lib\ArrayHelper::popByPath($this->arrayData, 'count');
        $count = \Lib\ArrayHelper::countByPath($this->arrayData, 'count');
        $this->assertEquals(3 , $count);
        $this->assertEquals('superValue' , $value);

        $value = \Lib\ArrayHelper::shiftByPath($this->arrayData, 'count');
        $count = \Lib\ArrayHelper::countByPath($this->arrayData, 'count');
        $this->assertEquals(2 , $count);
        $this->assertEquals('x' , $value);
    }

}