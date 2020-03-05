<?php
require __DIR__. "/../vendor/autoload.php";

use App\AtomFeeder;

$options = getopt("u:e:o:h", ["url:", "elements:", "output:", "help"]);

// Help command
if (isset($options['h']) || isset($options['help'])) {
    echo "Input parameters: " . PHP_EOL .
         "--url\t\t-u\t Url to be parsed" . PHP_EOL .
         "--elements\t-e\t Elements which contains anchor tags to the articles (Default: \"article h1 a\")" . PHP_EOL .
         "--output\t-o\t Output file path" . PHP_EOL .
         "--help\t\t-h\t Commands" . PHP_EOL;
    die();
}

$url = isset($options['u']) ?  $options['u'] : $options['url'];
$anchorElement = isset($options['e']) ? $options['e'] : $options['elements'];

// Get output file
if (isset($options['o'])) {
    $outputPath = $options['o'];
} elseif (isset($options['output'])) {
    $outputPath = $options['output'];
} else {
    $outputPath = './feeds/feed.xml';
}

// Generate Feed
try {
    $atomFeeder = new AtomFeeder($url, $anchorElement);
    $atomFeed = $atomFeeder->generateFeed();
} catch (\Exception $exception) {
    echo $exception;
    die();
}

// Save feed to file
$dirname = dirname($outputPath);
if (!is_dir($dirname)) {
    mkdir($dirname, 0755, true);
}
$fileHandler = fopen($outputPath, "w");
fwrite($fileHandler, $atomFeed);
fclose($fileHandler);
