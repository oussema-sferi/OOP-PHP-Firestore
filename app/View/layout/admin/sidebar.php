<div id="layoutSidenav_nav">
    <nav class="sidenav shadow-right sidenav-light">
        <div class="sidenav-menu">
            <div class="nav accordion" id="accordionSidenav">
                <!-- Sidenav Heading (Custom)-->
                <div class="sidenav-menu-heading">Dashboard</div>
                <!-- Sidenav Link (Users)-->
                <a class="nav-link" href="<?='users_list.php'?>">
                    <div class="nav-link-icon"><i data-feather="user"></i></div>
                    Users
                </a>
                <!-- Sidenav Accordion (Cell Rules)-->
                <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseEmails" aria-expanded="false" aria-controls="collapseDashboards">
                    <div class="nav-link-icon"><i data-feather="mail"></i></div>
                    Emails
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseEmails" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                        <a class="nav-link" href="<?='email_content.php'?>">Invitation Email</a>
                        <a class="nav-link" href="<?='reset_password_email.php'?>">Reset Password Email</a>
                    </nav>
                </div>
            </div>
        </div>
    </nav>
</div>