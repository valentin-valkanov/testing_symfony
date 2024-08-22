<?php declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Enum\HealthStatus;
use App\Service\GithubService;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class GithubServiceTest extends TestCase
{
    private LoggerInterface $mockLogger;
    private MockHttpClient $mockHttpClient;
    private MockResponse $mockResponse;

    protected function setUp(): void
    {
        $this->mockLogger = $this->createMock(LoggerInterface::class);
        $this->mockHttpClient = new MockHttpClient();

    }

    /**
     * @dataProvider dinoNameProvider
     */
    public function testGetHealthReportReturnCorrectHealthStatusForDino(HealthStatus $expectedStatus, string$dinoName): void
    {
        $mockLogger = $this->createMock(LoggerInterface::class);
        $mockHttpClient = $this->createMock(HttpClientInterface::class);
        $mockResponse = $this->createMock(ResponseInterface::class);

        $service = $this->createGitHubService([
            [
                'title' => 'Daisy',
                'labels' => [['name' => 'Status: Sick']]

            ],
            [
                'title' => 'Maverick',
                'labels' => [['name' => 'Status: Healthy']]

            ]
        ]);

        $this->assertSame($expectedStatus, $service->getHealthReport($dinoName));
        $this->assertSame(1, $this->mockHttpClient->getRequestsCount());
        $this->assertSame('GET', $this->mockResponse->getRequestMethod());
        $this->assertSame('https://api.github.com/repos/SymfonyCasts/dino-park/issues', $this->mockResponse->getRequestUrl());
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
        $service = $this->createGitHubService([
            [
                'title' => 'Maverick',
                'labels' => [['name' => 'Status: Drowsy']],
            ]
        ]);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Drowsy is an unknown status label!');
        $service->getHealthReport('Maverick');
    }

    private function createGitHubService(array $responseData): GithubService
    {
        $this->mockResponse = new MockResponse(json_encode($responseData));

        $this->mockHttpClient->setResponseFactory($this->mockResponse);

        return new GithubService($this->mockHttpClient, $this->mockLogger);
    }
}