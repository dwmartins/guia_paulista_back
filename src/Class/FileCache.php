<?php

namespace App\class;

class FileCache {
    private $cacheDir;
    private $defaultExpiration;

    public function __construct($cacheDir = 'cache', $defaultExpiration = 604800) {
        $rootPath = realpath(__DIR__ . '/../../');
        $this->cacheDir = $rootPath . DIRECTORY_SEPARATOR . $cacheDir;
        $this->defaultExpiration = $defaultExpiration;

        if(!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0755, true);
        }
    }

    private function getCacheFilePath($cacheKey) {
        return $this->cacheDir . DIRECTORY_SEPARATOR . md5($cacheKey) . '.cache';
    }

    public function get($cacheKey) {
        $cacheFile = $this->getCacheFilePath($cacheKey);

        if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $this->defaultExpiration) {
            $cache = file_get_contents($cacheFile);
            return json_decode($cache, true);
        }

        return false;
    }

    public function set($cacheKey, $data) {
        $cacheFile = $this->getCacheFilePath($cacheKey);
        file_put_contents($cacheFile, json_encode($data));
    }

    public function delete($cacheKey) {
        $cacheFile = $this->getCacheFilePath($cacheKey);

        if (file_exists($cacheFile)) {
            unlink($cacheFile);
        }
    }

    public function clear() {
        foreach (glob($this->cacheDir . DIRECTORY_SEPARATOR . '*.cache') as $file) {
            unlink($file);
        }
    }
}