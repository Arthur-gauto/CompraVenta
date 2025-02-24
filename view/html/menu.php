<?php
    require_once("../../models/Menu.php");
    $menu = new Menu();
    $datos= $menu -> get_menu_x_rol_id($_SESSION["ROL_ID"]);
?>
<div class="app-menu navbar-menu">
    <div class="navbar-brand-box">
        <a href="../../view/home/index.php" class="logo logo-dark">
            <span class="logo-sm">
                <img src="../../assets/images/logo-sm.png" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="../../assets/images/logo-light.png" alt="" height="70">
            </span>
        </a>
        <a href="../../view/home/index.php" class="logo logo-light">
            <span class="logo-sm">
                <img src="../../assets/images/logo-sm.png" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="../../assets/images/logo-light.png" alt="" height="70">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">
            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu">Menu</span></li>

                <?php
                    foreach ($datos as $row) {
                        if ($row["MEN_GRUPO"] == "Dashboard" && $row["MEND_PERMI"] == "Si") {
                            ?>
                            <li class="nav-item">
                                <a class="nav-link menu-link" href="<?php echo $row["MEN_RUTA"];?>">
                                    <i class="ri-home-4-line"></i> <span data-key="t-widgets"><?php echo $row["MEN_NOM"];?></span>
                                </a>
                            </li>
                            <?php
                        }   
                    }
                ?>

                <!-- Mantenimiento -->
                <li class="nav-item">
                            <a class="nav-link menu-link" href="#sidebarLayouts" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
                                <i class="ri-layout-3-line"></i> <span data-key="t-layouts">Mantenimiento</span>
                            </a>
                            <div class="collapse menu-dropdown" id="sidebarLayouts">
                                <ul class="nav nav-sm flex-column">
                                <?php
                                    foreach ($datos as $row){
                                        if ($row["MEN_GRUPO"]=="Mantenimiento" && $row["MEND_PERMI"]=="Si"){
                                            ?>
                                                <li class="nav-item">
                                                    <a class="nav-link menu-link" href="<?php echo $row["MEN_RUTA"];?>">
                                                        <i class="ri-honour-line"></i> <span data-key="t-widgets"><?php echo $row["MEN_NOM"];?></span>
                                                    </a>
                                                </li>
                                            <?php
                                        }   
                                    }
                                ?>
                                </ul>
                            </div>
                </li> 
                
                <!-- compra -->                   
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarAuth" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarAuth">
                        <i class="ri-shopping-cart-line"></i> <span data-key="t-authentication">Compra</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarAuth">
                        <ul class="nav nav-sm flex-column">
                        <?php
                            foreach ($datos as $row) {
                                if ($row["MEN_GRUPO"] == "Compra" && $row["MEND_PERMI"] == "Si") {
                                    // Asignar ícono según el nombre del menú
                                    $icon = "ri-shopping-cart-line"; // Default
                                    if ($row["MEN_NOM"] == "Nueva Compra") {
                                        $icon = "ri-add-circle-line";
                                    } elseif ($row["MEN_NOM"] == "List.Compra") { // Cambié "Lista de Compras" por "List Compra"
                                        $icon = "ri-file-list-line";
                                    }
                                    ?>
                                    <li class="nav-item">
                                        <a class="nav-link menu-link" href="<?php echo $row["MEN_RUTA"];?>">
                                            <i class="<?php echo $icon; ?>"></i> <span data-key="t-widgets"><?php echo $row["MEN_NOM"];?></span>
                                        </a>
                                    </li>
                                    <?php
                                }   
                            }
                        ?>
                        </ul>
                    </div>
                </li>

                <!-- venta -->
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarVenta" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarVenta">
                        <i class="ri-hand-coin-line"></i> <span data-key="t-venta">Venta</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarVenta">
                        <ul class="nav nav-sm flex-column">
                        <?php
                            foreach ($datos as $row) {
                                if ($row["MEN_GRUPO"] == "Venta" && $row["MEND_PERMI"] == "Si") {
                                    // Asignar ícono según el nombre del menú
                                    $icon = "ri-hand-coin-line"; // Cambiado a ri-hand-coin-line
                                    if ($row["MEN_NOM"] == "Nueva Venta") {
                                        $icon = "ri-add-box-line";
                                    } elseif ($row["MEN_NOM"] == "Lista de Ventas") {
                                        $icon = "ri-file-list-2-line";
                                    }
                                    ?>
                                    <li class="nav-item">
                                        <a class="nav-link menu-link" href="<?php echo $row["MEN_RUTA"];?>">
                                            <i class="<?php echo $icon; ?>"></i> <span data-key="t-widgets"><?php echo $row["MEN_NOM"];?></span>
                                        </a>
                                    </li>
                                    <?php
                                }   
                            }
                        ?>
                        </ul>
                    </div>
                </li>
                <!-- producto -->
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarProducto" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarProducto">
                        <i class="ri-gift-line"></i> <span data-key="t-producto">Producto</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarProducto">
                        <ul class="nav nav-sm flex-column">
                        <?php
                            foreach ($datos as $row) {
                                if ($row["MEN_GRUPO"] == "Producto" && $row["MEND_PERMI"] == "Si") {
                                    // Asignar ícono según el nombre del menú
                                    $icon = "ri-gift-line"; // Default
                                    if ($row["MEN_NOM"] == "Mnt.Categoria") {
                                        $icon = "ri-archive-line"; // Nuevo ícono para Mnt.Categoria
                                    } elseif ($row["MEN_NOM"] == "Mnt.Producto") {
                                        $icon = "ri-shopping-bag-line"; // Nuevo ícono para Mnt.Producto
                                    } elseif ($row["MEN_NOM"] == "Mnt.Subcategoria") {
                                        $icon = "ri-archive-drawer-line"; // Nuevo ícono para Mnt.Subcategoria
                                    }
                                    ?>
                                    <li class="nav-item">
                                        <a class="nav-link menu-link" href="<?php echo $row["MEN_RUTA"];?>">
                                            <i class="<?php echo $icon; ?>"></i> <span data-key="t-widgets"><?php echo $row["MEN_NOM"];?></span>
                                        </a>
                                    </li>
                                    <?php
                                }   
                            }
                        ?>
                        </ul>
                    </div>
                </li>

                <!-- Caja -->
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarCaja" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarCaja">
                        <i class="ri-safe-line"></i> <span data-key="t-authentication">Caja</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarCaja">
                        <ul class="nav nav-sm flex-column">
                        <?php
                            foreach ($datos as $row) {
                                if ($row["MEN_GRUPO"] == "Caja" && $row["MEND_PERMI"] == "Si") {
                                    // Asignar ícono según el nombre del menú
                                    $icon = "ri-safe-line"; // Default
                                    if ($row["MEN_NOM"] == "Mnt.Caja") {
                                        $icon = "ri-safe-2-line";
                                    } elseif ($row["MEN_NOM"] == "Mnt.Gasto") {
                                        $icon = "ri-money-dollar-box-line";
                                    }
                                    ?>
                                    <li class="nav-item">
                                        <a class="nav-link menu-link" href="<?php echo $row["MEN_RUTA"];?>">
                                            <i class="<?php echo $icon; ?>"></i> <span data-key="t-widgets"><?php echo $row["MEN_NOM"];?></span>
                                        </a>
                                    </li>
                                    <?php
                                }   
                            }
                        ?>
                        </ul>
                    </div>
                </li>

                <!--cliente-->
                <?php
                    foreach ($datos as $row) {
                        if ($row["MEN_GRUPO"] == "Cliente" && $row["MEND_PERMI"] == "Si") {
                            ?>
                            <li class="nav-item">
                                <a class="nav-link menu-link" href="<?php echo $row["MEN_RUTA"];?>">
                                    <i class="ri-user-3-line"></i> <span data-key="t-widgets"><?php echo $row["MEN_NOM"];?></span>
                                </a>
                            </li>
                            <?php
                        }   
                    }
                ?>

                <!--List.Compra-->
                <?php
                    foreach ($datos as $row) {
                        if ($row["MEN_GRUPO"] == "List.Precio" && $row["MEND_PERMI"] == "Si") {
                            ?>
                            <li class="nav-item">
                                <a class="nav-link menu-link" href="<?php echo $row["MEN_RUTA"];?>">
                                    <i class=" ri-list-check"></i> <span data-key="t-widgets"><?php echo $row["MEN_NOM"];?></span>
                                </a>
                            </li>
                            <?php
                        }   
                    }
                ?>
            </ul>
            
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>