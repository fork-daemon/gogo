<?php

namespace App\Elastic;

use App\Elastic\Exception\ModelException;
use App\Libs\ArrayHelper;
use App\Traits\SetOptionsTrait;

class Model
{

    use SetOptionsTrait;

    /**
     * if we need schema in model then just set it for array with 'keys'
     * but if we not, then just set it for 'null'
     *
     * @var null|array
     */
    protected $schema = null;
    /**
     * elastic equivalent 'index'
     *
     * @var string
     */
    protected $index;
    /**
     * elastic equivalent 'type'
     *
     * @var string
     */
    protected $type;
    /**
     * elastic equivalent 'id'
     *
     * @var int|string|null
     */
    protected $id;
    /**
     * elastic equivalent for special set 'version' / 'score' ....
     *
     * @var array|null
     */
    protected $info = null; //
    /**
     * elastic equivalent 'body' or '_source'
     *
     * @var array
     */
    protected $data = []; // elastic 'body'
    /**
     * use more fields describe times create and update in model
     *
     * @var bool
     */
    protected $timestamps = false;

    //region BASE *******************************************

    /**
     * main keys constant
     */
    const P_INDEX = 'index';
    const P_TYPE = 'type';
    const P_ID = 'id';
    const P_INFO = 'info';
    const P_DATA = 'data';

    /**
     * Model constructor.
     *
     * @param array $options
     */
    public function __construct($options = [])
    {
        $this->setOptions($options);
    }

    //endregion *******************************************

    //region  Elastic ************************************************

    /**
     * @var Client
     */
    protected static $client;

    /**
     * @return Client
     */
    public static function getClient()
    {
        if (static::$client === null) {
            /** @var Client $elastic */
            static::$client = \App\Services::get('elastic');
        }

        return static::$client;
    }

    /**
     * @param Client $client
     *
     * @return $this
     */
    public static function setClient(Client $client)
    {
        static::$client = $client;
    }

    //endregion *******************************************

    //region  HELPERS ************************************************

    /**
     * @var array
     */
    public static $cacheModelSet = [];

    /**
     * for static use with use cache
     *
     * @return self
     */
    public static function getCurrentModel()
    {
        $class = get_called_class();
        if (array_key_exists($class, self::$cacheModelSet)) {
            return self::$cacheModelSet[$class];
        }

        return self::$cacheModelSet[$class] = new $class;
    }

    /**
     * @return string
     */
    public static function getModelIndex()
    {
        return self::getCurrentModel()->index;
    }


    /**
     * @return string
     */
    public static function getModelType()
    {
        return self::getCurrentModel()->type;
    }

    /**
     * @return array
     */
    public static function getModelSchema()
    {
        return self::getCurrentModel()->schema;
    }

    // QUERY *****************************************************

    /**
     * <code>
     * <?php
     *
     *     $search = ModelXXX::search(['from' => 5 , 'query' => [ ... ]], true);
     *
     *     // --- OR -----------------
     *
     *     $search = ModelXXX::search(function($params){
     *          $params->size(2);
     *          $params->bodyExplain(true);
     *          $params->bodySort([ 'create' => ["order" => "asc", "mode" =>  "avg"]]);
     *          $params->bodyQueryTerm("ts" , '1452258486');
     *     }, true);
     *
     *     // --- OR -----------------
     *
     *     $params = new ParamsBuilder();
     *     $params->size(2);
     *     $params->bodyExplain(true);
     *     $params->bodySort([ 'create' => ["order" => "asc", "mode" =>  "avg"]]);
     *     $params->bodyQueryTerm("ts" , '1452258486');
     *     $search = ModelXXX::search($params, true);
     *
     *</code>
     *
     * Read about query :
     *      https://www.elastic.co/guide/en/elasticsearch/reference/1.4/_executing_searches.html
     *
     * @param array|ParamsBuilder|callable $query
     * @param bool                         $asArray
     * @param array                        $options
     *
     * @return static[]
     */
    public static function search($query = [], $asArray = false, $options = [])
    {
        $result = [];

        $currentModel = self::getCurrentModel();
        $currentIndex = $currentModel->getModelIndex();
        $currentType = $currentModel->getModelType();

        $params = ($query instanceof ParamsBuilder)
            ? $query
            : (new ParamsBuilder())->from(0)->size(2147483647);

        $params
            ->index($currentIndex)
            ->type($currentType);

        if (is_callable($query)) {
            /** @var $params ParamsBuilder */
            $query($params);
        } elseif (is_array($query)) {

            if (isset($query['from'])) {
                $params->from((int) $query['from']);
            }
            if (isset($query['size'])) {
                $params->size((int) $query['size']);
            }
            if (isset($query['query'])) {
                $params->bodyQuery($query['query']);
            }
            if (isset($query['sort'])) {
                $params->bodySort($query['sort']);
            }
            if (isset($query['explain'])) {
                $params->bodyExplain(!!$query['explain']);
            }
        }

        if (isset($options['singleDocument']) && $options['singleDocument']) {
            $params->size(1);
        }

        try {
            $response = static::getClient()->search($params->get());
        } catch (\Elasticsearch\Common\Exceptions\Missing404Exception $ex) {
            return $result;
        }

        foreach ($response['hits']['hits'] as $i => $data) {

            $itemData = [
                self::P_INDEX => $currentIndex,
                self::P_TYPE  => $currentType,
                self::P_ID    => $data['_id'],
                self::P_DATA  => $data['_source'],
            ];

            if (isset($data['_score'])) {
                $itemData[self::P_INFO]['score'] = $data['_score'];
            }

            if (isset($data['_explanation'])) {
                $itemData[self::P_INFO]['explanation'] = $data['_explanation'];
            }

            $result[] = ($asArray) ? $itemData : new static($itemData);
        }

        return $result;
    }


    /**
     * @param array $query
     * @param bool  $asArray
     *
     * @return null|self|array
     */
    public static function searchOne($query = [], $asArray = false)
    {

        if (is_string($query) || is_int($query)) {
            return static::getById($query, $asArray);
        }

        $options['singleDocument'] = true;
        $list = static::search($query, $asArray, $options);

        return $list[0];
    }


    /**
     * @param      $id
     * @param bool $asArray
     *
     * @return null|self|array
     */
    public static function getById($id, $asArray = false)
    {
        $currentModel = self::getCurrentModel();
        $currentIndex = $currentModel->getModelIndex();
        $currentType = $currentModel->getModelType();

        $params = (new ParamsBuilder())
            ->index($currentIndex)
            ->type($currentType)
            ->id($id);

        try {
            $response = static::getClient()->get($params->get());
        } catch (\Elasticsearch\Common\Exceptions\Missing404Exception $ex) {
            return null;
        }

        $itemData = [
            self::P_INDEX => $currentIndex,
            self::P_TYPE  => $currentType,
            self::P_ID    => $response['_id'],
            self::P_DATA  => $response['_source'],
        ];

        if (isset($response['_version'])) {
            $itemData[self::P_INFO]['version'] = $response['_version'];
        }

        $item = ($asArray) ? $itemData : new static($itemData);

        return $item;
    }

    /**
     * @param ParamsBuilder|callable $query
     *
     * @return mixed
     */
    public static function count($query = null)
    {

        $params = ($query instanceof ParamsBuilder)
            ? $query
            : new ParamsBuilder();

        $params
            ->index(self::getCurrentModel()->getModelIndex())
            ->type(self::getCurrentModel()->getModelType());

        if (is_callable($query)) {
            $query($params);
        }

        $response = static::getClient()->count($params->get());

        return $response['count'];
    }

    //endregion *******************************************

    //region STORAGE ************************************************

    /**
     * template method - rewrite if need in child classes
     */
    protected function callBeforeSave()
    {
        // rewrite it ...
    }

    /**
     * template method - rewrite if need in child classes
     */
    protected function callBeforeDestroy()
    {
        // rewrite it ...
    }

    /**
     * Generate unique id
     *
     * @return string
     */
    protected function generateId()
    {
        return (microtime(true) * 10000) . '' . uniqid();
    }


    const P_TIMESTAMP_CREATE = '@create';
    const P_TIMESTAMP_UPDATE = '@update';

    /**
     * @return $this|Model
     * @throws ModelException
     */
    protected function timestamps()
    {
        if (!$this->timestamps) {
            return $this;
        }

        $currentTime = time();
        if (!$this->existsData(self::P_TIMESTAMP_CREATE)) {
            $this->setData(self::P_TIMESTAMP_CREATE, $currentTime);
        }

        return $this->setData(self::P_TIMESTAMP_UPDATE, $currentTime);
    }

    /**
     * @return array
     */
    public function save()
    {
        $this->callBeforeSave();

        if (!$this->isStored()) {
            $this->setId($this->generateId());
        }

        $this->timestamps();

        $params = (new ParamsBuilder())
            ->index($this->getIndex())
            ->type($this->getType())
            ->id($this->getId())
            ->body($this->getData());

        $result = static::getClient()->index($params->get());

        return $this;
    }

    /**
     * @return bool
     */
    public function destroy()
    {
        $this->callBeforeDestroy();

        if ($this->getId() === null) {
            return false;
        }

        $params = (new ParamsBuilder())
            ->index($this->getIndex())
            ->type($this->getType())
            ->id($this->getId());

        try {
            $response = static::getClient()->delete($params->get());
        } catch (\Elasticsearch\Common\Exceptions\Missing404Exception $ex) {
            $this->setId(null);

            return false;
        }


        if ($this->id == $response['_id']) {
            $this->setId(null);

            return true;
        }

        return false;
    }

    //endregion *******************************************

    //region SOURCE ********************************************************

    /**
     * @return mixed
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @return array|null
     * @throws ModelException
     */
    public function getCreateTimestamp()
    {
        if (!$this->timestamps) {
            throw new ModelException('Can\'t get create time, cause current model without TIMESTAMPS!');
        }

        return $this->getData(self::P_TIMESTAMP_CREATE);
    }

    /**
     * @return array|null
     * @throws ModelException
     */
    public function getUpdateTimestamp()
    {
        if (!$this->timestamps) {
            throw new ModelException('Can\'t get update time, cause current model without TIMESTAMPS!');
        }

        return $this->getData(self::P_TIMESTAMP_UPDATE);
    }


    /**
     * @return string
     * @throws ModelException
     */
    public function getPath()
    {
        if ($this->getId() === null) {
            throw new ModelException('Can\'t get path, cause model "id" is undefined');
        }

        return implode('@', [$this->getIndex(), $this->getType(), $this->getId()]);
    }

    /**
     * @param $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return bool
     */
    public function isStored()
    {
        return $this->id !== null;
    }

    /**
     * @return mixed
     */
    public function toArray()
    {
        return [
            self::P_INDEX => $this->getIndex(),
            self::P_TYPE  => $this->getType(),
            self::P_ID    => $this->getId(),
            self::P_DATA  => $this->getData(),
            self::P_INFO  => $this->getInfo(),
        ];
    }

    /**
     * @return mixed
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * @param $info
     *
     * @return $this
     */
    protected function setInfo($info)
    {
        $this->info = $info;

        return $this;
    }

    /**
     * @param null|string $path
     * @param null|mixed  $default
     *
     * @return array|mixed
     */
    public function getData($path = null, $default = null)
    {
        if ($path === null) {
            return $this->data;
        }

        return ArrayHelper::getByPath($this->data, $path, $default);
    }

    /**
     * @param      $path
     * @param null $value
     *
     * @return $this
     * @throws ModelException
     */
    public function setData($path, $value = null)
    {
        if (is_array($path) && $value === null) {
            foreach ($path as $k => $v) {
                $this->setData($k, $v);
            }
            return $this;
        }

        if ($this->timestamps && in_array($path, [self::P_TIMESTAMP_CREATE, self::P_TIMESTAMP_UPDATE])) {
            // continue
        } elseif ((is_array($this->schema) && !array_key_exists($path, $this->schema))) {
            throw new ModelException("Can't set data, cause current model schema without path \"{$path}\"!");
        }

        ArrayHelper::setByPath($this->data, $path, $value);

        return $this;
    }

    /**
     * @param string $path
     *
     * @return $this
     */
    public function deleteData($path)
    {
        ArrayHelper::unsetByPath($this->data, $path);

        return $this;
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    public function existsData($path)
    {
        return ArrayHelper::existsByPath($this->data, $path);
    }

    //endregion *******************************************

    //region MAGIC ************************************************

    /**
     * @param $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        return $this->getData($key);
    }

    /**
     * @param string $key
     * @param mixed  $value
     */
    public function __set($key, $value)
    {
        $this->setData($key, $value);
    }

    /**
     * copy model without id and info
     */
    public function __clone()
    {
        $this->id = null;
        $this->info = null;
    }

    //endregion *******************************************
}
