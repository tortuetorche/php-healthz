<?php
namespace Gentux\Healthz\Checks\General;

use Gentux\Healthz\HealthCheck;

class GitCommit extends HealthCheck
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

    public function run()
    {

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
