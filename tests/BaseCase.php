<?php

namespace DealNews\Core\Tests\Data\Builder;

abstract class BaseCase extends \PHPUnit\Framework\TestCase {
    protected function assertBuild($expect, $obj) {
        foreach ($expect as $key => $expected_value) {
            $value = $obj->$key;
            if (is_object($value)) {
                $this->assertInstanceOf(
                    $expected_value,
                    $value,
                    "Expected $expected_value, object is an instance of " . get_class($value)
                );
            } else {
                $this->assertEquals(
                    $expected_value,
                    $value
                );
            }
        }
    }
}
