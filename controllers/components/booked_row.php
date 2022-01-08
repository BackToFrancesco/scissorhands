<?php


function booked_row($start_at, $end_at, $service, $price, $staff) {
    $out = file_get_contents(__DIR__ . '/../../views/components/booked_row.html');

    $day = gmdate('d F Y', $start_at);
    $from = gmdate('G:i', $start_at);
    $duration = ($end_at-$start_at) / 60;

    $out = str_replace("%DAY%", $day, $out);
    $out = str_replace("%HOUR%", $from, $out);
    $out = str_replace("%DURATION%", $duration, $out);
    $out = str_replace("%SERVICE%", $service, $out);
    $out = str_replace("%PRICE%", $price, $out);
    $out = str_replace("%STAFF%", $staff, $out);


    return $out;
}