<?php
ini_set('display_errors', '1');
ini_set('disable_functions', '');
include "Lib/Cpanel.class.php";
include "Lib/functions.php";

$cpanel = new Cpanel();


$usernames = $cpanel->getAccuntsByPath();

echo 'Total accounts: ' . count($usernames) . "\n";

$hasWPFake = [];

foreach ($usernames as $accunt => $path) {
    $path = $path . '/wordpress/';
    if (file_exists($path)) {
        $hasWPFake[$accunt] = $path;
    }
}

echo 'Fake Wordpress found: ' . count($hasWPFake) . "\n";

$hasWPFakeConfig = [];
$notWPFakeConfig = [];
foreach ($hasWPFake as $accunt => $path) {
    $path = $path . 'wp-config.php';

    if (file_exists($path)) {
        $hasWPFakeConfig[$accunt] = $path;
    } else {
        $notWPFakeConfig[$accunt] = $path;
    }
}
echo 'Fake Wordpress without wp-config: ' . count($notWPFakeConfig) . "\n";
echo 'Fake Wordpress with wp-config: ' . count($hasWPFakeConfig) . "\n";

$hasWPFakeConfigByIP = [];

foreach ($hasWPFakeConfig as $accunt => $path) {
    // $path = $path . 'wp-config.php';
    $content = file_get_contents($path);
    $find = preg_match('@[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}(:[0-9]{2,4})?@', $content, $xx);
    if ($find) {
        //  echo $accunt . ' ' . $path . "\n";
        $hasWPFakeConfigByIP[$accunt] = $path;
    }
}
echo 'Fake Wordpress with wp-config and other ip: ' . count($hasWPFakeConfigByIP) . "\n";
echo '----------------------------------------------------------------------------------------' . "\n";

echo "\n". '#list of accunts(Fake Wordpress with wp-config and other ip): '. "\n\n";
foreach ($hasWPFakeConfigByIP as $accunt => $path) {
    echo 'rm -rf ' . dirname($path) . "\n";
    unset($hasWPFakeConfig[$accunt],$hasWPFakeConfig[$accunt],$notWPFakeConfig[$accunt],$hasWPFake[$accunt]);
}


echo "\n". '#list of accunts(Fake Wordpress with wp-config): '. "\n\n";
foreach ($hasWPFakeConfig as $accunt => $path) {
    echo 'cd ' . dirname($path) . "\n";
    unset($hasWPFake[$accunt]);
}

echo "\n". '#list of accunts(Fake Wordpress without wp-config): '. "\n\n";
foreach ($notWPFakeConfig as $accunt => $path) {
    echo 'cd ' . dirname($path) . "\n";
    unset($hasWPFake[$accunt]);
}
