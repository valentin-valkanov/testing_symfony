<?php declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Enum\HealthStatus;
use App\Service\GithubService;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
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
        $mockResponse = $this->createMock(ResponseInterface::class);

        $mockResponse
            ->method('toArray')
            ->willReturn([
                [
                    'title' => 'Daisy',
                    'labels' => [['name' => 'Status: Sick']]

                ],
                [
                    'title' => 'Maverick',
                    'labels' => [['name' => 'Status: Healthy']]

                ]
            ])
        ;

        $mockHttpClient
            ->expects($this->once())
            ->method('request')
            ->with('GET', 'https://api.github.com/repos/SymfonyCasts/dino-park/issues')
            ->willReturn($mockResponse)
        ;


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

    public function testExceptionThrownWithUnknownLabel(): void
    {
        $mockResponse = new MockResponse(json_encode([
            [
                'title' => 'Maverick',
                'labels' => [['name' => 'Status: Drowsy']],
            ]
        ]));

        $mockHttpClient = new MockHttpClient($mockResponse);

        $service = new GithubService($mockHttpClient, $this->createMock(LoggerInterface::class));
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Drowsy is an unknown status label!');
        $service->getHealthReport('Maverick');
    }
}