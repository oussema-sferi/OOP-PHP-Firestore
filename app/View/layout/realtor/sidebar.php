<?php
$baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . "/app/View/";
?>
<div id="layoutSidenav_nav">
    <nav class="sidenav shadow-right sidenav-light">
        <div class="sidenav-menu">
            <div class="nav accordion" id="accordionSidenav">
                <!-- Sidenav Heading (Custom)-->
                <div class="sidenav-menu-heading">Dashboard</div>
                <!-- Sidenav Link (Blog Posts)-->
                <a class="nav-link" href="<?=$baseUrl . 'stories/list.php'?>">
                    <div class="nav-link-icon"><i data-feather="list"></i></div>
                    My Stories
                </a>
                <!-- Sidenav Link (Pro Services)-->
                <a class="nav-link" href="<?=$baseUrl . 'pro-services/list.php'?>">
                    <div class="nav-link-icon"><i data-feather="list"></i></div>
                    My Pro Services
                </a>
                <!-- Sidenav Link (Client)-->
                <a class="nav-link" href="<?=$baseUrl . 'clients/list.php'?>">
                    <div class="nav-link-icon"><i data-feather="list"></i></div>
                    My Clients
                </a>
            </div>
        </div>
    </nav>
</div>
