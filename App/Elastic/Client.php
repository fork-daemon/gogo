<?php

namespace App\Elastic;

use App\Elastic\Exception\ElasticException;
use Elasticsearch\ClientBuilder;

/**
 * Class Client
 *
 * @package App\Elastic
 */
class Client
{
    use \App\Traits\SetOptionsTrait;

    /**
     * @var \Elasticsearch\Client
     */
    protected $elasticClient;

    const P_RETRIES = 'retries';
    const P_HOSTS = 'hosts';

    const P_RETRIES_DEFAULT = 0;
    const P_HOSTS_DEFAULT = ['xxx.xxx:9200'];

    /**
     * clien configuration
     *
     * @var array
     */
    protected $config
        = [
            self::P_RETRIES => self::P_RETRIES_DEFAULT,
            self::P_HOSTS   => self::P_HOSTS_DEFAULT,
        ];

    // BASE ********************************************************************

    /**
     * Client constructor.
     *
     * @param array $options
     */
    public function __construct($options = [])
    {
        $this->setOptions($options);
    }


    /**
     * @return int
     */
    public function getRetries()
    {
        return $this->config[self::P_RETRIES];
    }


    /**
     * @param $retries
     *
     * @return $this
     */
    public function setRetries($retries)
    {
        $this->config[self::P_RETRIES] = (int) $retries;

        return $this;
    }

    /**
     * @return array
     */
    public function getHosts()
    {
        return $this->config[self::P_HOSTS];
    }

    /**
     * @param $hosts
     *
     * @return $this
     */
    public function setHosts($hosts)
    {
        $this->config[self::P_HOSTS] = (array) $hosts;

        return $this;
    }

    /**
     * @return \Elasticsearch\Client
     */
    public function getElasticClient()
    {
        if (null === $this->elasticClient) {
            $elasticClient = ClientBuilder::create();
            $elasticClient->setHosts($this->config[self::P_HOSTS]);
            $elasticClient->setRetries($this->config[self::P_RETRIES]);
            $this->elasticClient = $elasticClient->build();
        }

        return $this->elasticClient;
    }

    /**
     * @param \Elasticsearch\Client $elasticClient
     *
     * @return $this
     * @throws ElasticException
     */
    public function setElasticClient(\Elasticsearch\Client $elasticClient)
    {
        if (null != $this->elasticClient) {
            throw new ElasticException('elasticClient client is already set');
        }

        $this->elasticClient = $elasticClient;

        return $this;
    }

    // MAIN ********************************************************************

    /**
     * proxy method to elastic
     *
     * @param $param
     *
     * @return array
     */
    public function index($param)
    {
        return $this->getElasticClient()->index($param);
    }

    /**
     * proxy method to elastic
     *
     * @param $param
     *
     * @return array
     */
    public function get($param)
    {
        return $this->getElasticClient()->get($param);
    }

    /**
     * proxy method to elastic
     *
     * @param $param
     *
     * @return array
     */
    public function search($param)
    {
        return $this->getElasticClient()->search($param);
    }


    /**
     * proxy method to elastic
     *
     * @param $param
     *
     * @return array
     */
    public function delete($param)
    {
        return $this->getElasticClient()->delete($param);
    }

    /**
     * proxy method to elastic
     *
     * @param $param
     *
     * @return array
     */
    public function count($param)
    {
        return $this->getElasticClient()->count($param);
    }

}
