<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;

//Get all customers
$app->get('/api/customers', function(Request $request, Response $response, array $args) {
   
    $sql = "SELECT * FROM customers;";
    
    try {
        //Get DB Object
        $db = new db();

        //Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $customers = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        echo json_encode($customers);
    } catch(PDOException $e) {
        echo '{"error: {"text": '.$e->getMessage().'}}';
    }
});

//Get single customer
$app->get('/api/customer/{id}', function(Request $request, Response $response, array $args) {
    $id = $args['id'];

    $sql = "SELECT * FROM customers WHERE id = :id;";
    
    try {
        //Get DB Object
        $db = new db();

        //Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $customer = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        echo json_encode($customer);
    } catch(PDOException $e) {
        echo '{"error: {"text": '.$e->getMessage().'}}';
    }
});

//Add customer
$app->post('/api/customer/add', function(Request $request, Response $response, array $args) {
    $first_name = $request->getParam('first_name');
    $last_name = $request->getParam('last_name');
    $phone = $request->getParam('phone');
    $email = $request->getParam('email');
    $adress = $request->getParam('adress');
    $city = $request->getParam('city');
    $state = $request->getParam('state');

    $sql = "INSERT INTO customers
            (first_name,last_name,phone,email,adress,city,state) 
            VALUES 
            (:first_name,:last_name,:phone,:email,:adress,:city,:state)";
    
    try {
        //Get DB Object
        $db = new db();

        //Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':first_name', $first_name);
        $stmt->bindValue(':last_name', $last_name);
        $stmt->bindValue(':phone', $phone);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':adress', $adress);
        $stmt->bindValue(':city', $city);
        $stmt->bindValue(':state', $state);

        $stmt->execute();
        echo json_encode('{"notice": {"text": "Customer Added"}}');
    } catch(PDOException $e) {
        echo '{"error: {"text": '.$e->getMessage().'}}';
    }
});

//Update customer
$app->put('/api/customer/update/{id}', function(Request $request, Response $response, array $args) {
    $id = $args['id'];
    $first_name = $request->getParam('first_name');
    $last_name = $request->getParam('last_name');
    $phone = $request->getParam('phone');
    $email = $request->getParam('email');
    $adress = $request->getParam('adress');
    $city = $request->getParam('city');
    $state = $request->getParam('state');

    $sql = "UPDATE customers SET
            first_name = :first_name,
            last_name = :last_name,
            phone = :phone,
            email = :email,
            adress = :adress,
            city = :city,
            state = :state
            WHERE id = :id";

    try {
        //Get DB Object
        $db = new db();

        //Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':first_name', $first_name);
        $stmt->bindValue(':last_name', $last_name);
        $stmt->bindValue(':phone', $phone);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':adress', $adress);
        $stmt->bindValue(':city', $city);
        $stmt->bindValue(':state', $state);

        $stmt->execute();
        echo json_encode('{"notice": {"text": "Customer Updated"}}');
    } catch(PDOException $e) {
        echo '{"error: {"text": '.$e->getMessage().'}}';
    }
});

//Delete customer
$app->delete('/api/customer/delete/{id}', function(Request $request, Response $response, array $args) {
    $id = $args['id'];

    $sql = "DELETE FROM customers WHERE id = :id;";
    
    try {
        //Get DB Object
        $db = new db();

        //Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        $db = null;

        echo json_encode('{"notice": {"text": "Customer Deleted"}}');
    } catch(PDOException $e) {
        echo '{"error: {"text": '.$e->getMessage().'}}';
    }
});