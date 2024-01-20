<?php

namespace Moonspot\Builder\Tests\TestClasses\Builder;

use Moonspot\Builder\Tests\TestClasses\Fleet as FleetObj;
use Moonspot\Builder\Tests\TestClasses\Builder\Car;
use Moonspot\Builder\Builder;

class Fleet extends Builder {

    public function create(array|object $data): FleetObj {

        $fleet = new FleetObj();

        $this->setValue($fleet, 'name', $data);

        $cars = $this->findValue(['cars', 'inventory'], $data);

        if (!empty($cars)) {
            foreach ($cars as $car) {
                $fleet->cars[] = Car::build($car);
            }
        }

        return $fleet;
    }
}

