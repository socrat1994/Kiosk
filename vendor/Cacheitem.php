<?php

namespace General;

use Psr\Cache\CacheItemInterface;

class CacheItem implements CacheItemInterface
{
private $key;
private $value;
private $isHit;
private $expiration;

public function __construct($key, $value, $isHit)
{
$this->key = $key;
$this->value = $value;
$this->isHit = $isHit;
}

public function getKey()
{
return $this->key;
}

public function get()
{
return $this->value;
}

public function isHit()
{
return $this->isHit;
}

public function set($value)
{
$this->value = $value;

return $this;
}

public function expiresAt($expiration)
{
 $date = DateTime::createFromFormat('Y-M-D', $expiration);
if ($date === false) {
$this->expiration = $expiration;
} else {
$this->expiration = null;
}

return $this;
}

public function expiresAfter($time)
{

}

public function getExpirationTimestamp()
{
return $this->expiration;
}
}
