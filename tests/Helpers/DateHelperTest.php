<?php

namespace Ag84ark\SmartBill\Tests\Helpers;

use Ag84ark\SmartBill\Exceptions\InvalidDateException;
use Ag84ark\SmartBill\Helpers\DateHelper;
use PHPUnit\Framework\TestCase;

class DateHelperTest extends TestCase
{
    /** @test */
    public function it_returns_true_for_valid_date()
    {
        $this->assertTrue(DateHelper::isValidDate('2022-12-31'));
    }

    /** @test */
    public function it_returns_false_for_invalid_date()
    {
        $this->assertFalse(DateHelper::isValidDate('invalid-date'));
    }

    /** @test */
    public function it_returns_formatted_date_for_valid_date()
    {
        $this->assertEquals('2022-12-31', DateHelper::getYMD('2022-12-31'));
    }

    /** @test */
    public function it_throws_exception_for_invalid_date()
    {
        $this->expectException(InvalidDateException::class);
        DateHelper::getYMD('invalid-date');
    }
}
