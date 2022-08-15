<?php
ini_set('date.timezone','Asia/Taipei');

$ddate = "2022-01-10";
$date = new DateTime($ddate);
$week = $date->format("w");
echo "Weeknummer: $week";

$ddate = "2022-01-11";
$date = new DateTime($ddate);
$week = $date->format("w");
$weeks = $date->format("W");
echo "Weeknummer: $week".''.$weeks;

$ddate = "2022-01-12";
$date = new DateTime($ddate);
$week = $date->format("w");
echo "Weeknummer: $week";

$ddate = "2022-01-13";
$date = new DateTime($ddate);
$week = $date->format("w");
echo "Weeknummer: $week";

$ddate = "2022-01-14";
$date = new DateTime($ddate);
$week = $date->format("w");
echo "Weeknummer: $week";

$ddate = "2022-01-15";
$date = new DateTime($ddate);
$week = $date->format("w");
echo "Weeknummer: $week";

$ddate = "2022-01-16";  		//日
$date = new DateTime($ddate);
$week = $date->format("w");
echo "Weeknummer: $week";

$ddate = "2022-01-17";			//一
$date = new DateTime($ddate);
$week = $date->format("w");
echo "Weeknummer: $week";

?>