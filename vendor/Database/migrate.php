<?php
require_once(__DIR__ . '/../autoload.php');
require_once(__DIR__ . '/../../migration/first.php');
use General\Database\Database;
$db = new Database();
echo json_encode($db->migrate($tables));
?>