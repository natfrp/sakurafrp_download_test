<?php
$output = array();

function exportFile($file)
{
    if (file_exists($file)) {
        global $output;
        $output[basename($file)] = array(
            'md5' => md5_file($file),
            'size' => filesize($file)
        );
    }
}

function exportDir($path)
{
    $dir = opendir($path);
    while ($f = readdir($dir)) {
        if (substr($f, 0, 4) == 'sign' || !is_file($path . '/' . $f) || substr($f, -4) == '.sig') {
            continue;
        }
        if (substr($f, 0, 4) == 'frpc' || substr($f, -4) == '.zip' || substr($f, 0, 6) == 'Sakura') {
            exportFile($path . '/' . $f);
        }
    }
    closedir($dir);
}

if (isset($argv[1])) {
    exportDir($argv[1]);
} else {
    exportDir('../frp/release');
    exportDir('../SakuraLauncher/_publish');
    exportFile('../SakuraLauncher/bin/SakuraLauncher.exe');
}

file_put_contents('hash.json', json_encode($output, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
