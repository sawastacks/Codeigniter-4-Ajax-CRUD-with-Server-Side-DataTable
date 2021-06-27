# Tabulating results instead of plain render

If you want to have the console report displayed in tables, you can set the `tabulate` option to true
in the `phpunit.xml.dist` file.

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit bootstrap="vendor/autoload.php">
...
    <extensions>
        <extension class="Nexus\PHPUnit\Extension\Tachycardia">
            <arguments>
                <array>
                    ...
                    <element key="tabulate">
                        <boolean>true</boolean>
                    </element>
                </array>
            </arguments>
        </extension>
    </extensions>
</phpunit>
```

Running `vendor/bin/phpunit` will now yield the report similar to this:

```console
$ vendor/bin/phpunit
PHPUnit 9.5.3 by Sebastian Bergmann and contributors.

Runtime:       PHP 8.0.3 with Xdebug 3.0.3
Configuration: /var/www/tachycardia/phpunit.xml.dist

....S.........                                                    14 / 14 (100%)

Nexus\PHPUnit\Extension\Tachycardia identified these 7 slow tests:
+-----------------------------------------------------------------------------------------------+---------------+---------------+
| Test Case                                                                                     | Time Consumed | Time Limit    |
+-----------------------------------------------------------------------------------------------+---------------+---------------+
| Nexus\\PHPUnit\\Extension\\Tests\\TachycardiaTest::testWithProvider with data set \"slowest\" | 00:00:07.0053 | 00:00:01.0000 |
| Nexus\\PHPUnit\\Extension\\Tests\\TachycardiaTest::testWithProvider with data set \"slower\"  | 00:00:06.0110 | 00:00:01.0000 |
| Nexus\\PHPUnit\\Extension\\Tests\\TachycardiaTest::testWithProvider with data set \"slow\"    | 00:00:05.0114 | 00:00:01.0000 |
| Nexus\\PHPUnit\\Extension\\Tests\\TachycardiaTest::testSlowestTest                            | 00:00:04.0176 | 00:00:01.0000 |
| Nexus\\PHPUnit\\Extension\\Tests\\TachycardiaTest::testSlowerTest                             | 00:00:03.0104 | 00:00:01.0000 |
| Nexus\\PHPUnit\\Extension\\Tests\\TachycardiaTest::testSlowTest                               | 00:00:02.0107 | 00:00:01.0000 |
| Nexus\\PHPUnit\\Extension\\Tests\\TachycardiaTest::testCustomLowerLimit                       | 00:00:01.0186 | 00:00:00.5000 |
+-----------------------------------------------------------------------------------------------+---------------+---------------+


Time: 00:31.574, Memory: 8.00 MB

There was 1 skipped test:

1) Nexus\PHPUnit\Extension\Tests\TachycardiaTest::testWithGithubActionReporting
This should be tested in Github Actions.

/var/www/tachycardia/tests/TachycardiaTest.php:95

OK, but incomplete, skipped, or risky tests!
Tests: 14, Assertions: 21, Skipped: 1.

Generating code coverage report in Clover XML format ... done [00:00.526]

Generating code coverage report in HTML format ... done [00:10.317]
```
