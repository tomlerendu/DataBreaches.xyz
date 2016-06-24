<?php

use Illuminate\Support\Facades\Request;

/**
 * Determines if a route is currently active and returns a class name if it is
 *
 * @param string $route
 * @param string $class
 * @return string
 */
function ifActiveRoute (string $route, string $class = 'active')
{
    if (Request::is($route)) {
        return $class;
    }

    return '';
}

/**
 * Concatenates an array of values into a string
 *
 * @param array $array The original values
 * @param string $glue The string to hold each value together
 * @return string The concatenated list
 */
function arrayKeyString (array $array, string $glue = ','): string
{
    $keys = array_keys($array);
    $string = implode($glue, $keys);

    return $string;
}


/**
 * Determines if a Laravel old value should be used to populate a checkbox
 *
 * @param string $property
 * @param string $key
 * @return string
 */
function oldCheckArray (string $property, string $key): string
{
    $checkArray = old($property);

    //Form submission format
    if (isset($checkArray[$key])) {
        return 'checked';
    }

    //toArray format
    if (is_array($checkArray)) {
        foreach ($checkArray as $value) {
            if ($value == $key || (isset($value['id']) && $value['id'] == $key)) {
                return 'checked';
            }
        }
    }

    return '';
}

/**
 * Determines if a Laravel old value should be used to populate a checkbox
 *
 * @param string $property
 * @return string
 */
function oldCheck (string $property) {

    if (old($property)) {
        return 'checked';
    }

    return '';
}

/**
 * Determines if a Laravel old value should be used to populate a radio field
 *
 * @param string $property The name of the old value
 * @param string $value The desired value that determines if the radio is checked
 * @return string Either checked or an empty string
 */
function oldRadio (string $property, string $value): string
{
    $radio = old($property);

    var_dump($radio);
    var_dump($value);
    echo "\n\n";
    if ($radio == $value) {
        return 'checked';
    }

    return '';
}

/**
 * Determines if a Laravel old value should be used to populate a date field
 *
 * @param string $property
 * @return string
 */
function oldDate (string $property): string
{
    $date = old($property);

    if ($date) {
        $date = new \Carbon\Carbon($date);
        return $date->format(config('date.short'));
    }

    return '';
}