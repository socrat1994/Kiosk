<?php
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
class Response //implements ResponseInterface
{
    protected $statusCode;
    protected $headers;
    protected $body;

    public function __construct($statusCode = 200, array $headers = [], StreamInterface $body = null)
    {
        $this->statusCode = $statusCode;
        $this->headers = $headers;
        $this->body = $body;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function withStatus($code, $reasonPhrase = '')
    {
        $new = clone $this;
        $new->statusCode = $code;
        return $new;
    }

    public function getHeaderLine($name)
    {
        if (!$this->hasHeader($name)) {
            return '';
        }
        return implode(',', $this->headers[$name]);
    }

    public function getHeader($name)
    {
        if (!$this->hasHeader($name)) {
            return [];
        }
        return $this->headers[$name];
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function hasHeader($name)
    {
        return isset($this->headers[$name]);
    }

    public function withHeader($name, $value)
    {
        if (!is_array($value)) {
            $value = [$value];
        }
        $new = clone $this;
        $new->headers[$name] = $value;
        return $new;
    }

    public function withAddedHeader($name, $value)
    {
        if (!is_array($value)) {
            $value = [$value];
        }
        if (!$this->hasHeader($name)) {
            return $this->withHeader($name, $value);
        }
        $new = clone $this;
        foreach ($value as $v) {
            array_push($new->headers[$name], (string)$v);
        }
        return $new;
    }

    public function withoutHeader($name)
    {
        if (!$this->hasHeader($name)) {
            return clone $this;
        }
        $new = clone $this;
        unset($new->headers[$name]);
        return $new;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function withBody(StreamInterface $body)
    {
        if ($body === null) {
            throw new \InvalidArgumentException('Body must be a StreamInterface instance');
        }
        if ($body === $this->body) {
            return clone $this;
        }
        $new = clone $this;
        $new->body = $body;
        return $new;
    }

    public function getProtocolVersion()
    {
        // Not implemented in this example
    }

    public function withProtocolVersion($version)
    {
        // Not implemented in this example
    }

    public function getReasonPhrase()
    {
        // Not implemented in this example
    }
}

