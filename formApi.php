<?php

include 'db.php'; 
include 'helpers.php'; 

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: *');

$method = $_SERVER['REQUEST_METHOD'];   
$action = isset($_GET['action']) ? $_GET['action'] : '';
$payload = json_decode(file_get_contents('php://input'), true);



// Action: project_create
// HTTP verb: POST
// URL: http://localhost/portfolio_project/api.php
if (isset($payload['action']) && $payload['action'] == 'project_create' && isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $directory = "./uploads/";
    $image_path = $directory . basename($_FILES['image']['name']);

    if (!file_exists($directory)) {
        mkdir($directory, 0777, true); // Create the directory recursively with full permissions
    }

    if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
        $project_create_stm = $pdo->prepare("INSERT INTO `project` (`project_name`,`project_category`,`description`,`client`,`tech`,`type`,`github_URL`,`image`) VALUES (:project_name, :project_category, :description, :client,:tech, :type, :github_URL, :image)");
        $project_create_stm->bindParam(':project_name', $payload['project_name']);
        $project_create_stm->bindParam(':project_category', $payload['project_category']);
        $project_create_stm->bindParam(':description', $payload['description']);
        $project_create_stm->bindParam(':client', $payload['client']);
        $project_create_stm->bindParam(':tech', $payload['tech']);
        $project_create_stm->bindParam(':type', $payload['type']);
        $project_create_stm->bindParam(':github_URL', $payload['github_URL']);
        $project_create_stm->bindParam(':image', $image_path); 

        if ($project_create_stm->execute()) {
            $response = ['status' => 1, 'message' => 'Project created successfully'];
        } else {
            $response = ['status' => 0, 'message' => 'Failed to create project'];
        }
    } else {
        $response = ['status' => 0, 'message' => 'Failed to move uploaded image'];
    }

    echo json_encode($response);
    exit(); // Stop execution after handling the request
} 