<?php

namespace App\Test\Model\Entity;

use App\Model\Entity\Ingredient;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class IngredientTest extends TestCase {
    public function testCreate() : void {
        $oak = new Ingredient("Oak Wood", "Sturdy oak wood from the Alps");
        Assert::assertEquals($oak->getName(), "Oak Wood");
        Assert::assertEquals($oak->getDescription(), "Sturdy oak wood from the Alps");
        Assert::assertNotNull($oak->getId());
    }
}