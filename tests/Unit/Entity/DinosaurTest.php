<?php declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Dinosaur;
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
}