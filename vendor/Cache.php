<?php

namespace General;

use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;

class Cache implements CacheItemPoolInterface
{
    private $cacheDir;

    public function __construct($cacheDir)
    {
        $this->cacheDir = $cacheDir;
    }

    public function getItem($key)
    {
        $filename = $this->getFilename($key);

        if (!file_exists($filename)) {
            return new CacheItem($key, null, false);
        }

        $data = file_get_contents($filename);
        $data = unserialize($data);

        if ($data['expires'] < time()) {
            unlink($filename);
            return new CacheItem($key, null, false);
        }

        return new CacheItem($key, $data['value'], true);
    }

    public function getItems(array $keys = [])
    {
        $items = [];

        foreach ($keys as $key) {
            $items[$key] = $this->getItem($key);
        }

        return $items;
    }

    public function hasItem($key)
    {
        return file_exists($this->getFilename($key));
    }

    public function clear()
    {
        throw new Exception('Not implemented');
    }

    public function deleteItem($key)
    {
        throw new Exception('Not implemented');
    }

    public function deleteItems(array $keys)
    {
        throw new Exception('Not implemented');
    }

    public function save(CacheItemInterface $item)
    {
        $filename = $this->getFilename($item->getKey());
        $data = [
            'value' => $item->get(),
            'expires' => $item->getExpirationTimestamp(),
        ];
        $data = serialize($data);
        file_put_contents($filename, $data);
    }

    public function saveDeferred(CacheItemInterface $item)
    {
        throw new Exception('Not implemented');
    }

    public function commit()
    {
        throw new Exception('Not implemented');
    }

    private function getFilename($key)
    {
        return $this->cacheDir . '/' . md5($key) . '.cache';
    }
}
