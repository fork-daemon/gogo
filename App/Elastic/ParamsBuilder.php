<?php

namespace App\Elastic;

use App\Libs\ArrayHelper;

/**
 * Class Client
 *
 * NOTE: Write helper methods for IDE
 *
 * @method ParamsBuilder index(string $index) - elastic index
 * @method ParamsBuilder type(string $type) - elastic type
 * @method ParamsBuilder id(mixed $id) - elastic id
 * @method ParamsBuilder body(array $body) - request: body
 *
 * @method ParamsBuilder search_type(string $searchType) - use search_type [scan / ...]
 * @method ParamsBuilder scroll(string $time) - how long between scroll requests. should be small!
 * @method ParamsBuilder from(int $size) - from what item you want start
 * @method ParamsBuilder size(int $size) - how many results *per shard* you want back
 *
 * @method ParamsBuilder bodyExplain(bool $size) - Enables explanation for each hit on how its score was computed.
 *
 * @method ParamsBuilder bodySort(array ...$params) - request: body.sort
 *
 * @method ParamsBuilder bodyQuery(array ...$params) - request: body.query
 * @method ParamsBuilder bodyQueryTerm(array ...$params) - request: body.query.term
 * @method ParamsBuilder bodyQueryMatch(array ...$params) - request: body.query.match
 * @method ParamsBuilder bodyQueryMatch_all(array ...$params) - request: body.query.match_all
 * @method ParamsBuilder bodyQueryMatch_phrase(array ...$params) - request: body.query.match_phrase
 * @method ParamsBuilder bodyQueryBoolMust(array ...$params) - request: body.query.bool.must
 * @method ParamsBuilder bodyQueryBoolMustMatch(array ...$params) - request: body.query.bool.must.match
 * @method ParamsBuilder bodyQueryRange(array ...$params) - request: body.query.range
 *
 * @method ParamsBuilder bodyQueryFilteredFilterTerm(string $key, $value) - request: body.filtered.filter.term
 * @method ParamsBuilder bodyQueryFilteredQueryMatch(string $key, $value) - request: body.filtered.query.match
 *
 * @package App\Elastic
 */
class ParamsBuilder
{

    /**
     * @var array
     */
    public $params = [];

    /**
     * @return array
     */
    public function get()
    {
        return $this->params;
    }

    /**
     * @param      $method
     * @param null $args
     *
     * @return $this
     */
    public function __call($method, $args = null)
    {
        $way = preg_split('/(?=[A-Z])/', $method, -1, PREG_SPLIT_NO_EMPTY);
        $way = array_map('strtolower', $way);

        $value = null;
        if (count($args) === 2) {
            $value[$args[0]] = $args[1];
        } elseif (count($args) === 1) {
            $value = $args[0];
        } else {
            $value = $args;
        }

        ArrayHelper::setByPath($this->params, implode('.', $way), $value);

        return $this;
    }

}