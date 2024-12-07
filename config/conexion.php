<?php
    class Conectar{
        protected $dbh;

        protected function Conexion(){
            try{
                $conectar = $this->dbh=new PDO("sqlsrv:Server=localhost;Database=CompraVenta","sa","lacabragauto123");
                return $conectar;
            }catch(Exception $e){
                print "Error Conexion BD". $e->getMessage()."<br/>";
                die();
            }
        }
    }
?>