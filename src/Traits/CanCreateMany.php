<?php

namespace Laraning\Boost\Traits;

/**
 * Allows the Model to use the static method ::createMany.
 */
trait CanCreateMany
{
    /**
     * Creates many Models.
     *
     * @param array $datasets The data array of arrays.
     *
     * @return array The created models array.
     */
    public static function createMany(array $datasets) : array
    {
        $models = [];

        foreach ($datasets as $dataset) {
            $models[] = static::create($dataset);
        }

        return $models;
    }
}
