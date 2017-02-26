<?php

namespace DictionaryBundle\Mapper;

/**
 * Class DictionaryArray
 *
 * @package DictionaryBundle\Mapper
 */
class DictionaryArray implements DictionaryMapperInterface
{
    /**
     * @var array
     */
    protected $dictionary;

    /**
     * DictionaryArray constructor.
     *
     * @param array $dictionary
     */
    public function __construct(array $dictionary)
    {
        $this->dictionary = $dictionary;
    }

    /**
     * @param $key
     *
     * @return array|null
     */
    public function findByKey($key)
    {
        $result = null;

        if (isset($this->dictionary[$key])) {
            $result = [
                $key => $this->dictionary[$key]
            ];
        }
        return $result;
    }

    /**
     * @param $value
     *
     * @return array|null
     */
    public function findByValue($value)
    {
        $result = null;

        if (in_array($value, $this->dictionary)) {
            $result = array_keys($this->dictionary, $value);
        }
        return $result;
    }

    /**
     * @param int   $limit
     * @param array $exclude
     *
     * @return array
     */
    public function findRandom($limit = 1, array $exclude = [])
    {
        $limit = (sizeof($this->dictionary) < $limit) ? sizeof($this->dictionary) : $limit;
        $keys = array_rand($this->dictionary, $limit);

        return array_intersect_key($this->dictionary, array_flip($keys));
    }
}