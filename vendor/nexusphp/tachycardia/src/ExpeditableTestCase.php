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

use PHPUnit\Framework\TestCase;

/**
 * An extension to TestCase using the Expeditable trait.
 * Used mainly in conjunction with `collectBare` option
 * on Tachycardia.
 */
abstract class ExpeditableTestCase extends TestCase
{
    use Expeditable;
}
