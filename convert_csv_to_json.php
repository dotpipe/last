<?php

$directory = '.';
$files = glob($directory . '/*.csv');

foreach ($files as $file) {
    $csv = array_map('str_getcsv', file($file));
    $headers = array_shift($csv);
    $shortHeaders = array_map(function($header) { return strtolower(substr($header, 0, 1)); }, $headers);
    
    $json = [];
    foreach ($csv as $row) {
        $json['results'][] = array_combine($shortHeaders, $row);
    }
    
    $jsonFile = str_replace('.csv', '.json', $file);
    file_put_contents($jsonFile, json_encode($json, JSON_PRETTY_PRINT));
    
    echo "Converted $file to $jsonFile\n";
}

echo "All CSV files in testminute directory have been converted to JSON.";
