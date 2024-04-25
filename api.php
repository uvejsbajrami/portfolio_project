<?php

include 'db.php'; 
include 'helpers.php'; 

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: *');

$method = $_SERVER['REQUEST_METHOD'];   
$action = isset($_GET['action']) ? $_GET['action'] : '';
$payload = json_decode(file_get_contents('php://input'), true);

// Action: get_portfolio_projects
// HTTP verb: GET
// URL: http://localhost/portfolio_project/api.php?action=get_portfolio_projects
if ($action == 'get_portfolio_projects' && $method == 'GET') {
    $portfolio_projects = [];
    
    $portfolio_projects_stm = $pdo->prepare("SELECT *, DATE(date_upload) AS date_only FROM `project`");
    $portfolio_projects_stm->execute();

    while ($row = $portfolio_projects_stm->fetch(PDO::FETCH_ASSOC)) {
        $portfolio_projects[] = $row;
    }

    echo json_encode(['portfolio_projects' => $portfolio_projects]);
    exit(); // Stop execution after handling the request
} 

// Action: get_portfolio_projects_desc
// HTTP verb: GET
// URL: http://localhost/portfolio_project/api.php?action=get_portfolio_projects_desc
if ($action == 'get_portfolio_projects_desc' && $method == 'GET') {
    $portfolio_projects_desc = [];

    $portfolio_projects_desc_stm = $pdo->prepare("SELECT *, DATE(date_upload) AS date_only FROM `project` ORDER BY `date_upload` DESC LIMIT 4");
    $portfolio_projects_desc_stm->execute();

    while ($row = $portfolio_projects_desc_stm->fetch(PDO::FETCH_ASSOC)) {
        $portfolio_projects_desc[] = $row;
    }

    echo json_encode(['portfolio_projects_desc' => $portfolio_projects_desc]);
    exit(); // Stop execution after handling the request
} 
//test



// Default response for unknown requests
$response = ['error' => 'Invalid action or method'];
echo json_encode($response);

?>
