<?php declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Enum\HealthStatus;
use App\Service\GithubService;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Twig\Node\Expression\TestExpression;

class GithubServiceTest extends TestCase
{
    /**
     * @dataProvider dinoNameProvider
     */
    public function testGetHealthReportReturnCorrectHealthStatusForDino(HealthStatus $expectedStatus, string$dinoName): void
    {
        $mockLogger = $this->createMock(LoggerInterface::class);
        $mockHttpClient = $this->createMock(HttpClientInterface::class);

        $service = new GithubService($mockHttpClient, $mockLogger);

        $this->assertSame($expectedStatus, $service->getHealthReport($dinoName));
    }

    public function dinoNameProvider(): \Generator
    {
        yield 'Sick Dino' =>[
            HealthStatus::SICK,
            'Daisy'
        ];

        yield "Healthy Dion" =>[
            HealthStatus::HEALTHY,
            'Maverick',
        ];
    }
}