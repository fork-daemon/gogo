<?php

namespace App\Lib;

use App\Exception\ImplementException;

trait SetOptionsTrait
{

    /**
     * @param array $options
     *
     * @return $this
     * @throws TraitsException
     */
    protected function setOptions($options = [])
    {
        if (!is_array($options)) {
            throw new TraitsException('Options is not an array!');
        }

        if (count($options) === 0) {
            return $this;
        }

        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (method_exists($this, $method)) {
                $this->{$method}($value);
            }
        }

        return $this;
    }


}