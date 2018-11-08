<?php

declare(strict_types=1);

namespace App\View\Helper;

use League\Plates\Extension\ExtensionInterface;
use League\Plates\Engine;

class CdnLocator implements ExtensionInterface
{
    
    const CSS_EXTENSION = 'css'; 
    
    private $checksums = [];
    
    private $localBasePath;
    
    private $config;
    
    private $host;
    
    private $isLocal;
    
    public function __construct(array $config, bool $isLocal)
    {
        $this->config = $config;
        $this->host = $config['host'];
        $this->isLocal = $isLocal;
    }
    
    
    public function register(Engine $engine)
    {
        $engine->registerFunction('cssLocator', [$this, 'cssLocator']);
    }
    
    
    public function cssLocator($filename)
    {
        if ($this->isLocal) {
            return implode('/', [$this->config['paths']['css'], $filename.'.'.self::CSS_EXTENSION]);
        }
        
        $filename = implode('.', [$filename, self::CSS_EXTENSION]);
        
        $checksum = $this->getChecksum($filename);

        return $this->host.$this->config['paths']['css'].'/'.implode('.', [$checksum, self::CSS_EXTENSION]);;
    }
    
    
    private function getChecksum($filename)
    {
        if (!array_key_exists($filename, $this->checksums)) {
            $this->calculateChecksum($filename);
        }
        
        return $this->checksums[$filename];
    }
    
    
    private function calculateChecksum($filename) :void
    {
        $filePath = implode('/', [$this->getLocalBasePath(), $this->config['paths']['css'], $filename]);
        $this->checksums[$filename] = md5_file($filePath);
    }
    
    
    private function getLocalBasePath()
    {
        if (!$this->localBasePath) {
            $this->localBasePath = implode('/', [getcwd(), $this->config['localBasePath']]);
        }
        
        return $this->localBasePath;
    }
    
}
