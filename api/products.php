<?php

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
?>