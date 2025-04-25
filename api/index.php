<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

// require_once __DIR__ . '/products.php';
$products = [
    1 => [
        'id' => 1,
        'name' => 'Laptop',
        'price' => 999.99,
        'stock' => 50
    ],
    2 => [
        'id' => 2,
        'name' => 'Smartphone',
        'price' => 699.99,
        'stock' => 100
    ],
    3 => [
        'id' => 3,
        'name' => 'Headphones',
        'price' => 199.99,
        'stock' => 75
    ]
];

function getAllProducts() {
    global $products;
    return [
        'status' => 'success',
        'data' => array_values($products)
    ];
}

function getProductById($id) {
    global $products;
    if (isset($products[$id])) {
        return [
            'status' => 'success',
            'data' => $products[$id]
        ];
    }
    return [
        'status' => 'error',
        'message' => 'Product not found'
    ];
}

function createProduct($data) {
    global $products;
    if ($data && isset($data['name']) && isset($data['price']) && isset($data['stock'])) {
        $newId = max(array_keys($products)) + 1;
        $newProduct = [
            'id' => $newId,
            'name' => $data['name'],
            'price' => $data['price'],
            'stock' => $data['stock']
        ];
        
        $products[$newId] = $newProduct;
        return [
            'status' => 'success',
            'data' => $newProduct
        ];
    }
    return [
        'status' => 'error',
        'message' => 'Invalid product data'
    ];
}

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$pathSegments = explode('/', trim($path, '/'));

/*
$pathSegments --> ['products']
$pathSegments[0] --> products
$pathSegments[1] --> 3
*/

if ($pathSegments[0] === 'products') {

    if ($method === 'GET') {
        if (isset($pathSegments[1])) {
            $response = getProductById($pathSegments[1]);
            http_response_code($response['status'] === 'success' ? 200 : 404);
        } else {
            $response = getAllProducts();
            http_response_code(200);
        }
    } elseif ($method === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        $response = createProduct($data);
        http_response_code($response['status'] === 'success' ? 201 : 400);
    } else {
        http_response_code(405);
        $response = [
            'status' => 'error',
            'message' => 'Method not allowed'
        ];
    }
} else {
    http_response_code(404);
    $response = [
        'status' => 'error',
        'message' => 'Endpoint not found'
    ];
}

echo json_encode($response);
