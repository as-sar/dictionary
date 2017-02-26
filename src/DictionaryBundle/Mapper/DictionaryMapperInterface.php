<?php

namespace DictionaryBundle\Mapper;

/**
 * Interface DictionaryMapperInterface
 *
 * @package DictionaryBundle\Mapper
 */
interface DictionaryMapperInterface
{
    /**
     * @param $key
     *
     * @return mixed
     */
    public function findByKey($key);

    /**
     * @param $value
     *
     * @return mixed
     */
    public function findByValue($value);

    /**
     * @param       $count
     * @param array $exclude
     *
     * @return mixed
     */
    public function findRandom($count, array $exclude = []);

}