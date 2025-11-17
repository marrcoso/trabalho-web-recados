<?php
require_once __DIR__ . '/repositories/RecadoRepository.php';

$repo = new RecadoRepository();
$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"), true);

header('Content-Type: application/json');

if ($method === 'POST') {
    $mensagem = $data['mensagem'] ?? '';
    $recado = $repo->create($mensagem);
    echo json_encode(['ok' => true, 'recado' => $recado]);
    exit;
}

if ($method === 'PUT') {

    if (isset($data['status'])) {
        $id = (int)$data['id'];
        $status = (int)$data['status'];

        $result = $repo->updateStatus($id, $status);

        echo json_encode(['ok' => true]);
        exit;
    }

    $id = (int)$data['id'];
    $mensagem = $data['mensagem'] ?? '';

    $recado = $repo->edit($id, $mensagem);

    echo json_encode(['ok' => true, 'recado' => $recado]);
    exit;
}

if ($method === 'DELETE') {
    $id = (int)$data['id'];
    $repo->delete($id);
    echo json_encode(['ok' => true]);
    exit;
}

echo json_encode(['ok' => false, 'error' => 'Método inválido']);
