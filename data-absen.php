<?php
session_start();
include "function.php";

$data_absen = $_SESSION['role'] == 2 ? ambil_data_absen_dosen($_SESSION['id']): ambil_data_absen($_SESSION['id']);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "head.php"?>

    <title>Kelola Absensi</title>

    <?php include "css.php"?>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php include "sidebar.php";?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <?php include "navbar.php";?>

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                </div>
                <div style="text-align:center;">
                    <img src="img/<?= ambil_logo_data()['img']?>" width="250" class="img-profile rounded-circle"alt="logo">
                     <h3 style="color:#1cc88a;font-family:sans" class="text-dark pt-2" > Data Absensi Mahasiswa </h3> 
                </div>

                        <?php if (get_flash_name('berhasil_tambah_absen') != ""):?>
                        <div class="alert alert-success my-3">
                            <?= get_flash_message('berhasil_tambah_absen')?>
                        </div>
                        <?php endif;?>

                        <?php if (get_flash_name('gagal_tambah_absen') != ""):?>
                        <div class="alert alert-danger my-3">
                            <?= get_flash_message('gagal_tambah_absen')?>
                        </div>
                        <?php endif;?>

                    <div class="card-body card mb-4">
                        <div class="card-header bg-warning py-3">
                            <h6 class="m-0 font-weight-bold text-dark">List Data Absensi </h6>
                        </div> <br>
                     <!-- Tambah Data Hanya Untuk Admin -->
                        <?php if ($_SESSION['role'] == 1 ):?>
                            <a href="tambah-data-absen.php" class="btn btn-info float-right">Tambah Absensi</a>
                        <?php endif;?>
                        <br>
                            <div class="table-responsive">
                                <?php if ($_SESSION['role'] == 1):?>
                                <table class="table table-striped table-hover table-bordered" id="table-absensi">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>Tanggal Absensi</th>
                                            <th>Dosen Pengampu</th>
                                            <th>Waktu Absensi</th>
                                            <th>Waktu Dispensasi</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php foreach ($data_absen as $data):?>
                                        <tr>
                                            <td><?= $data['nama_matkul'] ?></td>
                                            <td><?= $data['tgl_absen']?></td>
                                            <td><?= $data['dosen_pengampu']?></td>
                                            <td><?= $data['jam_masuk'].'-'.$data['jam_keluar']?></td>
                                            <td><?= $data['waktu_dispensasi']?></td>
                                            <td>
                                                <a href="list-presensi.php?id-presensi=<?= $data['presensi_id']?>"
                                                    class="btn btn-success">Lihat Absensi</a>
                                                <a href="ubah-data-absen.php?id=<?=$data['presensi_id']?>"
                                                    class="btn btn-warning">Edit</a>
                                                <button data-id="<?=$data['presensi_id']?>"
                                                    class="btn-remove btn btn-danger">Hapus</button>
                                            </td>
                                        </tr>
                                        <?php endforeach;?>
                                    </tbody>
                                </table>

                                <!-- Tampilan Untuk Dosen -->
                                <?php endif;?>
                                <?php if ($_SESSION['role'] == 2):?>
                                <table class="table table-striped table-hover table-bordered" id="table-absensi">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>Matkul</th>
                                            <th>Waktu Absen</th>
                                            <th>Tanggal Absen</th>
                                            <th>Waktu Dispensasi</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php foreach ($data_absen as $data):?>
                                        <tr>
                                            <td><?= $data['nama_presensi'] ?></td>
                                            <td><?= $data['nama_matkul']?></td>
                                            <td><?= $data['jam_masuk'].'-'.$data['jam_keluar']?></td>
                                            <td><?= $data['tgl_absen']?></td>
                                            <td><?= $data['waktu_dispensasi']?></td>
                                            <td>
                                                <a href="list-presensi.php?id-presensi=<?= $data['presensi_id']?>"
                                                    class="btn btn-success">Lihat Presensi</a>
                                            </td>
                                        </tr>
                                        <?php endforeach;?>
                                    </tbody>
                                </table>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>
            </div>
            <!-- End of Content Wrapper -->

            <?php include "footer.php"?>
        </div>
        <!-- End of Page Wrapper -->

        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>

        <!-- Logout Modal-->
        <?php include "logout_modal.php"?>

        <?php include "js.php"?>
        <script>
        $(document).ready(function() {
            let tableDataAbsensi = $("#table-absensi").DataTable()
            $(document).on("click", ".btn-remove", function () {
                let idMataKuliah = $(this).attr('data-id')
                console.log($(this).attr('data-id'));
                Swal.fire({
                    title: 'Question',
                    text: 'Yakin ingin menghapus ?',
                    showCancelButton: true,
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                         Swal.fire('Success', 'Data Absensi Berhasil Dihapus', 'success').then(() => {
                            setTimeout(function() {
                            window.location.href = 'hapus-data-absen.php?id=' + idMataKuliah
                            }, 1000);
                        });
                    }
                })
            })
        })
        </script>

</body>

</html>