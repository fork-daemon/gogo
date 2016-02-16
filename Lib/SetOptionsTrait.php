<?php

namespace Lib;

use Lib\Exception\RuntimeException;

trait SetOptionsTrait
{

    /**
     * @param array $options
     *
     * @return $this
     * @throws RuntimeException
     */
    protected function setOptions($options = [])
    {
        if (!is_array($options)) {
            throw new RuntimeException('Options is not an array!');
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