<?php

namespace AlfredNutileInc\LarScanner;

if (!function_exists('event')) {
    function event($arg)
    {

        if (isset($_SESSION['events_called'][$arg])) {
            $_SESSION['events_called'][$arg] = $_SESSION['events_called'][$arg] + 1;
        } else {
            $_SESSION['events_called'][$arg] = 1;
        }
    }
}

if (!function_exists('event')) {
    function event_count($arg)
    {

        if (isset($_SESSION['events_called'][$arg])) {
            return $_SESSION['events_called'][$arg];
        } else {
            return 0;
        }
    }
}

class TestCase extends \PHPUnit_Framework_TestCase
{



}
