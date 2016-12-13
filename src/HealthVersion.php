<?php

namespace GenTux\Healthz;

class HealthVersion
{

    protected $version;

    protected $customPath;

    /**
     * HealthVersion constructor.
     * @param string $customPath
     */
    public function __construct($customPath = __DIR__.'commit.txt')
    {
        $this->customPath = $customPath;
    }

    /**
     * checkVersion
     * @param $inputHash
     * @return bool
     */
    public function checkVersion($inputHash)
    {
        $this->version = $inputHash;

        if(!is_file($this->customPath)){
            return false;
        }

        return (file_get_contents($this->customPath) == $this->version) ? true : false;
    }
}