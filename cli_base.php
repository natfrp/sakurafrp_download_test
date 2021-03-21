<?php
function log_msg($val, ...$args)
{
    global $_LOG_FILE;
    $val = sprintf($val, ...$args) . PHP_EOL;
    echo ($val);
    if (!empty($_LOG_FILE)) {
        file_put_contents($_LOG_FILE, $val, FILE_APPEND);
    }
}

function log_I($val, ...$args)
{
    log_msg('[INFO] ' . $val, ...$args);
}

function log_W($val, ...$args)
{
    log_msg('[WARNING] ' . $val, ...$args);
}

function log_E($val, ...$args)
{
    log_msg('[ERROR] ' . $val, ...$args);
}

if (php_sapi_name() != 'cli') {
    unset($_LOG_FILE);
    log_E('This is a command line tool!');
    exit(1);
}

if (!empty($_LOG_FILE)) {
    file_put_contents($_LOG_FILE, 'Started on ' . date('Y-m-d H:i:s') . PHP_EOL);
}

set_time_limit(0);
ini_set('memory_limit', '4G');

set_error_handler(function (int $nSeverity, string $strMessage, string $strFilePath, int $nLineNumber) {
    if (error_reporting() !== 0) // Not error suppression operator @
        throw new \ErrorException($strMessage, /*nExceptionCode*/ 0, $nSeverity, $strFilePath, $nLineNumber);
}, E_ALL);
