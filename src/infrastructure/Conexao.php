<?php

namespace DesafioBackend\infrastructure;

use \PDO;
use PDOException;

class Conexao{
    private static $instance;

    private function __construct() {
    }

    public static function getConn(){
        if(!isset(self::$instance)){
            try{
                self::$instance = new PDO('mysql:host=localhost; dbname=acme_fitness; charset=utf8', 'root', '');
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }catch(PDOException $e){
                die('Erro: '.$e->getMessage());
            }
        }
    
        return self::$instance;
    }

    public static function beginTransaction() {
        self::getConn()->beginTransaction();
    }

    public static function commit() {
        self::getConn()->commit();
    }

    public static function rollBack() {
        self::getConn()->rollBack();
    }
}
