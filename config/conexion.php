<?php
    session_start();
    class Conectar{
        protected $dbh;

        protected function Conexion(){
            try{
                /* $conectar = $this->dbh=new PDO("sqlsrv:Server=Arthur\SQLEXPRESS;Database=CompraVenta","sa","lacabragauto123"); */
                $conectar = $this->dbh=new PDO("sqlsrv:server = tcp:gauriv01.database.windows.net,1433; Database = compraventa01","Gauriv","Yosoylacabra123#");
                return $conectar;
            }catch(Exception $e){
                print "Error Conexion BD". $e->getMessage()."<br/>";
                die();
            }
        }

        public static function ruta(){
            /* return "http://localhost:90/PERSONAL_CompraVenta/"; */
            return "https://gaurivtech.azurewebsites.net/";
            
        }   
    }
?>