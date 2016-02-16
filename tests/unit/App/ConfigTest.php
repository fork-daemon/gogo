<?php
namespace App;


class ConfigTest extends \Codeception\TestCase\Test
{

    protected function _before()
    {
        \App\Config::load([
            ROOT . '/config/production.php'
        ]);
    }

    protected function _after()
    {
        \App\Config::clean();
    }

    public function testConfigExistsGetterSetter()
    {
        $key = 'test-key';
        $value = 'test-value';

        $isExists = \App\Config::exists($key);
        $this->assertFalse($isExists);

        \App\Config::set($key, $value);
        $isExists = \App\Config::exists($key);
        $this->assertTrue($isExists);

        $testValue = \App\Config::get($key);
        $this->assertEquals($value , $testValue);
    }

//    public function testMapGlobbals()
//    {
//        $globalKeys = [
//            'language_id',
//            'data_source_id',
//            'language_table',
//            'icecat_data_source_id',
//            'data_sources_to_update_by_icecat',
//        ];
//
//        foreach($globalKeys as $globalKey){
//            $configValue = \App\Config::get($globalKey);
//            $globalValue =$GLOBALS[$globalKey];
//            $this->assertEquals($globalValue , $configValue , "global key is: {$globalKey}");
//        }
//    }


}