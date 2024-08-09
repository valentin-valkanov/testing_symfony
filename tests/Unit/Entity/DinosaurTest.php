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

    public function testDinosaurOver10MetersOrGreaterIsLarge(): void
    {
        //Arrange
        $dino = new Dinosaur(
            name: 'Big Eaty',
            length: 15,
        );

        //Act
        $size = $dino->DefineTypeSize();

        //Assert
        $this->assertSame('Large', $size);
    }

    public function testDinosaurBetween5and9MetersOrGreaterIsMedium(): void
    {
        //Arrange
        $dino = new Dinosaur(
            name: 'Big Mami',
            length: 9,
        );

        //Act
        $size = $dino->DefineTypeSize();

        //Assert
        $this->assertSame('Medium', $size, 'This is supposed to be a Medium Dino');
    }

    public function testDinosaurUnder5MetersIsSmall(): void
    {
        //Arrange
        $dino = new Dinosaur(
            name: 'Big Mami',
            length: 4,
        );

        //Act
        $size = $dino->defineTypeSize();

        //Assert
        $this->assertSame('Small', $size, 'This is supposed to be a Small Dino');
    }
}