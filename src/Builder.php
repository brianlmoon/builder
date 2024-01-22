<?php

namespace Moonspot\Builder;

/**
 * Builder base class
 *
 * A base class for creating builder objects used to build other objects
 *
 * @author      Brian Moon <brian@moonspot.net>
 * @copyright   1997-Present DealNews.com, Inc
 * @package     Moonspot\Builder
 */
abstract class Builder {

    /**
     * Creates a new object
     *
     * @param      array|object  $data   The array or object used to create the object
     *
     * @return     object
     */
    abstract public function create(array|object $data): object;

    /**
     * Static factory style wrapper around the create method
     *
     * @param      array|object  $data   The array or object used to create the object
     *
     * @return     object
     */
    public static function build(array|object $data): object {
        static $builders = [];

        $class = get_called_class();
        if (empty($builders[$class])) {
            $builders[$class] = new $class();
        }

        return $builders[$class]->create($data);
    }

    /**
     * Set the property $key on the object $obj by finding the first valid
     * value in array $data by searching the array keys named in $data_key.
     *
     * @param object        $obj                Object which property we are setting.
     * @param string        $key                Property name being set.
     * @param array|object  $data               Data to search for set value.
     * @param array         $data_key           Keys to match for value, use first found.
     * @param bool          $accept_empty       When true, non-null empty values will
     *                                          be considered found, otherwise we
     *                                          consider not empty as found.
     */
    protected function setValue(object $obj, string $key, array|object $data, array $data_key = [], bool $accept_empty = true): void {
        array_push($data_key, $key);
        try {
            $value = $this->findValue($data_key, $data, $accept_empty);
            $obj->$key = $value;
        } catch (\TypeError $e) {
            if ($value !== null) {
                throw $e;
            }
        } catch (KeyNotFoundException $e) {
            // noop for this one
        }
    }

    /**
     * Finds a value from the source object/array using the array of keys
     *
     * @param  array  $keys          The key/property within the array/object
     * @param  array  $source        The source to look for data within
     * @param  bool   $accept_empty  When true, non-null empty values will
     *                               be considered found, otherwise we
     *                               consider not empty as found.
     *
     * @return     mixed   The value.
     */
    protected function findValue(array $keys, array|object $source, bool $accept_empty = true): mixed {
        $value = null;
        $key_found = false;
        foreach ($keys as $key) {
            if ($this->keyExists($key, $source)) {
                $key_found = true;
                $value = $this->getValue($key, $source);
                if ($value !== null) {
                    if (!empty($value) || $accept_empty) {
                        break;
                    }
                }
            }
        }

        if (!$key_found) {
            throw new KeyNotFoundException("Keys " . implode(", ", $keys) . " not found");
        }

        return $value;
    }

    /**
     * Gets a value from the source object/array
     *
     * @param      int|string  $key    The key/property within the array/object
     * @param      array       $source The source to look for data within
     *
     * @return     mixed   The value.
     */
    protected function getValue(int|string $key, array|object $source): mixed {
        $value = null;
        if (is_array($source) && array_key_exists($key, $source)) {
            $value = $source[$key];
        } elseif (is_object($source) && property_exists($source, $key)) {
            $value = $source->$key;
        }

        return $value;
    }

    /**
     * Checks if the source has the provided key
     *
     * @param      int|string  $key    The key/property within the array/object
     * @param      array       $source The source to look for data within
     *
     * @return     bool
     */
    protected function keyExists(int|string $key, array|object $source): bool {
        return (
            is_array($source) &&
            array_key_exists($key, $source)
        ) || (
            is_object($source) &&
            property_exists($source, $key)
        );
    }
}
