<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class ArrayHelper
 * Laravel style helper
 */
class ArrayHelper
{
    /**
     * Get an item from an array using "dot" notation.
     *
     * @param  array $array
     * @param  string $key
     * @param  mixed $default
     * @return mixed
     */
    public static function arrayGet($array, $key, $default = null)
    {
        if (null === $key) {
            return $array;
        }
        if (isset($array[$key])) {
            return $array[$key];
        }
        foreach (explode('.', $key) as $segment) {
            if (!is_array($array) || !array_key_exists($segment, $array)) {
                return static::value($default);
            }
            $array = $array[$segment];
        }
        return $array;
    }

    /**
     * Set an array item to a given value using "dot" notation.
     *
     * If no key is given to the method, the entire array will be replaced.
     *
     * @param  array $array
     * @param  string $key
     * @param  mixed $value
     * @return array
     */
    public static function arraySet(&$array, $key, $value)
    {
        if (is_null($key)) {
            return $array = $value;
        }
        $keys = explode('.', $key);
        while (count($keys) > 1) {
            $key = array_shift($keys);
            // If the key doesn't exist at this depth, we will just create an empty array
            // to hold the next value, allowing us to create the arrays to hold final
            // values at the correct depth. Then we'll keep digging into the array.
            if (!isset($array[$key]) || !is_array($array[$key])) {
                $array[$key] = array();
            }
            $array =& $array[$key];
        }
        $array[array_shift($keys)] = $value;
        return $array;
    }

    /**
     * Flatten a multi-dimensional associative array with dots.
     *
     * @param  array $array
     * @param  string $prepend
     * @return array
     */
    public static function arrayDot($array, $prepend = '')
    {
        $results = array();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $results = array_merge($results, static::arrayDot($value, $prepend . $key . '.'));
            } else {
                $results[$prepend . $key] = $value;
            }
        }
        return $results;
    }

    /**
     * Unflatten a associative array with dots to multi-dimensional representation.
     *
     * @param  array $arrayDot
     * @return array
     */
    public static function arrayFromDot($arrayDot)
    {
        $results = array();
        foreach ($arrayDot as $key => $value) {
            static::arraySet($results, $key, $value);
        }
        return $results;
    }

    /**
     * Check if an item exists in an array using "dot" notation.
     *
     * @param  array $array
     * @param  string $key
     * @return bool
     */
    public static function arrayHas($array, $key)
    {
        if (empty($array) || null === $key) {
            return false;
        }
        if (array_key_exists($key, $array)) {
            return true;
        }
        foreach (explode('.', $key) as $segment) {
            if (!is_array($array) || !array_key_exists($segment, $array)) {
                return false;
            }
            $array = $array[$segment];
        }
        return true;
    }

    /**
     * Return the default value of the given value.
     *
     * @param  mixed $value
     * @return mixed
     */
    public static function value($value)
    {
        return $value instanceof Closure ? $value() : $value;
    }
}