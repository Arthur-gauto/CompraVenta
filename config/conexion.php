<?php
ini_set('session.cookie_secure', 1); // Para HTTPS en Azure
ini_set('session.cookie_httponly', 1);
session_start();

class Conectar {
    protected $dbh;

    protected function Conexion() {
        try {
            $this->dbh = new PDO("sqlsrv:server = tcp:gauriv01.database.windows.net,1433; Database = compraventa01", "Gauriv", "Yosoylacabra123#");
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->dbh;
        } catch (Exception $e) {
            file_put_contents('debug.log', "Error Conexion BD: " . $e->getMessage() . "\n", FILE_APPEND);
            throw $e;
        }
    }

    public static function ruta() {
        //return "http://localhost:90/PERSONAL_CompraVenta/";
        return "https://gaurivtech.azurewebsites.net/";
    }
}
?>