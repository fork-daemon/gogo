<?php

namespace Lib\Database;

use App\Service;
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

    const P_DSN = 'dsn';
    const P_USER = 'user';
    const P_PASSWORD = 'password';
    const P_DB_OPTIONS = 'dbOptions';

    /**
     * client configuration
     *
     * @var array
     */
    protected $config
        = [
            self::P_DSN        => 'mysql:dbname=testdb;host=127.0.0.1',
            self::P_USER       => 'user',
            self::P_PASSWORD   => 'password',
            self::P_DB_OPTIONS => [],
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
     * @return \PDO
     */
    public function getPdo()
    {
        if ($this->pdo === null) {
            $this->pdo = new \PDO(
                $this->config[self::P_DSN],
                $this->config[self::P_USER],
                $this->config[self::P_PASSWORD],
                $this->config[self::P_DB_OPTIONS]
            );
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

    // OPTIONS ********************************************************************

    /**
     * @param $dsn
     *
     * @return $this
     */
    protected function setDsn($dsn)
    {
        $this->config[self::P_DSN] = $dsn;

        return $this;
    }

    /**
     * @param $user
     *
     * @return $this
     */
    protected function setUser($user)
    {
        $this->config[self::P_USER] = $user;

        return $this;
    }

    /**
     * @param $password
     *
     * @return $this
     */
    protected function setPassword($password)
    {
        $this->config[self::P_PASSWORD] = $password;

        return $this;
    }

    /**
     * @param $dbOptions
     *
     * @return $this
     */
    protected function setDbOptions($dbOptions)
    {
        $this->config[self::P_DB_OPTIONS] = $dbOptions;

        return $this;
    }

    // MAIN ********************************************************************

    /**
     * @return string
     */
    public function lastInsertId()
    {
        return $this->getPdo()->lastInsertId();
    }


    // https://habrahabr.ru/post/137664/

    protected function run($query, $data = [])
    {
        $pdo = $this->getPdo();
        $stm = $pdo->prepare($query);

        foreach ($data as $key => $value) {
            switch (true) {
                case is_int($value):
                    $type = \PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = \PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = \PDO::PARAM_NULL;
                    break;
                default:
                    $type = \PDO::PARAM_STR;
            }
            $stm->bindValue(':' . $key, $value, $type);
        }
        $stm->execute();

        return $stm;
    }


    public function command($query, $data = [])
    {
        $stm = $this->run($query, $data);

        return $stm->rowCount();
    }

    public function fetch($query, $data = [])
    {
        $stm = $this->run($query, $data);
        $stm->setFetchMode(\PDO::FETCH_ASSOC);

        return $stm->fetchAll();
    }

    public function fetchOne($query, $data = [])
    {

        $stm = $this->run($query, $data);
        $stm->setFetchMode(\PDO::FETCH_ASSOC);

        return $stm->fetch();
    }


    protected function glue(&$data, $glue = " AND "){
        $t = [];
        foreach$data as $key => $value){
            $contition = ' = ';
            if(is_array($value)){
                $contition = $value[0];
                $value = $value[1];
                $data[$key] = $value;
            }

            $t[] = "{$field} {$contition} :{$field}";
        }

        return implode($glue , $t);
    }


    public function insert($table, $data = [])
    {
        $replace['{table}'] = $table;
        $replace['{set}'] = $this->glue($data , ', ');
        $query = strtr("INSERT INTO {table} SET {set}" , $replace);

        return $this->run($query , $data);
    }

    public function update($table, $data = [], $where = [])
    {
        $replace['{table}'] = $table;
        $replace['{set}'] = $this->glue($data , ', ');
        $replace['{where}'] = $this->glue($data , ' AND ');
        $query = strtr("INSERT INTO {table} SET {set} WHERE {where}" , $replace);
        
        return $this->run($query , $data);
    }

    public function delete($table, $where = [])
    {
        $replace['{table}'] = $table;
        $replace['{where}'] = $this->glue($data , ' AND ');
        $query = strtr("DELETE FROM {table} WHERE {where}" , $replace);
        
        return $this->run($query , $data);
    }

}

