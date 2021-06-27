@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../rector/rector/bin/rector
php "%BIN_TARGET%" %*
