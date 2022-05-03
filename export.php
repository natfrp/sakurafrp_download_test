<?php
$output = array();

function exportDir($path)
{
    global $output;
    $dir = opendir($path);
    while ($f = readdir($dir)) {
        if (substr($f, 0, 4) == 'sign' || !is_file($path . '/' . $f) || substr($f, -4) == '.sig') {
            continue;
        }
        if (substr($f, 0, 4) == 'frpc' || substr($f, -4) == '.zip' || substr($f, 0, 6) == 'Sakura') {
            $file = $path . '/' . $f;
            $output[$f] = array(
                'md5' => md5_file($file),
                'size' => filesize($file)
            );
        }
    }
    closedir($dir);
}

if (isset($argv[1])) {
    exportDir($argv[1]);
} else {
    exportDir('D:/Code/CS/SakuraLauncher/_publish');
    exportDir('D:/Code/Golang/frp/release');
}

file_put_contents('hash.json', json_encode($output, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
