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

use Nexus\PHPUnit\Extension\Tachycardia;

/**
 * @internal
 */
final class GithubMonitor
{
    /**
     * @var array<string, string>
     *
     * @see https://github.com/actions/toolkit/blob/5e5e1b7aacba68a53836a34db4a288c3c1c1585b/packages/core/src/command.ts#L80-L85
     */
    private const ESCAPED_DATA = [
        '%' => '%25',
        "\r" => '%0D',
        "\n" => '%0A',
    ];

    /**
     * @var array<string, string>
     *
     * @see https://github.com/actions/toolkit/blob/5e5e1b7aacba68a53836a34db4a288c3c1c1585b/packages/core/src/command.ts#L87-L94
     */
    private const ESCAPED_PROPERTIES = [
        '%' => '%25',
        "\r" => '%0D',
        "\n" => '%0A',
        ':' => '%3A',
        ',' => '%2C',
    ];

    /**
     * Instance of Tachycardia;.
     *
     * @var Tachycardia
     */
    private $tachycardia;

    public function __construct(Tachycardia $tachycardia)
    {
        $this->tachycardia = $tachycardia;
    }

    public static function runningInGithubActions(): bool
    {
        return getenv('GITHUB_ACTIONS') !== false;
    }

    /**
     * Reports the slow tests as inline annotations in
     * the Github Actions work environment.
     */
    public function defibrillate(): void
    {
        foreach ($this->tachycardia->getSlowTests() as $test) {
            /** @phpstan-var class-string $class */
            [$class, $method] = explode('::', $test['label'], 2);
            $method = preg_replace('/^(test(?:\S+))(\s\S+)+/', '$1', $method) ?? '';

            try {
                $class = new \ReflectionClass($class);
                $method = $class->getMethod($method);
                // @codeCoverageIgnoreStart
            } catch (\ReflectionException $e) {
                continue;
                // @codeCoverageIgnoreEnd
            }

            $file = (string) $class->getFileName();
            $file = str_replace((string) getcwd(), '', $file);
            $line = (int) $method->getStartLine();
            $message = $this->recreateMessage($test);

            $this->warning($message, $file, $line);
        }
    }

    /**
     * Output a warning using the Github annotations format.
     *
     * @see https://docs.github.com/en/free-pro-team@latest/actions/reference/workflow-commands-for-github-actions#setting-a-warning-message
     */
    public function warning(string $message, string $file = '', int $line = 1, int $col = 0): void
    {
        $message = strtr($message, self::ESCAPED_DATA);

        if ('' === $file) {
            // @codeCoverageIgnoreStart
            printf('::warning::%s', $message);

            return;
            // @codeCoverageIgnoreEnd
        }

        printf(
            "::warning file=%s,line=%d,col=%d::%s\n",
            strtr($file, self::ESCAPED_PROPERTIES),
            $line,
            $col,
            $message,
        );
    }

    /**
     * Recreates the message given by Tachycardia::renderAsPlain() without
     * the ANSI codes and warning sign.
     *
     * @param array<string, mixed> $testDetails
     */
    private function recreateMessage(array $testDetails): string
    {
        ['label' => $label, 'time' => $time, 'limit' => $limit] = $testDetails;
        $precision = $this->tachycardia->getPrecision();

        return sprintf(
            'Took %s from %s limit to run %s',
            number_format($time, $precision) . 's',
            number_format($limit, $precision) . 's',
            addslashes($label),
        );
    }
}
