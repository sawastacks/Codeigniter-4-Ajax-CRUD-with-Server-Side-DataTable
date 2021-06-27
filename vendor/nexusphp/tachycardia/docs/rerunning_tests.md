# Rerunning slow tests to see if these are fast now

After Tachycardia exposes the slow tests in your test suite, you can now start to investigate further
on why these tests run so slow. You may optionally use a profiler like XDebug for this purpose. After
_fixing_ these slow tests, you may check directly whether these tests now run within your set time limits.

Simply copy the name of the test case and the associated data set, if any. Then paste and pass this as the
value to PHPUnit's `--filter` option.

1. Copy the name of the test class and method and the associated data set, if it uses data providers.

```
⚠  Took 5.0216s from 1.0000s limit to run Nexus\\PHPUnit\\Extension\\Tests\\TachycardiaTest::testWithProvider with data set \"slow\"
```

2. Paste it as the value to the `--filter` option:

```console
$ vendor/bin/phpunit --filter 'Nexus\\PHPUnit\\Extension\\Tests\\TachycardiaTest::testWithProvider with data set \"slow\"'
```

Note that PHPUnit uses single quotes for the value of the `--filter` option. Read more on
the [`--filter` option documentation](https://phpunit.readthedocs.io/en/9.5/textui.html?highlight=filter)
for all supported matching patterns.
