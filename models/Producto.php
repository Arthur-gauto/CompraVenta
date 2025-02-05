<?php
    class Producto extends Conectar{
        //TODO Listar Registros
        public function get_producto_x_suc_id($suc_id){
            try {
                $conectar = parent::Conexion();
                $sql = "SELECT 
                            p.*,
                            c.CAT_NOM,
                            u.UND_NOM,
                            m.MON_NOM
                        FROM TM_PRODUCTO p
                        INNER JOIN TM_CATEGORIA c ON p.CAT_ID = c.CAT_ID
                        INNER JOIN TM_UNIDAD u ON p.UND_ID = u.UND_ID
                        INNER JOIN TM_MONEDA m ON p.MON_ID = m.MON_ID
                        WHERE p.SUC_ID = ? AND p.EST = 1";
                $sql = $conectar->prepare($sql);
                $sql->bindValue(1, $suc_id);
                $sql->execute();
                return $sql->fetchAll(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                return array();
            }
        }
        //TODO Listar Registro por ID
        public function get_producto_x_prod_id($prod_id){
            $conectar=parent::Conexion();
            $sql="SP_L_PRODUCTO_02 ?";
            $query=$conectar->prepare($sql);
            $query->bindValue(1,$prod_id);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        public function get_producto_x_cat_id($cat_id){
            $conectar=parent::Conexion();
            $sql="SP_L_PRODUCTO_03 ?";
            $query=$conectar->prepare($sql);
            $query->bindValue(1,$cat_id);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }
        //TODO Eliminar o cambiar estado a eliminado
        public function delete_producto($prod_id){
            $conectar=parent::Conexion();
            $sql="SP_D_PRODUCTO_01 ?";
            $query=$conectar->prepare($sql);
            $query->bindValue(1,$prod_id);
            $query->execute();
        }
        //TODO Registro de datos
        public function insert_producto($suc_id,$cat_id,$prod_nom,$prod_descrip,$und_id,$mon_id,$prod_pcompra,$prod_pventa,$prod_stock,$prod_fechaven,$prod_img){
            $conectar=parent::Conexion();

            require_once("Producto.php");
            $prod=new Producto();
            $prod_img='';
            if($_FILES["prod_img"]["name"]!=' '){
                $prod_img=$prod->upload_image();
            }

            $sql="SP_I_PRODUCTO_01 ?,?,?,?,?,?,?,?,?,?,?";
            $query=$conectar->prepare($sql);
            $query->bindValue(1,$suc_id);
            $query->bindValue(2,$cat_id);
            $query->bindValue(3,$prod_nom);
            $query->bindValue(4,$prod_descrip);
            $query->bindValue(5,$und_id);
            $query->bindValue(6,$mon_id);
            $query->bindValue(7,$prod_pcompra);
            $query->bindValue(8,$prod_pventa);
            $query->bindValue(9,$prod_stock);
            $query->bindValue(10,$prod_fechaven);
            $query->bindValue(11,$prod_img);
            $query->execute();
        }
        //TODO Actualizar Datos
        public function update_producto($prod_id,$suc_id,$cat_id,$prod_nom,$prod_descrip,$und_id,$mon_id,$prod_pcompra,$prod_pventa,$prod_stock,$prod_fechaven,$prod_img){
            $conectar=parent::Conexion();

            require_once("Producto.php");
            $prod=new Producto();
            $prod_img='';
            if($_FILES["prod_img"]["name"]!=' '){
                $prod_img=$prod->upload_image();
            }else{
                $prod_img = $POST["hidden_producto_imagen"];
            }

            $sql="SP_U_PRODUCTO_01 ?,?,?,?,?,?,?,?,?,?,?,?";
            $query=$conectar->prepare($sql);
            $query->bindValue(1,$prod_id);
            $query->bindValue(2,$suc_id);
            $query->bindValue(3,$cat_id);
            $query->bindValue(4,$prod_nom);
            $query->bindValue(5,$prod_descrip);
            $query->bindValue(6,$und_id);
            $query->bindValue(7,$mon_id);
            $query->bindValue(8,$prod_pcompra);
            $query->bindValue(9,$prod_pventa);
            $query->bindValue(10,$prod_stock);
            $query->bindValue(11,$prod_fechaven);
            $query->bindValue(12,$prod_img);
            $query->execute();
        }

        public function upload_image(){
            if(isset($_FILES["prod_img"])){
                $extension = explode('.', $_FILES['prod_img']['name']);
                $new_name = rand() . '.' . $extension[1];
                $destination = '../assets/producto/' . $new_name;
                move_uploaded_file($_FILES['prod_img']['tmp_name'], $destination);
                return $new_name;
            }
        }

        public function buscar_producto_nombre($prod_nom = ""){
            $conectar = parent::Conexion();
            $sql = "EXEC SP_L_PRODUCTO_BUSCAR ?";
            $query = $conectar->prepare($sql);
            $query->bindValue(1, ($prod_nom == "" ? NULL : $prod_nom));
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }
        
        public function update_stock_precio_compra($prod_id, $cantidad, $precio_compra){
            $conectar = parent::Conexion();
            
            try {
                $sql = "EXEC SP_U_PRODUCTO_COMPRA ?, ?, ?";
                $stmt = $conectar->prepare($sql);
                $stmt->bindValue(1, $prod_id, PDO::PARAM_INT);
                $stmt->bindValue(2, $cantidad, PDO::PARAM_INT);
                $stmt->bindValue(3, $precio_compra, PDO::PARAM_STR);
                $stmt->execute();
                return true;
            } catch (Exception $e) {
                return false;
            }
        }

        
        public function buscar_producto_venta($term, $suc_id) {
            $conectar = parent::Conexion();
            $sql = "SELECT 
                        p.PROD_ID,
                        p.PROD_NOM,
                        c.CAT_NOM,
                        p.PROD_PVENTA,
                        p.PROD_STOCK,
                        u.UND_NOM
                    FROM TM_PRODUCTO p
                    INNER JOIN TM_CATEGORIA c ON p.CAT_ID = c.CAT_ID
                    INNER JOIN TM_UNIDAD u ON p.UND_ID = u.UND_ID
                    WHERE p.SUC_ID = ? 
                    AND (
                        p.PROD_NOM LIKE ? OR
                        c.CAT_NOM LIKE ? OR
                        CAST(p.PROD_ID as VARCHAR) LIKE ?
                    )
                    AND p.EST = 1
                    AND p.PROD_STOCK > 0
                    ORDER BY p.PROD_NOM ASC";
            
            $sql = $conectar->prepare($sql);
            $sql->bindValue(1, $suc_id);
            $sql->bindValue(2, "%".$term."%");
            $sql->bindValue(3, "%".$term."%");
            $sql->bindValue(4, "%".$term."%");
            $sql->execute();
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        }

        public function buscar_productos_venta($term, $suc_id) {
            $conectar = parent::Conexion();
            $sql = "SELECT 
                        p.PROD_ID,
                        p.PROD_NOM,
                        p.PROD_PVENTA,
                        p.PROD_STOCK,
                        c.CAT_NOM,
                        u.UND_NOM
                    FROM TM_PRODUCTO p
                    INNER JOIN TM_CATEGORIA c ON p.CAT_ID = c.CAT_ID
                    INNER JOIN TM_UNIDAD u ON p.UND_ID = u.UND_ID
                    WHERE p.SUC_ID = ? 
                    AND (
                        p.PROD_NOM LIKE ? OR 
                        CAST(p.PROD_ID as VARCHAR) LIKE ? OR
                        c.CAT_NOM LIKE ?
                    )
                    AND p.EST = 1
                    AND p.PROD_STOCK > 0
                    ORDER BY p.PROD_NOM ASC";
            
            $sql = $conectar->prepare($sql);
            $sql->bindValue(1, $suc_id);
            $sql->bindValue(2, "%".$term."%");
            $sql->bindValue(3, "%".$term."%");
            $sql->bindValue(4, "%".$term."%");
            $sql->execute();
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        }
    }
?>

