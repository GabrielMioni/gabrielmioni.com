<?php

if (! function_exists('sqlFormatDate')) {
    function sqlFormatDate($date = null) {
        $set_date = $date === null ? time() : strtotime($date);
        return date(getenv('SITE_DATE_FORMAT'), $set_date);
    }
}