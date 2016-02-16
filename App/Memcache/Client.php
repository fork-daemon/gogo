<?php

namespace App\Memcache;

class Client
{

    use \App\Traits\SetOptionsTrait;

    const P_PREFIX = 'prefix';
    const P_IPS = 'ips';

    const P_PREFIX_DEFAULT = 'abc';
    const P_IPS_DEFAULT
        = [
            // [ $host, $port, $persistent, $weight ]
            ['192.168.1.00', 11211, 1],
            ['192.168.1.01', 11211, 1],
        ];

    /**
     * @var \Memcache
     */
    protected $memcacheClient = null;

    /**
     * @var array
     */
    protected $config
        = [
            self::P_PREFIX => self::P_PREFIX_DEFAULT,
            self::P_IPS    => self::P_IPS_DEFAULT
        ];

    /**
     * TODO : REFACTOR constants names
     */
    const TTL_VERY_SMALL = 10 * 60; // 10 minutes
    const TTL_SMALL = 30 * 60; // 30 minutes
    const TTL_MEDIUM = 60 * 60; // 1 hour
    const TTL_BIG = 60 * 60; // 1 hour
    const TTL_HUGE = 120 * 60; // 2 hour
    const TTL_GREAT = 24 * 60 * 60; // 1 day
    const TTL_MONTH = 30 * 24 * 60 * 60; // 1 month

    // BASE *******************************************************************

    /**
     * Client constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->setOptions($options);
    }


    /**
     * @param $ips
     *
     * @return $this
     */
    public function setIps($ips)
    {
        $this->config[self::P_IPS] = (array) $ips;

        return $this;
    }

    /**
     * @param $prefix
     *
     * @return $this
     */
    public function setPrefix($prefix)
    {
        $this->config[self::P_PREFIX] = (string) $prefix;

        return $this;
    }

    /**
     * @return \Memcache
     */
    public function getMemcacheClient()
    {
        if ($this->memcacheClient === null) {
            $this->memcacheClient = new \Memcache();
            foreach ($this->config[self::P_IPS] as $i => $params) {
                $this->memcacheClient->addServer($params[0], $params[1], false, $params[2]);
            }
        }

        return $this->memcacheClient;
    }

    //endregion *******************************************

    //region MAIN ***********************************************

    /**
     * @param $key
     *
     * @return array|string
     */
    public function get($key)
    {
        $prefixKey = $this->buildKey($key);
        $value = $this->getMemcacheClient()->get($prefixKey);

        return $value;
    }

    /**
     * @param     $key
     * @param     $value
     * @param int $expire
     *
     * @return bool
     */
    public function set($key, $value, $expire = 0)
    {
        $prefixKey = $this->buildKey($key);
        $result = $this->getMemcacheClient()->set($prefixKey, $value, false, (int) $expire);

        return $result;
    }

    /**
     * @param     $key
     * @param     $value
     * @param int $expire
     *
     * @return array|bool
     */
    public function add($key, $value, $expire = 0)
    {
        $prefixKey = $this->buildKey($key);
        $result = $this->getMemcacheClient()->add($prefixKey, $value, false, (int) $expire);

        return $result;
    }


    /**
     * @param     $key
     * @param     $value
     * @param int $expire
     *
     * @return bool
     */
    public function replace($key, $value, $expire = 0)
    {
        $prefixKey = $this->buildKey($key);
        $result = $this->getMemcacheClient()->replace($prefixKey, $value, false, (int) $expire);

        return $result;
    }

    /**
     * @param $key
     *
     * @return bool
     */
    public function delete($key)
    {
        $prefixKey = $this->buildKey($key);
        $result = $this->getMemcacheClient()->delete($prefixKey);

        return $result;
    }

    /**
     * @return bool
     */
    public function flush()
    {
        $result = $this->getMemcacheClient()->flush();

        return $result;
    }

    //endregion *******************************************

    //region FEATURES ******************************************

    /**
     * @param     $key
     * @param     $callback
     * @param int $expire
     *
     * @return array|string
     */
    public function cache($key, $callback, $expire = 0)
    {
        $value = $this->get($key);

        if ($value === false && is_callable($callback)) {
            $value = $callback();
            $this->set($key, $value, $expire);
        }

        return $value;
    }
    //endregion *******************************************

    //region HELPERS ******************************************

    /**
     * @param $key
     *
     * @return string
     */
    protected function buildKey($key)
    {
        return $this->config[self::P_PREFIX] . $key;
    }

    //endregion *******************************************
}

