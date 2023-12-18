<?php

declare(strict_types=1);

namespace App\tests\Helper;

use App\Models\Helper\CommissionHelper;
use PHPUnit\Framework\TestCase;

class CommissionHelperTest extends TestCase
{
    public function testCommissionForEu(): void
    {
        $this->assertEquals(CommissionHelper::getRate('LT'), CommissionHelper::FOR_EU_COMMISSION);
    }
    public function testCommissionForNonEu(): void
    {
        $this->assertEquals(CommissionHelper::getRate('UA'), CommissionHelper::FOR_NON_EU_COMMISSION);
    }
}
