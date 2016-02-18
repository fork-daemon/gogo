<?php

namespace Lib\Database;

use Lib\Database\Exception\DatabaseException;

/**
 * Class Client
 *
 * @package App\Elastic
 */
class Client
{
    use \Lib\SetOptionsTrait;

    /**
     * @var \PDO
     */
    protected $pdo;

    const P_HOST = 'host';
    const P_USER = 'user';
    const P_PASSWORD = 'password';
    const P_DATABASE = 'database';

    /**
     * client configuration
     *
     * @var array
     */
    protected $config
        = [
            self::P_HOST     => 'localhost',
            self::P_USER     => 'user',
            self::P_PASSWORD => 'password',
            self::P_DATABASE => 'content',
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
        $this->config[self::P_RETRIES] = (int)$retries;

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
        $this->config[self::P_HOSTS] = (array)$hosts;

        return $this;
    }

    /**
     * @return \PDO
     */
    public function getPdo()
    {
        if ($this->pdo === null) {
            $this->pdo = new \PDO();
        }

        return $this->pdo;
    }

    /**
     * @param \PDO $pdo
     *
     * @return $this
     * @throws DatabaseException
     */
    public function setPdo(\PDO $pdo)
    {
        if ($this->pdo != null) {
            throw new DatabaseException('elasticClient client is already set');
        }

        $this->pdo = $pdo;

        return $this;
    }

    // MAIN ********************************************************************

    /**
     * @param $query
     * @param $data
     *
     * @return int
     */
    public function statement($query, $data)
    {
        return $this->getPdo()->exec($query, $data);
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function fetch($query)
    {
        return $this->getPdo()->query($query);
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function fetchOne($query, $data)
    {
        return $this->getPdo()->fetch($query);
    }


    protected function buildGlue($data, $glue)
    {
        $keys = array_keys($data);
        $mass = [];
        while ($key = array_shift($keys)) {
            $mass[] = "{$key} = :{$key}";
        }

        return implode($glue, $mass);
    }


    public function delete($table, $data)
    {

        $where = $this->buildGlue($data, ' AND ');

        return $this->statement("DELETE FROM {$table} WHERE {$where}", $data);
    }

    public function update($table, $qwe)
    {
        return $this->getPdo()->index($query);
    }

    public function insert($table, $data)
    {
        return $this->getPdo()->index($query);
    }

    public function updset($table, $index, $data)
    {
        return $this->getPdo()->index($query);
    }


}
