
# Limiting slow test profiling to the actual test case

By default, PHPUnit benchmarks the execution of test cases starting from the pre-hook methods
(`setUpBeforeClass`, `setUp`, other `@before` methods), then the test case, and finally the post-hook
methods (`tearDown`, `tearDownAfterClass`, other `@after` methods). PHPUnit does this for each test.

In the interest of our desire to identify slow tests, we should be aware that there are instances
that these hook methods are used for initialization and cleanup of variables used in the tests (e.g.,
establishing a connection to the database, hydrating needed objects, etc.). Depending on these factors,
the load time to first test execution can be substantially long, adding bloat to the reported times
to a simple test.

Tachycardia offers a solution for those who want to isolate their tests from variable latency. This is
a two-step solution that requires using either the `Expeditable` trait or extending
the `ExpeditableTestCase` abstract class and then turning on the `$collectBare` option.

1. Turn on the `$collectBare` option in your phpunit.xml.dist file by setting it to `true`.
```xml
<!-- phpunit.xml.dist -->
<phpunit bootstrap="vendor/autoload.php">
    <!-- Other configurations -->

    <extensions>
        <extension class="Nexus\PHPUnit\Extension\Tachycardia">
            <arguments>
                <array>
                    ...
                    <element key="collectBare">
                        <boolean>true</boolean>
                    </element>
                </array>
            </arguments>
        </extension>
    </extensions>
</phpunit>
```

2. Either use the trait or extending the abstract class.
```php
// using the trait
use Nexus\PHPUnit\Extension\Expeditable;
use PHPUnit\Framework\TestCase;

final class FooTest extends TestCase
{
    use Expeditable;

    // ..
}

/* ------------------------------------------------- */

// using the abstract class
use Nexus\PHPUnit\Extension\ExpeditableTestCase;

final class FooTest extends ExpeditableTestCase
{
    // ..
}

```
