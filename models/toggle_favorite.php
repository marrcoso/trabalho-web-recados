<?php
require_once __DIR__ . '/../repositories/RecadoRepository.php';

if (!isset($_POST['id'], $_POST['status'])) {
    echo "0";
    exit;
}

$repo = new RecadoRepository();
$repo->updateStatus((int)$_POST['id'], (int)$_POST['status']);

echo "1";
