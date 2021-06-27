<?php

declare(strict_types=1);

/**
 * This file is part of Nexus Tachycardia.
 *
 * (c) 2021 John Paul E. Balandan, CPA <paulbalandan@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Nexus\PHPUnit\Extension;

use Nexus\PHPUnit\Extension\Util\Parser;
use PHPUnit\Util\Test as TestUtil;
use SebastianBergmann\Timer\Timer;

/**
 * The trait is used to hook into `PHPUnit\Framework\TestCase`'s
 * `runTest` method to obtain a streamlined benchmarking of its
 * execution time.
 */
trait Expeditable
{
    /**
     * Overridden to run the test and assert its executed time state.
     *
     * @throws \SebastianBergmann\ObjectEnumerator\InvalidArgumentException
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Throwable
     *
     * @return mixed
     */
    protected function runTest()
    {
        $timer = new Timer();
        $timer->start();

        $result = parent::runTest();
        $time = $timer->stop()->asSeconds();

        $this->store($time);

        return $result;
    }

    /**
     * Stores the "slim" execution time of the test case into
     * the global `$__TACHYCARDIA_TIME_STATES` array.
     */
    private function store(float $time): void
    {
        $testName = TestUtil::describeAsString($this);
        $testName = Parser::getInstance()->parseTest($testName)->getTestName();
        $testName = md5($testName);

        if (! isset($GLOBALS['__TACHYCARDIA_TIME_STATES'][$testName])) {
            $GLOBALS['__TACHYCARDIA_TIME_STATES'][$testName] = [];
        }

        $GLOBALS['__TACHYCARDIA_TIME_STATES'][$testName]['bare'] = $time;
    }
}
