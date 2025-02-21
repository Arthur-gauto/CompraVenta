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
                    foreach ($datos as $row){
                        if ($row["MEN_GRUPO"]=="Dashboard" && $row["MEND_PERMI"]=="Si"){
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
                
                <li class="menu-title"><span data-key="t-menu">Mantenimiento</span></li>
                
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
                
                <li class="menu-title"><span data-key="t-menu">Compra</span></li>
                <!-- compra -->                   
                <li class="nav-item">
                            <a class="nav-link menu-link" href="#sidebarAuth" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarAuth">
                                <i class="ri-layout-3-line"></i> <span data-key="t-authentication">Compra</span>
                            </a>
                            <div class="collapse menu-dropdown" id="sidebarAuth">
                                <ul class="nav nav-sm flex-column">
                                <?php
                                    foreach ($datos as $row){
                                        if ($row["MEN_GRUPO"]=="Compra" && $row["MEND_PERMI"]=="Si"){
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

                <li class="menu-title"><span data-key="t-menu">Venta</span></li>
                <!-- venta -->
                <li class="nav-item">
                            <a class="nav-link menu-link" href="#sidebarLanding" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLanding">
                                <i class="ri-layout-3-line"></i> <span data-key="t-authentication">Venta</span>
                            </a>
                            <div class="collapse menu-dropdown" id="sidebarLanding">
                                <ul class="nav nav-sm flex-column">
                                <?php
                                    foreach ($datos as $row){
                                        if ($row["MEN_GRUPO"]=="Venta" && $row["MEND_PERMI"]=="Si"){
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
            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>