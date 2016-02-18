<?php

namespace Lib\Source\Filesystem;

use Lib\Source\ClientInterface;
use Lib\Source\ModelInterface;

class Client implements ClientInterface
{
    /**
     * @param ModelInterface $model
     *
     * @return mixed
     */
    public static function create(ModelInterface $model)
    {
        $data = $model->getData();
        $id = $model->getId();
        $data = json_encode($data);
        file_put_contents($id, $data);

        return true;
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public static function read($id, $model)
    {
        $data = file_get_contents($id);
        $data = json_decode($data, true);
        $model->setId($id);
        $model->setData($data);

        return $model;
    }

    /**
     * @param ModelInterface $model
     *
     * @return mixed
     */
    public static function update(ModelInterface $model)
    {
        return static::create($model);
    }

    /**
     * @param ModelInterface $model
     *
     * @return mixed
     */
    public static function delete(ModelInterface $model)
    {
        $id = $model->getId();
        @unlink($id);
        return true;
    }

}
