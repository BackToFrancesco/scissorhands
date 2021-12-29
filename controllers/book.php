<?php
require_once 'components/page.php';
require_once 'components/header.php';
require_once 'components/radio_book.php';

$pagina = page('Prenota');
$header = _header();
$main = file_get_contents('../views/book.html');

require_once __DIR__ . '/../services/public/book.php';
require_once __DIR__ . '/../services/public/service.php';
require_once __DIR__ . '/../services/public/staff.php';

$services = PublicServiceService::getAll();
$staff = PublicStaffService::getAll();

$selected_service = "";
if (isset($_GET["service"]) && preg_match('/^[0-9]+$/', $_GET["service"])) {
    $selected_service = $_GET["service"];
}

$selected_staff = "";
if (isset($_GET["staff"]) && preg_match('/^[0-9]+$/', $_GET["staff"])) {
    $selected_staff = $_GET["staff"];
}

$radios_services = "";
foreach($services as $service) {
    $radios_services.= radio_book($service["name"], "service", $service["_id"], "service-".$service["_id"], boolval($selected_service === $service["_id"]));
}

$radios_staff = "";
foreach($staff as $member) {
    $radios_staff .= radio_book($member["name"]. " ". $member["surname"], "staff", $member["_id"], "staff-".$member["_id"], boolval($selected_staff === $member["_id"]));
}

$main = str_replace("%RADIOS_SERVICE%", $radios_services, $main);
$main = str_replace("%RADIOS_STAFF%", $radios_staff, $main);

$main = str_replace("%SELECTED_SERVICE%", $selected_service, $main);
$main = str_replace("%SELECTED_STAFF%", $selected_staff, $main);

$pagina = str_replace('%HEADER%', $header, $pagina);
$pagina = str_replace('%MAIN%', $main, $pagina);

echo $pagina;
