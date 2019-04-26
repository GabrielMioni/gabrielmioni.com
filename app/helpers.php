<?php

if (! function_exists('sqlFormatDate')) {
    function sqlFormatDate($date = null) {
        $set_date = $date === null ? time() : strtotime($date);
        return date(getenv('SITE_DATE_FORMAT'), $set_date);
    }
}

function checkBladeData($data) {
    return trim($data) !== '';
}

function setBladeClass(bool $conditional, $classOne, $classTwo) {
    $out = '';
    if ($conditional === true) {
        $out = $classOne;
    }
    if ($conditional === false) {
        $out = $classTwo;
    }

    return 'class='.$out;
}

function bgImage($avatar) {
    return '/images/' . $avatar . '.jpg';
}
