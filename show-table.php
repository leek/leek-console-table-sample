#!/usr/bin/env php
<?php

require 'vendor/autoload.php';

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableStyle;
use Symfony\Component\Console\Output\ConsoleOutput;

// Maps columns to colors
$color_map = array(
    'Name'    => 'fg=black;bg=red',
    'Color'   => 'fg=black;bg=blue',
    'Element' => 'fg=black;bg=green',
    'Likes'   => 'fg=black;bg=yellow',
);

// Fake our input data
$input = array(
    array(
        'Name'    => 'Trixie',
        'Color'   => 'Green',
        'Element' => 'Earth',
        'Likes'   => 'Flowers'
    ),
    array(
        'Name'    => 'Tinkerbell',
        'Element' => 'Air',
        'Likes'   => 'Singing',
        'Color'   => 'Blue'
    ),
    array(
        'Element' => 'Water',
        'Likes'   => 'Dancing',
        'Name'    => 'Blum',
        'Color'   => 'Pink'
    ),
);

// Determine our table headers from the first row
$headers = array_keys(reset($input));

// Table will do all the heavy lifting
$table = new Table($output = new ConsoleOutput());

// Our custom table styling (for colors)
$style = new TableStyle();
$style
    ->setHorizontalBorderChar('<fg=black;bg=white>-</>')
    ->setVerticalBorderChar('<fg=black;bg=white>|</>')
    ->setCrossingChar('<fg=black;bg=white>+</>')
;

// Fill in our tabular data
foreach ($input as $row) {

    // Reorder our keys so they are in the same order as $header
    $row = array_merge(array_flip($headers), $row);

    // Colorize our output columns
    array_walk($row, function(&$value, $key) use ($color_map) {
        !empty($color_map[$key]) && $value = "<{$color_map[$key]}>{$value}";
    });

    // Write row
    $table->addRow($row);

}

// Colorize our output headers
$headers = array_map(function($header) use ($color_map) {
    return str_ireplace('fg=black;bg=', 'fg=', "<{$color_map[$header]}>{$header}");
}, $headers);

$table->setStyle($style);
$table->setHeaders($headers);
$table->render();
