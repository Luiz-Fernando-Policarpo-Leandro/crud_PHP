<?php
require 'controller.php';
require __DIR__ . './../models/user.php';

class userController extends applicationController {

    function __construct(user $params)
    {
        throw new \Exception('Not implemented');
    }

    function index() {
        require parent::DB;
        $sql = "SELECT * FROM users;";
        $stmt = $pdo->query($sql);
        $res = $stmt->fetchAll();
        
        return $res;

    }
    function create() {

    }

    function save() {

    }

    function destroy() {

    }
}
