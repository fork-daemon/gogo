<?php

namespace Lib\Elastic;

use Codeception\Util\Stub;

class ClientTest extends \Codeception\TestCase\Test
{

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    public function testClientCorrectInstance()
    {
        $client = new \Lib\Elastic\Client();

        $this->assertInstanceOf('\Lib\Elastic\Client', $client);
    }

    public function testClientDefaultOptions()
    {
        $client = new \Lib\Elastic\Client();

        $this->assertEquals(\Lib\Elastic\Client::P_RETRIES_DEFAULT, $client->getRetries());
        $this->assertEquals([\Lib\Elastic\Client::P_HOSTS_DEFAULT], $client->getHosts());
    }

    public function testClientSetOptions()
    {
        $client = new \Lib\Elastic\Client(
            [
                \Lib\Elastic\Client::P_HOSTS   => ['xxx', 'yyy', 'zzz'],
                \Lib\Elastic\Client::P_RETRIES => 999,
            ]
        );

        $this->assertEquals(999, $client->getRetries());

        $hosts = $client->getHosts();
        $this->assertInternalType('array', $hosts);
        $this->assertEquals('xxx', $hosts[0]);
        $this->assertEquals('yyy', $hosts[1]);
        $this->assertEquals('zzz', $hosts[2]);
    }

    public function testClientAllProxyMethodsExists()
    {
        $client = new \Lib\Elastic\Client();

        $checkMethods = [
            'index',
            'get',
            'search',
            'delete',
        ];

        while ($method = array_pop($checkMethods)) {
            $this->assertTrue(method_exists($client, $method));
        }
    }




    public function testClientSetMock()
    {
        // readme: http://codeception.com/docs/reference/Stub#.VrnDXEKkU_s

        $stubSimpleFunction = function ($params) {
            return $params;
        };

        /** @var $elasticSearchClient \Elasticsearch\Client */

        $elasticSearchClient = Stub::make(
            '\Elasticsearch\Client', [
                'index'  => Stub::once($stubSimpleFunction),
                'get'    => Stub::once($stubSimpleFunction),
                'search' => Stub::once($stubSimpleFunction),
                'delete' => Stub::once($stubSimpleFunction),
                'count'  => Stub::once($stubSimpleFunction),
            ]
        );

        $client = new \Lib\Elastic\Client();
        $client->setElasticClient($elasticSearchClient);

        $this->assertEquals('call-index', $client->index('call-index'));
        $this->assertEquals('call-get', $client->get('call-get'));
        $this->assertEquals('call-search', $client->search('call-search'));
        $this->assertEquals('call-delete', $client->delete('call-delete'));
        $this->assertEquals('call-count', $client->count('call-count'));
    }


}