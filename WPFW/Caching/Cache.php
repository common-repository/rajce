<?php

namespace WPFW\Caching;

class Cache
{

    /** dependency */
    const EXPIRE = 'expire',
          EXPIRATION = 'expire';

    /** @var IStorage */
    private $storage;

    public function __construct(IStorage $storage)
    {
        $this->storage = $storage;
    }

    /**
     * Reads item from cache
     * @param  mixed key
     * @return mixed|NULL
     */
    public function load($key)
    {
        $data = $this->storage->read($this->generateKey($key));
        return $data;
    }

    /**
     * Writes item into cache
     * @param  mixed  key
     * @param  mixed  value
     * @param  array  dependencies
     */
    public function save($key, $data, array $dependencies = NULL)
    {
        $key = $this->generateKey($key);
        if ($data === NULL) {
            $this->storage->remove($key);
        } else {
            $this->storage->write($key, $data, $this->completeDependencies($dependencies));
            return $data;
        }
    }

    /**
     * Generates cache key
     *
     * @param  string
     * @return string
     */
    protected function generateKey($key)
    {
        return md5($key);
    }

    private function completeDependencies($dp)
    {
        if (!is_array($dp)) {
            $dp = [];
        }
        return $dp;
    }

}