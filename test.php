<?php

use GuzzleHttp\Client;
use GuzzleHttp\Promise\Utils;

require __DIR__.'/vendor/autoload.php';

$fileName = '/tmp/input-file';

$guzzle = new Client([
    'base_uri' => 'http://server:8000'
]);

// 2MB+ data size si it's offloaded to tmp file
// https://www.php.net/manual/en/wrappers.php.php#wrappers.php.memory
$data = str_repeat('X', 2 * 1024 * 1024);

$promises = [];
foreach (range(1, 256) as $i) {
    $promises[] = $guzzle->postAsync('/', [
        'body' => $data,
    ])->then(function() use ($i) {
        echo sprintf('Request %s finished', $i).PHP_EOL;
    });

    // first 50 requests is cleaned up properly
    // anything above 50 remains
    if (count($promises) >= 51) {
        echo sprintf('Wait %s uploads to finish', count($promises)).PHP_EOL;
        Utils::unwrap($promises);
        $promises = [];

        echo 'Uploads finished' .PHP_EOL;

        echo 'tmp files left: ';
        passthru('ls -la /tmp/php* | wc -l');
        passthru('ls -la /tmp/php*');
    }
}

echo sprintf('Wait %s uploads to finish', count($promises)).PHP_EOL;
Utils::unwrap($promises);
echo 'All done'.PHP_EOL;
echo 'tmp files left: ';
passthru('ls -la /tmp/php* | wc -l');
passthru('ls -la /tmp/php*');
