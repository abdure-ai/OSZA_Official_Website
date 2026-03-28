<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "--- Database Encoding Test ---\n";

$connection = DB::connection()->getPdo();
$status = $connection->query("SHOW VARIABLES LIKE 'character_set_database'")->fetch();
echo "Database Charset: " . $status['Value'] . "\n";

$status = $connection->query("SHOW VARIABLES LIKE 'collation_database'")->fetch();
echo "Database Collation: " . $status['Value'] . "\n";

if (Schema::hasTable('hero_slides')) {
    $slides = DB::table('hero_slides')->get();
    echo "Count: " . $slides->count() . "\n";
    foreach ($slides as $slide) {
        echo "ID: " . $slide->id . "\n";
        echo "Title (EN): " . $slide->title_en . "\n";
        echo "Title (AM): " . $slide->title_am . "\n";
        echo "Hex AM: " . bin2hex($slide->title_am ?? '') . "\n";
        echo "---\n";
    }
} else {
    echo "Table 'hero_slides' NOT found.\n";
}

if (Schema::hasTable('posts')) {
    $post = DB::table('posts')->first();
    if ($post) {
        echo "Sample Post AM: " . $post->title_am . "\n";
        echo "Hex: " . bin2hex($post->title_am ?? '') . "\n";
    }
}
