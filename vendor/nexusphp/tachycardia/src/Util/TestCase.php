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

use PHPUnit\Util\Test as TestUtil;

/**
 * An object representation of the TestCase class-string.
 *
 * @internal
 * @coversNothing
 */
final class TestCase
{
    /**
     * @var string
     *
     * @phpstan-var class-string
     */
    private $class;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $dataname;

    /**
     * The annotations array provided by \PHPUnit\Util\Test::parseMethodAnnotations.
     *
     * @var array<string, mixed>
     *
     * @phpstan-var array{'method'?: null|array<string, array<int, string>>, 'class'?:array<string, array<int, string>>}
     */
    private $annotations = [];

    /**
     * @phpstan-param class-string $class
     */
    public function __construct(string $class, string $name, string $dataname = '')
    {
        $this->class = $class;
        $this->name = $name;
        $this->dataname = $dataname;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getName(bool $withDataname = false): string
    {
        if ($withDataname) {
            return $this->name . $this->dataname;
        }

        return $this->name;
    }

    public function getTestName(bool $withDataname = true): string
    {
        return sprintf('%s::%s', $this->getClass(), $this->getName($withDataname));
    }

    /**
     * @return array<string, mixed>
     */
    public function getAnnotations(): array
    {
        if ([] === $this->annotations) {
            $this->annotations = TestUtil::parseTestMethodAnnotations($this->class, $this->name);
        }

        return $this->annotations;
    }

    public function hasClassAnnotation(string $key): bool
    {
        return $this->hasAnnotation('class', $key);
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return array<int, string>
     */
    public function getClassAnnotation(string $key): array
    {
        if (! $this->hasClassAnnotation($key)) {
            throw new \InvalidArgumentException(sprintf('Key "%s" not found in the class annotations.', $key)); // @codeCoverageIgnore
        }

        return $this->getAnnotations()['class'][$key];
    }

    public function hasMethodAnnotation(string $key): bool
    {
        return $this->hasAnnotation('method', $key);
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return array<int, string>
     */
    public function getMethodAnnotation(string $key): array
    {
        if (! $this->hasMethodAnnotation($key)) {
            throw new \InvalidArgumentException(sprintf('Key "%s" not found in the method annotations.', $key)); // @codeCoverageIgnore
        }

        return $this->getAnnotations()['method'][$key];
    }

    private function hasAnnotation(string $type, string $key): bool
    {
        if (! \in_array($type, ['method', 'class'], true)) {
            return false; // @codeCoverageIgnore
        }

        return isset($this->getAnnotations()[$type][$key]);
    }
}
