<?php

namespace GenTux\Healthz;

class HealthVersion
{

    protected $customPath;

    /**
     * HealthVersion constructor.
     * @param string $customPath
     */
    public function __construct($customPath = __DIR__.'/commit.txt')
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
        if (file_exists($this->customPath)) {
            return (trim(file_get_contents($this->customPath)) == $inputHash);
        } else {
            return false;
        }
    }
}
