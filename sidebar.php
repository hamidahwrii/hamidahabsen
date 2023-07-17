<!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-warning sidebar sidebar-dark accordion" id="accordionSidebar">
            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <div >
                    <img src="img/<?= ambil_logo_sidebar()['img']?>" width="55" class="img-profile rounded-circle"alt="logo">
                </div>
                <div class="sidebar-brand-text mx-3">Sistem Absensi | POLSRI</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <!--<li class="nav-item <?php /*if ((isset($_GET['halaman']) && $_GET['halaman'] == 'dashboard') || isset($_GET['halaman']) == false) :*/?> active <?php /*endif;*/?>">
                <a class="nav-link" href="index.php?halaman=dashboard">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>-->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Tampilan Sidebar Admin DATA MATA KULIAH -->
            <hr class="sidebar-divider">
            <?php if($_SESSION['role'] == 1):?>
                <li class="nav-item <?php if ((isset($_GET['halaman']) && $_GET['halaman'] == 'data-mata-kuliah'))  :?> active <?php endif;?>">
                    <a class="nav-link" href="data-mata-kuliah.php?halaman=data-mata-kuliah">
                        <i class="fa fa-calendar" aria-hidden="true"></i>
                        <span>Data Mata Kuliah</span>
                    </a>
                </li>

                <li class="nav-item <?php if ((isset($_GET['halaman']) && $_GET['halaman'] == 'data-absen'))  :?> active <?php endif;?>">
                    <a class="nav-link" href="data-absen.php?halaman=data-absen">
                        <i class="fa fa-calendar" aria-hidden="true"></i>
                        <span>Data Absensi</span>
                    </a>
                </li>

                <li class="nav-item <?php if ((isset($_GET['halaman']) && $_GET['halaman'] == 'list-data-keterlambatan'))  :?> active <?php endif;?>">
                    <a class="nav-link" href="list-data-keterlambatan.php?halaman=list-data-keterlambatann">
                        <i class="fa fa-calendar" aria-hidden="true"></i>
                        <span>Data Keterlambatan</span>
                    </a>
                </li>

                <li class="nav-item <?php if ((isset($_GET['halaman']) && $_GET['halaman'] == 'setting'))  :?> active <?php endif;?>">
                    <a class="nav-link" href="setting.php?halaman=setting">
                        <i class="fa fa-wrench" aria-hidden="true"></i>
                        <span>Setting</span>
                    </a>
                </li>
            <?php endif;?>

            <!-- Tampilan Sidebar Dosen DATA ABSENSI -->
            <?php if($_SESSION['role'] == 2):?>
                <li class="nav-item <?php if ((isset($_GET['halaman']) && $_GET['halaman'] == 'data-mata-kuliah'))  :?> active <?php endif;?>">
                    <a class="nav-link" href="data-mata-kuliah.php?halaman=data-mata-kuliah">
                        <i class="fa fa-calendar" aria-hidden="true"></i>
                        <span>Absensi</span>
                    </a>
                </li>
            <?php endif;?>

            <!-- Tampilan Sidebar Mahasiswa -->
            <?php if($_SESSION['role'] == 3 ):?>
                <li class="nav-item <?php if ((isset($_GET['halaman']) && $_GET['halaman'] == 'list-kelas'))  :?> active <?php endif;?>">
                    <a class="nav-link" href="list-kelas.php">
                        <i class="fas fa-fw fa-tachometer-alt"></i>
                        <span>Absensi</span>
                    </a>
                </li>
            <?php endif;?>

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->