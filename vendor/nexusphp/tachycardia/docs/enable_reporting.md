# Reporting Slow Tests

## Enable/disable console reporting using environment variable

Tachycardia is configured to hook into PHPUnit once it is included in your XML file. You can, however,
control this behavior by introducing the `TACHYCARDIA_MONITOR` environment variable.

### 1. Disable in development but enable on Github Actions

Add the `env` element to your `phpunit.xml.dist` file disabling Tachycardia then enable this on Actions:

```xml
<!-- phpunit.xml.dist -->
<phpunit bootstrap="vendor/autoload.php">
    <!-- Other configurations -->

    <php>
        <env name="TACHYCARDIA_MONITOR" value="disabled" />
    </php>

    <extensions>
        <extension class="Nexus\PHPUnit\Extension\Tachycardia" />
    </extensions>
</phpunit>
```

```yaml
# your build workflow
- name: Run test suite
  run: vendor/bin/phpunit --color=always
  env:
    TACHYCARDIA_MONITOR: enabled
```

### 2. Enable in development but disable in Github Actions

```xml
<!-- phpunit.xml.dist -->
<phpunit bootstrap="vendor/autoload.php">
    <!-- Other configurations -->

    <extensions>
        <extension class="Nexus\PHPUnit\Extension\Tachycardia" />
    </extensions>
</phpunit>
```

```yaml
# your build workflow
- name: Run test suite
  run: vendor/bin/phpunit --color=always
  env:
    TACHYCARDIA_MONITOR: disabled
```

### 3. Disable profiling and enable only on demand

```xml
<!-- phpunit.xml.dist -->
<phpunit bootstrap="vendor/autoload.php">
    <!-- Other configurations -->

    <php>
        <env name="TACHYCARDIA_MONITOR" value="disabled" />
    </php>

    <extensions>
        <extension class="Nexus\PHPUnit\Extension\Tachycardia" />
    </extensions>
</phpunit>
```

When running `vendor/bin/phpunit` either from the terminal or from Github Actions, just pass the variable
like this:

```console
$ TACHYCARDIA_MONITOR=enabled vendor/bin/phpunit
```

## Enable/disable profiling in Github Actions

Profiling in development for the Github Actions is **disabled** by default because the console cannot
interpret the special workflow commands used by Github Actions. Using the `TACHYCARDIA_MONITOR_GA`
variable, you can enable it by exporting `TACHYCARDIA_MONITOR_GA=enabled`. To disable, just export
`TACHYCARDIA_MONITOR_GA=disabled`.

The steps here are similar to above procedures for setting `TACHYCARDIA_MONITOR` variable.
