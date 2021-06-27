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

namespace Nexus\PHPUnit\Extension\Util;

/**
 * @internal
 */
final class TimeState
{
    /**
     * @var array<string, array<string, float>>
     */
    private $timeStates = [];

    /**
     * Constructor.
     *
     * @param array<string, array<string, float>> $timeStates
     */
    public function __construct(array $timeStates = [])
    {
        if ([] === $timeStates) {
            $GLOBALS['__TACHYCARDIA_TIME_STATES'] = $GLOBALS['__TACHYCARDIA_TIME_STATES'] ?? [];

            $this->timeStates = &$GLOBALS['__TACHYCARDIA_TIME_STATES'];

            return;
        }

        $this->timeStates = $timeStates;
    }

    public function __destruct()
    {
        if (isset($GLOBALS['__TACHYCARDIA_TIME_STATES'])) {
            unset($GLOBALS['__TACHYCARDIA_TIME_STATES']);
        }
    }

    /**
     * Retrieve all time states.
     *
     * @return array<string, array<string, float>>
     */
    public function retrieve(): array
    {
        return $this->timeStates;
    }

    /**
     * Finds the bare time for the test, with default to `$actual` if not set.
     * If `$test` is not set, `$actual` is returned, which can be null or float.
     * If `$actual` is null, returns the array of times for the test, which
     * includes the actual and bare times.
     *
     * @return null|array<string, float>|float
     */
    public function find(string $test, ?float $actual = null)
    {
        $testName = Parser::getInstance()->parseTest($test)->getTestName();
        $testName = md5($testName);

        if (null !== $actual) {
            if (isset($this->timeStates[$testName]) && ! isset($this->timeStates[$testName]['actual'])) {
                $this->timeStates[$testName]['actual'] = $actual;
            }
        }

        $result = $this->timeStates[$testName] ?? null;

        if (null === $result) {
            return $actual;
        }

        if (null === $actual) {
            return $result;
        }

        return $result['bare'] ?? $actual;
    }
}
