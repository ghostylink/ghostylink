<?php
$baseDir = dirname(dirname(__FILE__));
return [
    'plugins' => [
        'AssetCompress' => $baseDir . '/vendor/markstory/asset_compress/',
        'Bake' => $baseDir . '/vendor/cakephp/bake/',
        'DebugKit' => $baseDir . '/vendor/cakephp/debug_kit/',
        'Migrations' => $baseDir . '/vendor/cakephp/migrations/',
        'MiniAsset' => $baseDir . '/vendor/markstory/mini-asset/'
    ]
];
