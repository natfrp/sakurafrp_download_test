<?php
include('cli_base.php');

define('DOWNLOAD_FILE', 'downloads.json');

if (!is_array($download_data = @json_decode(file_get_contents(DOWNLOAD_FILE), true))) {
    log_E('Bad ' . DOWNLOAD_FILE);
    exit(1);
}

function clear_download(&$data)
{
    foreach ($data as &$v) {
        if (isset($v['hash'])) {
            $v['hash'] = '';
            $v['size'] = 0;
        }
    }
}

clear_download($download_data['launcher']);
clear_download($download_data['frpc']);

file_put_contents(DOWNLOAD_FILE, json_encode($download_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
