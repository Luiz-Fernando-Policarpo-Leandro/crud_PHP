<?php
require __DIR__ . '/model.php';

class User extends Model{
    
    protected string $table = "users";

    function __construct(protected PDO $pdo,
        private string $nome,
        private string $email,
        private string $dataNascimento
        ){}

    function create() {
        $pdo = $this->pdo;
        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare("INSERT INTO users (nome,email,data_nascimento) VALUES (?,?,?);");
            $stmt->execute([$this->nome, $this->email, $this->dataNascimento]);
            $pdo->commit();

        }catch (PDOException $e) {
            error_log($e->getMessage());
            throw $e;
        }
         catch (Exception $e){
            error_log($e->getMessage());
            throw $e;
        }
    }

    function destroy() {

    }

    function save() {


    }

}