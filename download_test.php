<?php
include('cli_base.php');

define('FRPC_VERSION', '0.37.0-sakura-1');
define('LAUNCHER_VERSION', '2.0.4.0');

define('HASH_FILE', 'hash.json');
define('DOWNLOAD_FILE', 'downloads.json');

define('DOWNLOAD_BASE', 'https://azure.globalslb.net/frp/');

$_LAUNCHER_FILES = array(
    'SakuraLauncher.exe',
    'LegacyUpdate.zip',
    'SakuraUpdate.zip'
);

$_FRPC_FILES = array(
    'frpc_darwin_amd64',
    'frpc_darwin_arm64',
    'frpc_freebsd_386',
    'frpc_freebsd_amd64',
    'frpc_linux_386',
    'frpc_linux_amd64',
    'frpc_linux_arm_garbage',
    'frpc_linux_armv7',
    'frpc_linux_arm64',
    'frpc_linux_mips',
    'frpc_linux_mips64',
    'frpc_linux_mips64le',
    'frpc_linux_mipsle',
    'frpc_windows_386.exe',
    'frpc_windows_386.zip',
    'frpc_windows_amd64.exe',
    'frpc_windows_amd64.zip',
    'frpc_windows_arm64.exe',
    'frpc_windows_arm64.zip'
);

if (!is_array($hash_data = @json_decode(file_get_contents(HASH_FILE), true))) {
    log_E('Bad ' . HASH_FILE);
    exit(1);
}

if (!is_array($download_data = @json_decode(file_get_contents(DOWNLOAD_FILE), true))) {
    log_E('Bad ' . DOWNLOAD_FILE);
    exit(1);
}

function save()
{
    global $hash_data, $download_data;
    file_put_contents(HASH_FILE, json_encode($hash_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    file_put_contents(DOWNLOAD_FILE, json_encode($download_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

function &find_download(&$data, $file)
{
    foreach ($data as &$d) {
        if (isset($d['url']) && $file == explode('?', basename($d['url']))[0]) {
            return $d;
        }
    }
    $null = null;
    return $null;
}

function test_file($file)
{
    global $hash_data, $download_data, $version;

    if (!isset($hash_data[$file])) {
        return;
    }
    $hash = strtolower($hash_data[$file]['md5']);

    $d_ref = &find_download($download_data['frpc'], $file);
    if ($d_ref === null) {
        $d_ref = &find_download($download_data['launcher'], $file);
        if ($d_ref === null) {
            return;
        }
    }

    log_I('Validating ' . $version . '/' . $file);

    $test = md5(file_get_contents(DOWNLOAD_BASE . $version . '/' . $file . '?dl_test=' . time()));
    if ($test != $hash) {
        log_E(' Mismatch! Actual [' . $hash . '], Except [' . $test . ']');
    } else {
        $d_ref['hash'] = strtoupper($hash);
        $d_ref['size'] = $hash_data[$file]['size'];
        unset($hash_data[$file]);
        save();
    }

    sleep(2);
}

$version = LAUNCHER_VERSION;
array_map('test_file', $_LAUNCHER_FILES);

$version = FRPC_VERSION;
array_map('test_file', $_FRPC_FILES);
