<?php

namespace DoctrineDataManager\Tests;

use DoctrineDataManager\Fixture;
use PHPUnit\Framework\TestCase;

class FixtureTest extends TestCase
{
    private $fields = array(
        "sea" => array(
            "name"     => "Sea",
            "color"    => "navy",
            "playable" => false
        ),
        "town" => array(
            "name"     => "Town",
            "color"    => "tan",
            "playable" => true
        )
    );

    private $field = array(
        "name"     => "Pond",
        "color"    => "lakeblue",
        "playable" => true
    );

    public function testSetFixture()
    {
        $save = Fixture::set("map", $this->fields);
        $this->assertTrue($save);
    }

    public function testGetFixture()
    {
        $load = Fixture::get("map");
        $this->assertEquals($load, $this->fields);
    }

    public function testSetFixtureByKey()
    {
        $save = Fixture::set("map", $this->field, "sea");
        $this->assertTrue($save);
    }

    public function testGetFixtureByKey()
    {
        $load = Fixture::get("map", "sea");
        $this->assertEquals($load, $this->field);
    }
}