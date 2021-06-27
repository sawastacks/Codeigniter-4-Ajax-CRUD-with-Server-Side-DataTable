# Nexus Tachycardia

[![PHP version](https://img.shields.io/packagist/php-v/nexusphp/tachycardia)](https://php.net)
![build](https://github.com/NexusPHP/tachycardia/actions/workflows/build.yml/badge.svg?branch=develop)
[![Coverage Status](https://coveralls.io/repos/github/NexusPHP/tachycardia/badge.svg?branch=develop)](https://coveralls.io/github/NexusPHP/tachycardia?branch=develop)
[![PHPStan](https://img.shields.io/badge/PHPStan-max%20level-brightgreen)](phpstan.neon.dist)
[![Latest Stable Version](https://poser.pugx.org/nexusphp/tachycardia/v)](//packagist.org/packages/nexusphp/tachycardia)
[![License](https://img.shields.io/github/license/nexusphp/tachycardia)](LICENSE)
[![Total Downloads](https://poser.pugx.org/nexusphp/tachycardia/downloads)](//packagist.org/packages/nexusphp/tachycardia)

**Tachycardia** is a PHPUnit extension that detects and reports slow running tests and prints them
right in your console. It can also optionally inline annotate the specific tests in the files
during pull requests.

**NOTE:** Tachycardia will only detect the slow tests in your test suites but will offer no explanation
as to why these identified are slow. You should use a dedicated profiler for these instead.

```console
$ vendor/bin/phpunit
PHPUnit 9.5.4 by Sebastian Bergmann and contributors.

Runtime:       PHP 8.0.3 with Xdebug 3.0.3
Configuration: /home/runner/work/tachycardia/tachycardia/phpunit.xml.dist

...................................                               35 / 35 (100%)

Nexus\PHPUnit\Extension\Tachycardia identified these 14 slow tests:
⚠  Took 7.0003s from 1.0000s limit to run Nexus\\PHPUnit\\Extension\\Tests\\Live\\SlowTestsTest::testWithProvider with data set \"slowest\"
⚠  Took 6.0003s from 1.0000s limit to run Nexus\\PHPUnit\\Extension\\Tests\\Live\\SlowTestsTest::testWithProvider with data set \"slower\"
⚠  Took 5.0004s from 1.0000s limit to run Nexus\\PHPUnit\\Extension\\Tests\\Live\\SlowTestsTest::testWithProvider with data set \"slow\"
⚠  Took 4.0004s from 1.0000s limit to run Nexus\\PHPUnit\\Extension\\Tests\\Live\\SlowTestsTest::testSlowestTest
⚠  Took 3.0004s from 1.0000s limit to run Nexus\\PHPUnit\\Extension\\Tests\\Live\\SlowTestsTest::testSlowerTest
⚠  Took 2.5040s from 2.0000s limit to run Nexus\\PHPUnit\\Extension\\Tests\\Live\\ClassAnnotationsTest::testSlowTestUsesClassTimeLimit
⚠  Took 2.0003s from 1.0000s limit to run Nexus\\PHPUnit\\Extension\\Tests\\Live\\SlowTestsTest::testSlowTest
⚠  Took 1.5012s from 1.0000s limit to run Nexus\\PHPUnit\\Extension\\Tests\\Live\\NoTimeLimitInMethodTest::testSlowTestNotDisabled
⚠  Took 1.0004s from 0.5000s limit to run Nexus\\PHPUnit\\Extension\\Tests\\Live\\SlowTestsTest::testCustomLowerLimit
⚠  Took 0.9012s from 0.5000s limit to run Nexus\\PHPUnit\\Extension\\Tests\\Live\\WithDataProvidersTest::testSlowProvidedTestRespectsTimeLimit with data set #4
⚠  Took 0.8011s from 0.5000s limit to run Nexus\\PHPUnit\\Extension\\Tests\\Live\\WithDataProvidersTest::testSlowProvidedTestRespectsTimeLimit with data set #3
⚠  Took 0.7011s from 0.5000s limit to run Nexus\\PHPUnit\\Extension\\Tests\\Live\\WithDataProvidersTest::testSlowProvidedTestRespectsTimeLimit with data set #2
⚠  Took 0.6012s from 0.5000s limit to run Nexus\\PHPUnit\\Extension\\Tests\\Live\\WithDataProvidersTest::testSlowProvidedTestRespectsTimeLimit with data set #1
⚠  Took 0.5513s from 0.5000s limit to run Nexus\\PHPUnit\\Extension\\Tests\\Live\\WithDataProvidersTest::testSlowProvidedTestRespectsTimeLimit with data set #0


Time: 00:43.251, Memory: 16.00 MB

OK (35 tests, 55 assertions)

Generating code coverage report in Clover XML format ... done [00:00.004]

Generating code coverage report in HTML format ... done [00:00.038]
```

## Installation

Tachycardia should only be installed as a development-time dependency to aid in
running your project's test suite. You can install using [Composer](https://getcomposer.org):

    composer require --dev nexusphp/tachycardia

## Configuration

Tachycardia supports these parameters:

- **timeLimit** - Time limit in seconds to be enforced for all tests. All tests exceeding
    this amount will be considered as slow. ***Default: 1.00***
- **reportable** - Number of slow tests to be displayed in the console report. This is ignored
    on Github Actions report. ***Default: 10***
- **precision** - Degree of precision of the decimals of the test's consumed time and allotted
    time limit. ***Default: 4***
- **tabulate** - Boolean flag whether the console report should be displayed in a tabular format
    or just displayed as plain. ***Default: false***
- **collectBare** - Boolean flag whether collected times should be free of the hook
    methods' times. Turning this on requires using the `Expeditable` trait or extending
    the `ExpeditableTestCase` class. ***Default: false***

To use the extension with its default configuration options, you can simply add the following
into your `phpunit.xml.dist` or `phpunit.xml` file.

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:noNamespaceSchemaLocation="vendor/phpunit/phpunit/phpunit.xsd"
         bootstrap="vendor/autoload.php"
         cacheResultFile="build/.phpunit.cache/test-results"
         colors="true"
         executionOrder="depends,defects"
         beStrictAboutOutputDuringTests="true"
         beStrictAboutTodoAnnotatedTests="true"
         failOnRisky="true"
         failOnWarning="true"
         verbose="true">

    <!-- Your other phpunit configurations here -->

    <extensions>
        <extension class="Nexus\PHPUnit\Extension\Tachycardia" />
    </extensions>
</phpunit>
```

Now, run `vendor/bin/phpunit`. If there are test cases where the time consumed exceeds the configured
time limits, these will be displayed in the console after all tests have completed.

If you wish to customize one or more of the available options, you can just change the entry in your
`phpunit.xml.dist` or `phpunit.xml` file.

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:noNamespaceSchemaLocation="vendor/phpunit/phpunit/phpunit.xsd"
         bootstrap="vendor/autoload.php"
         cacheResultFile="build/.phpunit.cache/test-results"
         colors="true"
         executionOrder="depends,defects"
         beStrictAboutOutputDuringTests="true"
         beStrictAboutTodoAnnotatedTests="true"
         failOnRisky="true"
         failOnWarning="true"
         verbose="true">

    <!-- Your other phpunit configurations here -->

    <extensions>
        <extension class="Nexus\PHPUnit\Extension\Tachycardia">
            <arguments>
                <array>
                    <element key="timeLimit">
                        <double>1.00</double>
                    </element>
                    <element key="reportable">
                        <integer>10</integer>
                    </element>
                    <element key="precision">
                        <integer>4</integer>
                    </element>
                    <element key="tabulate">
                        <boolean>false</boolean>
                    </element>
                    <element key="collectBare">
                        <boolean>false</boolean>
                    </element>
                </array>
            </arguments>
        </extension>
    </extensions>
</phpunit>
```

## Documentation

- [Reporting Slow Tests](docs/enable_reporting.md)
    - [Enable/disable console reporting using environment variable](docs/enable_reporting.md#enabledisable-console-reporting-using-environment-variable)
    - [Enable/disable profiling in Github Actions](docs/enable_reporting.md#enabledisable-profiling-in-github-actions)
- [Custom Time Limits](docs/custom_time_limits.md)
    - [Setting custom time limits per test](docs/custom_time_limits.md#setting-custom-time-limits-per-test)
    - [Setting custom time limits per class](docs/custom_time_limits.md#setting-custom-time-limits-per-class)
    - [Disabling time limits per test or per class](docs/custom_time_limits.md#disabling-time-limits-per-test-or-per-class)
- [Tabulating results instead of plain render](docs/tabulating_results.md)
- [Rerunning slow tests to see if these are fast now](docs/rerunning_tests.md)
- [Limiting slow test profiling to the actual test case](docs/limiting_test_times.md)

## Contributing

Contributions are very much welcome. If you see an improvement or bug fix,
open a [PR](https://github.com/NexusPHP/tachycardia/pulls) now!

Read more on the [Contributing to Nexus Tachycardia](.github/CONTRIBUTING.md).

## Inspiration

Tachycardia was inspired from [`johnkary/phpunit-speedtrap`](https://github.com/johnkary/phpunit-speedtrap),
but injected with anabolic steroids.

Tachycardia is actually a [medical term](https://www.webmd.com/heart-disease/atrial-fibrillation/what-are-the-types-of-tachycardia)
referring to a heart rate that exceeds the normal resting rate in general of over 100 beats per minute.

## License

This library is licensed under the [MIT License](LICENSE).
