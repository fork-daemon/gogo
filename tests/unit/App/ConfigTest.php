<?php
namespace App;


class ConfigTest extends \Codeception\TestCase\Test
{

    protected function _before()
    {
        \App\Config::extend([
            'aaa' => 'aaa',
            'bbb' => 'bbb',
            'ccc' => [
                'ddd' => 'eee'
            ],
        ]);
    }

    protected function _after()
    {
        \App\Config::clear();
    }

    public function testConfigGetter()
    {
        $aaa = \App\Config::get('aaa');
        $this->assertEquals($aaa, 'aaa');

        $eee = \App\Config::get('ccc.ddd');
        $this->assertEquals($eee, 'eee');
    }

    public function testConfigExistsGetterSetter()
    {
        $key = 'test.key';
        $value = 'test-value';

        $isExists = \App\Config::exists($key);
        $this->assertFalse($isExists);

        \App\Config::set($key, $value);
        $isExists = \App\Config::exists($key);
        $this->assertTrue($isExists);

        $testValue = \App\Config::get($key);
        $this->assertEquals($value, $testValue);
    }
}