<?php
/***** SETTINGS *****/

// To add more, array("address", port),
$servers = array(
	array("localhost", 4028),
	// array("address", port),
	// array("address", port),
);

// Put false to disable or true to enable removing of disabled devices
$removeDisabled = true;
// Enable gpu specific details
$enableGPUdisp = false;
// Time format, view this if you wish to change it: http://www.php.net/manual/en/function.date.php
$timeFormat = "F d, Y, h:i:s A";
// PHP has the incorrect time? Override it! Timezones list: http://www.php.net/manual/en/timezones.php
// Only uncomment if that is the case
//date_default_timezone_set('America/Los_Angeles');
/*****
 * View current prices for btc & ltc
 * WARNING: this feature slows down your page load time dramatically, since it
 * needs to retrieve data from other websites.
 * Enabled vs. disabled feature time is
 * 2.5281450748444 seconds vs. 0.037001848220825 seconds
 * It is however enabled by default
 *****/
$viewPrices = true;

/** END SETTINGS **/
?>