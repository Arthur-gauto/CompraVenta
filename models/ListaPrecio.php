<?php
class ListaPrecio extends Conectar {

    //TODO INSERTAR O ACTUALIZAR PRECIO
    public function update_lista_precios($prod_id, $tipo_lista, $precio) {
        $conectar = parent::Conexion();
        $sql = "IF EXISTS (SELECT 1 FROM TM_LIST_PRECIO WHERE prod_id = ? AND tipo_lista = ?)
                    UPDATE TM_LIST_PRECIO SET precio = ? WHERE prod_id = ? AND tipo_lista = ?
                ELSE
                    INSERT INTO TM_LIST_PRECIO (prod_id, tipo_lista, precio) VALUES (?, ?, ?)";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $prod_id);
        $query->bindValue(2, $tipo_lista);
        $query->bindValue(3, $precio);
        $query->bindValue(4, $prod_id);
        $query->bindValue(5, $tipo_lista);
        $query->bindValue(6, $prod_id);
        $query->bindValue(7, $tipo_lista);
        $query->bindValue(8, $precio);
        $query->execute();
    }

    //TODO OBTENER PRECIOS POR PRODUCTO
    public function get_lista_precios($prod_id) {
        $conectar = parent::Conexion();
        $sql = "SELECT * FROM TM_LIST_PRECIO WHERE prod_id = ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $prod_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>