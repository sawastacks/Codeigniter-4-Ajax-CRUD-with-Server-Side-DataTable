@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../symplify/easy-testing/bin/easy-testing
php "%BIN_TARGET%" %*
