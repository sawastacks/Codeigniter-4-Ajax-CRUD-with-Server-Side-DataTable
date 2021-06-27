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
final class Parser
{
    /**
     * @var string
     */
    public const REGEX_TEST_CASE_NAME = '/^(?:(?P<class>[A-Z][A-Za-z0-9_\\\\]+)::(?P<name>\S+))(?:(?P<dataname> with data set (?:#\d+|"[^"]+"))\s\()?/u';

    /**
     * @var Parser
     */
    private static $instance;

    /**
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    public static function getInstance(): self
    {
        self::$instance = self::$instance ?? new self();

        return self::$instance;
    }

    public function parseTest(string $test): TestCase
    {
        $matches = [];

        preg_match(self::REGEX_TEST_CASE_NAME, $test, $matches);

        return new TestCase($matches['class'], $matches['name'], $matches['dataname'] ?? '');
    }
}
