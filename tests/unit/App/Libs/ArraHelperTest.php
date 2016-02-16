<?php
namespace App\Libs;


class ArraHelperTest extends \Codeception\TestCase\Test
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

        \App\Libs\ArrayHelper::setByPath($this->arrayData, '2.2-1.2-1-1', $uniqueValue);
        $getValue = \App\Libs\ArrayHelper::getByPath($this->arrayData, '2.2-1.2-1-1');
        $this->assertEquals($uniqueValue , $getValue);

        $array = \App\Libs\ArrayHelper::getByPath($this->arrayData, '2.2-1');
        $this->assertInternalType('array', $array);
    }

    public function testArrayExistsByPath()
    {
        foreach ($this->paths as $path) {
            $exists = \App\Libs\ArrayHelper::existsByPath($this->arrayData, $path);
            $this->assertTrue($exists);

            $notExists = \App\Libs\ArrayHelper::existsByPath($this->arrayData, 'notExistsPath.' . $path);
            $this->assertFalse($notExists);
        }
    }

    public function testArrayUnsetByPath()
    {
        \App\Libs\ArrayHelper::unsetByPath($this->arrayData, '2.2-1.2-1-1');
        $nullValue = \App\Libs\ArrayHelper::getByPath($this->arrayData, '2.2-1.2-1-1');
        $this->assertEquals(null , $nullValue);
    }

    public function testArrayCountByPath()
    {
        $count = \App\Libs\ArrayHelper::countByPath($this->arrayData, 'count');
        $this->assertEquals(3 , $count);
    }


    public function testArrayPushPopShiftByPath()
    {
        $count = \App\Libs\ArrayHelper::countByPath($this->arrayData, 'count');
        $this->assertEquals(3 , $count);

        \App\Libs\ArrayHelper::pushByPath($this->arrayData, 'count' , 'superValue');
        $count = \App\Libs\ArrayHelper::countByPath($this->arrayData, 'count');
        $this->assertEquals(4 , $count);

        $value = \App\Libs\ArrayHelper::popByPath($this->arrayData, 'count');
        $count = \App\Libs\ArrayHelper::countByPath($this->arrayData, 'count');
        $this->assertEquals(3 , $count);
        $this->assertEquals('superValue' , $value);

        $value = \App\Libs\ArrayHelper::shiftByPath($this->arrayData, 'count');
        $count = \App\Libs\ArrayHelper::countByPath($this->arrayData, 'count');
        $this->assertEquals(2 , $count);
        $this->assertEquals('x' , $value);
    }

}