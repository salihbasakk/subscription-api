<?php

namespace App\Service;

use Symfony\Contracts\Cache\CacheInterface;

class CacheService
{
    private CacheInterface $cache;

    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @param string $cacheKey
     * @return string|null
     */
    public function getFromCache(string $cacheKey): mixed
    {
        $item = $this->cache->getItem($cacheKey);

        if ($item->isHit()) {
            return $item->get();
        }

        return null;
    }

    /**
     * @param string $cacheKey
     * @param string $clientToken
     * @param int $ttl
     */
    public function saveCache(string $cacheKey, string $clientToken, int $ttl): void
    {
        $item = $this->cache->getItem($cacheKey);

        $item->set($clientToken);
        $item->expiresAfter($ttl);

        $this->cache->save($item);
    }
}