<?php

namespace Moonspot\Builder\Tests\TestClasses\Builder;

use Moonspot\Builder\Tests\TestClasses\Car as CarObj;
use Moonspot\Builder\Builder;

class Car extends Builder {

    public function create(array|object $data): CarObj {

        $car = new CarObj();

        $this->setValue($car, 'color', $data);
        $this->setValue($car, 'capacity', $data);
        $this->setValue($car, 'license_number', $data, ['plate_number'], false);

        return $car;
    }
}

