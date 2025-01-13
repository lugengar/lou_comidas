<?php

// Lista de IPs permitidas (la tuya)
$allowed_ips = ["192.168.100.1"]; // Cambia esto por tu dirección IP

// Obtén la IP del cliente
$client_ip = $_SERVER['REMOTE_ADDR'];

// Verifica si la IP del cliente está en la lista de permitidas
if (!in_array($client_ip, $client_ip)) {
    http_response_code(403); // Código de acceso denegado
    echo json_encode(["error" => "Acceso denegado desde esta IP"]);
    exit;
}

echo json_encode(["success" => "Acceso permitido desde $client_ip"]);

header('Content-Type: application/json');

// Cargar JSON
$menuJson = file_get_contents('./menu.json');
$menuData = json_decode($menuJson, true);

// Función para agregar un nuevo elemento al menú
function addItem(&$menuData, $item) {
    $menuData['menu'][] = $item;
}

// Función para eliminar un elemento del menú por su nombre
function deleteItem(&$menuData, $nombre) {
    foreach ($menuData['menu'] as $key => $value) {
        if ($value['nombre'] === $nombre) {
            unset($menuData['menu'][$key]);
            $menuData['menu'] = array_values($menuData['menu']); // Reindexar array
            return true;
        }
    }
    return false;
}

// Manejar solicitudes
$method = $_SERVER['REQUEST_METHOD'];
$requestData = json_decode(file_get_contents('php://input'), true);

if ($method === 'POST' && isset($requestData['action'])) {
    if ($requestData['action'] === 'add') {
        addItem($menuData, $requestData['item']);
        echo json_encode(["message" => "Item added successfully"]);
    } elseif ($requestData['action'] === 'delete') {
        $result = deleteItem($menuData, $requestData['nombre']);
        echo json_encode(["message" => $result ? "Item deleted successfully" : "Item not found"]);
    }
    // Guardar cambios en el archivo
    file_put_contents('menu.json', json_encode($menuData, JSON_PRETTY_PRINT));
} else {
    echo json_encode(["error" => "Invalid request"]);
}
