# Custom Time Limits

## Setting custom time limits per test

There will be instances that execution times of some tests will definitely exceed the configured time limit.
To prevent false positives, it is possible to provide these long-running tests with their own time limits.

You can simply annotate the test method with `@timeLimit` followed by the number of seconds (figures only).
This can be higher or lower than the default time limit and will be used instead.

```php
/**
 * This test will have a time limit of 5 seconds instead of
 * the default 1 second.
 *
 * @timeLimit 5.0
 */
public function testLongRunningCodeBeingTested(): void
{
    // Logic of long running code
}

```

## Setting custom time limits per class

If you are feeling lazy and want to set a time limit applicable for the whole class, you can do so by
including a class-wide `@timeLimit` annotation. This works the same way as with method-level time limits.

```php
/**
 * @timeLimit 3.0
 */
class FooTakesLongToTest
{
    public function testOne(): void {}
    public function testTwo(): void {}
}

```

Please be guided that if both method-level and class-level time limit annotations exist, then the method-level
annotation will take precedence.

The order of precedence is: `method-level annotation > class-level annotation > default time limit`

## Disabling time limits per test or per class

There may be instances where you do not want to include a particular test case or class from slow test
profiling. One reason is that you do not want to be burdened first of the existing slow tests and just fix
"for now" the emerging slow tests. Whatever reason that may be, you can disable the profiling by using
the `@noTimeLimit` annotation. This can be placed either in the test case or in the test class.

```php
// method-level disabling
final class FooTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @noTimeLimit
     */
    public function testExtremelySlowTest(): void {}
}

// class-level disabling
/**
 * @noTimeLimit
 */
final class BarTest extends \PHPUnit\Framework\TestCase
{
    public function testSluggishTest(): void {}
}
```

Method-level disabling takes precedence from class-level disabling. Moreover, if you have a `@noTimeLimit`
applied to a test case, either through the method or the class, and a custom `@timeLimit` applied also to
this test case, **THE `@noTimeLIMIT` ANNOTATION WILL TAKE PRECEDENCE**.
