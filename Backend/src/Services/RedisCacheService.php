<?php
namespace web\Services; 

use Predis\Client;

class RedisCacheService {
    private $redis;
    
    public function __construct() {
        $connectionTimeout = 0.8; 
        
        try {
            $this->redis = new Client([
                'scheme' => 'tcp',
                'host'   => $_ENV['REDIS_HOST'] ?? '127.0.0.1', 
                'port'   => $_ENV['REDIS_PORT'] ?? 6379,
                'timeout' => $connectionTimeout 
            ]);
            $this->redis->ping(); 
            
        } catch (\Exception $e) {
            $this->redis = null; 
            error_log("Redis error de conexão (Timeout: {$connectionTimeout}s): " . $e->getMessage());
        }
    }

    public function get(string $key): ?array {
        if (!$this->redis) {
            return null;
        }
        try {
            $data = $this->redis->get($key);
            if ($data) {
                return json_decode($data, true);
            }
        } catch (\Exception $e) {
            return null;
        }
        return null;
    }
    
    public function set(string $key, array $data, int $ttl): void {
        if (!$this->redis) {
            return;
        }
        try {
            $this->redis->setex($key, $ttl, json_encode($data));
        } catch (\Exception $e) {
            error_log("Redis log error: " . $e->getMessage());
        }
    }
}