<?php declare(strict_types=1);

namespace App\Service;

use App\Enum\HealthStatus;

class GithubService
{
    public function getHealthReport(string $dinosaurName): HealthStatus
    {
        //Call Github API

        //Filter the issues

        return HealthStatus::HEALTHY;
    }

}