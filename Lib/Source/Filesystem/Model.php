<?php

namespace Lib\Source\Filesystem;

use Lib\Source\ModelInterface;

class Model implements ModelInterface {

    /**
     * @var
     */
    protected $id;

    /**
     * @var array
     */
    protected $data = [];


    public function find($id)
    {
        return Client::read($id , new static());
    }

    public function save()
    {
        if(isset($this->id)){
            Client::update($this);
        } else {
            $this->setId('xxx-'.time().'-'.uniqid());
            Client::create($this);
        }
        return $this;
    }

    public function destroy()
    {
        return Client::delete($this);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @param      $path
     * @param null $default
     *
     * @return mixed
     */
    public function getData($path, $default = null)
    {
        // TODO: Implement getData() method.
    }

    /**
     * @param $path
     * @param $value
     *
     * @return mixed
     */
    public function setData($path, $value)
    {
        // TODO: Implement setData() method.
    }

    /**
     * @param $path
     *
     * @return mixed
     */
    public function existsData($path)
    {
        // TODO: Implement existsData() method.
    }



}
