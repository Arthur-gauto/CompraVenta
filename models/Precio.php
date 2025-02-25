<?php
class Precio extends Conectar {
    public function guardar_precios($prod_id, $listp_a, $listp_b, $listp_c) {
        $conectar = parent::Conexion();
        $sql = "SP_U_LISTA_PRECIOS_01 ?, ?, ?, ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $prod_id);
        $query->bindValue(2, $listp_a);
        $query->bindValue(3, $listp_b);
        $query->bindValue(4, $listp_c);
        $query->execute();
    }

    public function get_precios($prod_id) {
        $conectar = parent::Conexion();
        $sql = "SP_L_LISTA_PRECIOS_01 ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $prod_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>