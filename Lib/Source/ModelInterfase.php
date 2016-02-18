<?php

namespace Lib\Source;

interface ModelInterface
{

//    /**
//     * @return mixed
//     */
//    public function getClient();
//
//    /**
//     * @param $client
//     *
//     * @return mixed
//     */
//    public function setClient($client);

    /**
     * @return mixed
     */
    public function getId();

    /**
     * @param $id
     *
     * @return mixed
     */
    public function setId($id);

    /**
     * @param      $path
     * @param null $default
     *
     * @return mixed
     */
    public function getData($path, $default = null);

    /**
     * @param $path
     * @param $value
     *
     * @return mixed
     */
    public function setData($path, $value);

    /**
     * @param $path
     *
     * @return mixed
     */
    public function existsData($path);

}