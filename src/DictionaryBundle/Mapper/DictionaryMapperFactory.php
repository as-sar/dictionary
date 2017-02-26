<?php

namespace DictionaryBundle\Mapper;

/**
 * Class DictionaryMapperFactory
 *
 * @package DictionaryBundle\Mapper
 */
class DictionaryMapperFactory
{
    const TYPE_ARRAY = 'array';

    /**
     * @param string $rootDir
     * @param string $dictionarySourcePath
     * @param string $type
     *
     * @return DictionaryArray
     */
    public static function createDictionaryMapper($rootDir, $dictionarySourcePath, $type = self::TYPE_ARRAY)
    {
        switch ($type) {

            case self::TYPE_ARRAY:
                $dictionaryFile = $rootDir . '/..' . $dictionarySourcePath;
                $contents = file_get_contents($dictionaryFile);
                $dictionaryData = json_decode($contents, true);
                $dictionaryArrayMapper = new \DictionaryBundle\Mapper\DictionaryArray($dictionaryData);
                break;
        }

        return $dictionaryArrayMapper;
    }
}