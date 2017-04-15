<?php

namespace JodyBoucher\Laravel\Utility;

/**
 * Some basic array utility routines.
 */
class ArrayUtility
{
    /**
     * Checks if the given key or index exists in the array.
     *
     * @param array $array The array to check.
     * @param mixed $key   Value to check.
     *
     * @return bool
     */
    public static function keyExists(array $array = null, $key)
    {
        if ($array === null) {
            return false;
        }

        return isset($array[$key]) || array_key_exists($key, $array);
    }

    /**
     * Gets the value of the given key in the array, or default if key does not exist.
     *
     * @param array $array   The array to obtain value form.
     * @param mixed $key     The key of the value to obtain.
     * @param mixed $default The value to obtain if key does not exist.
     *
     * @return mixed The value associated with key, otherwise default.
     */
    public static function getValueOrDefault(array $array = null, $key, $default)
    {
        if ($array === null) {
            return $default;
        }

        return self::keyExists($array, $key) ? $array[$key] : $default;
    }
}
