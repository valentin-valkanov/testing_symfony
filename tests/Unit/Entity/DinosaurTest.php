<?php declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Dinosaur;
use PHPUnit\Framework\TestCase;

class DinosaurTest extends TestCase
{
    public function testItWorks(): void
    {
        $dino = new Dinosaur('Rex');

        $this->assertInstanceOf(Dinosaur::class, $dino);
    }
}