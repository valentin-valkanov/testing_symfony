<?php declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Dinosaur;
use App\Enum\HealthStatus;
use PHPUnit\Framework\TestCase;

class DinosaurTest extends TestCase
{
    public function testCanGetAndSetData(): void
    {
        $dino = new Dinosaur(
            name: 'Big Eaty',
            genus: 'Tyrannosaurus',
            length: 15,
            enclosure: 'Paddock A',
        );

        $this->assertInstanceOf(Dinosaur::class, $dino);
        $this->assertSame('Big Eaty', $dino->getName());
        $this->assertSame('Tyrannosaurus', $dino->getGenus());
        $this->assertSame(15, $dino->getLength());
        $this->assertSame('Paddock A', $dino->getEnclosure());
    }
    /**
     * @dataProvider typeSizeProvider
     */
    public function testDinosaurHasCorrectTypeSizeFromLength(int $length, string $expectedSize): void
    {
        //Arrange
        $dino = new Dinosaur(
            name: 'Big Eaty',
            length: $length,
        );

        //Act
        $size = $dino->DefineTypeSize();

        //Assert
        $this->assertSame($expectedSize, $size);
    }

    public function typeSizeProvider(): \Generator
    {
        yield '10 Meter Large Dino' => [10, 'Large'];
        yield '5 Meter Medium Dino' => [5, 'Medium'];
        yield '4 Meter Small Dino' => [4, 'Small'];
    }

    public function testIsAcceptingVisitorsByDefault(): void
    {
        $dino = new Dinosaur('Dennis');

        $this->assertTrue($dino->isAcceptingVisitors());
    }

    /** @dataProvider healthStatusProvider */
    public function testIsAcceptingVisitorsBasedOnHealthStatus(HealthStatus $healthStatus, bool $expectedVisitorStatus): void
    {
        $dino = new Dinosaur('Bumpy');
        $dino->setHealth($healthStatus);

        $this->assertSame($expectedVisitorStatus, $dino->isAcceptingVisitors());
    }

    public function healthStatusProvider(): \Generator
    {
        yield 'Sick dino is not accepting visitors' => [HealthStatus::SICK, false];
        yield 'Hungry dino is accepting visitors' => [HealthStatus::HUNGRY, true];
    }
}