<?php
namespace General;

class EnvReader {

    private static $instance;
    private $env;
    private $cache;

    public function __construct() {
        $this->cache = __DIR__ . '/../cache/env_cache.php';
        if (file_exists($this->cache)) {
            $this->env = include($this->cache);
        } else {
            $path = __DIR__ . '/../.env';
            if (file_exists($path)) {
                $envData = file_get_contents($path);
                $envLines = explode(PHP_EOL, $envData);

                foreach ($envLines as $line) {
                    $line = trim($line);

                    if (!empty($line) && strpos($line, '#') !== 0) {
                        list($key, $value) = explode('=', $line, 2);
                        $this->env[$key] = $value;
                    }
                }

                file_put_contents($this->cache, '<?php return '.var_export($this->env, true).';');
            } else {
                throw new Exception('.env file not found at ' . $path);
            }
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function get($key) {
        return isset($this->env[$key]) ? $this->env[$key] : null;
    }
}

