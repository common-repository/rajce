<?php

namespace WPFW\Caching;

interface IStorage
{
    /**
     * Read from cache.
     * @param  string key
     * @return mixed|NULL
     */
    function read($key);

    /**
     * Writes item into the cache.
     * @param  string key
     * @param  mixed  data
     * @param  array  dependencies
     * @return void
     */
    function write($key, $data, array $dependencies);

    /**
     * Removes item from the cache.
     * @param  string key
     * @return void
     */
    function remove($key);

}