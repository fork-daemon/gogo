<?php

namespace Lib\Source;

interface ClientInterface
{

    /**
     * @param ModelInterface $model
     *
     * @return mixed
     */
    public static function create(ModelInterface $model);

    /**
     * @param $id
     *
     * @return mixed
     */
    public static function read($id);

    /**
     * @param ModelInterface $model
     *
     * @return mixed
     */
    public static function update(ModelInterface $model);

    /**
     * @param ModelInterface $model
     *
     * @return mixed
     */
    public static function delete(ModelInterface $model);

}



