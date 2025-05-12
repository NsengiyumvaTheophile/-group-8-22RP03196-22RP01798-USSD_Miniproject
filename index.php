<?php
// Include necessary file
require_once 'utils.php';
require_once 'sms.php';
require_once 'menu.php';

use AfricasTalking\SDK\AfricasTalking;

session_start();

$menu = new Menu();
$sms = new Sms();

$sessionId = $_POST["sessionId"] ?? '';
$serviceCode = $_POST["serviceCode"] ?? '';
$phoneNumber = $_POST["phoneNumber"] ?? '';
$text = $_POST["text"] ?? '';

$parts = explode("*", $text);
$level = count($parts);

$menu->handleRequest($sessionId, $serviceCode, $phoneNumber, $text, $parts, $level, $sms);

?>
