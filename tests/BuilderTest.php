<?php

namespace Moonspot\Builder\Tests;

use Moonspot\Builder\Tests\TestClasses\Car;
use Moonspot\Builder\Tests\TestClasses\Fleet;
use Moonspot\Builder\Tests\TestClasses\Builder\Car as CarBuilder;
use Moonspot\Builder\Tests\TestClasses\Builder\Fleet as FleetBuilder;

class BuilderTest extends \PHPUnit\Framework\TestCase {

    /**
     * @dataProvider createData
     */
    public function testCreate($class, $input, $expect) {
        $obj = $class::build($input);
        $this->assertEquals($expect, $obj);
    }

    public function createData() {

        $car                 = new Car();
        $car->color          = 'blue';
        $car->capacity       = 10;
        $car->license_number = 'ASDF1234';

        $fleet = new Fleet();
        $fleet->name = 'fleet1';
        $fleet->cars = [$car];

        $car2 = clone $car;
        $car2->license_number = null;

        $car3 = clone $car;
        $car3->capacity = 0;

        return [

            'Simple' => [
                CarBuilder::class,
                [
                    'color'          => 'blue',
                    'capacity'       => 10,
                    'license_number' => 'ASDF1234',
                ],
                $car
            ],

            'Missing Data' => [
                CarBuilder::class,
                [
                    'color'          => 'blue',
                    'license_number' => 'ASDF1234',
                ],
                $car3
            ],

            'Simple Object Input' => [
                CarBuilder::class,
                (object)[
                    'color'          => 'blue',
                    'capacity'       => 10,
                    'license_number' => 'ASDF1234',
                ],
                $car
            ],

            'Nested' => [
                FleetBuilder::class,
                [
                    'name' => 'fleet1',
                    'cars' => [
                        [
                            'color'          => 'blue',
                            'capacity'       => 10,
                            'license_number' => 'ASDF1234',
                        ],
                    ]
                ],
                $fleet,
            ],

            'Alt Property Name' => [
                CarBuilder::class,
                [
                    'color'        => 'blue',
                    'capacity'     => 10,
                    'plate_number' => 'ASDF1234',
                ],
                $car
            ],

            'Empty Value' => [
                CarBuilder::class,
                [
                    'color'        => 'blue',
                    'capacity'     => 10,
                    'plate_number' => '',
                ],
                $car2
            ],
        ];
    }
}
