<?php
session_start();
include "function.php";

if (isset($_POST['ubah-absensi'])) {
     update_data_absen($_POST);
}
$data_absen = ambil_data_absen_by_id($_GET['id']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "head.php"?>

    <title>Edit Absensi</title>

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
                <div class="container">
                      <div class="row justify-content-center">
                      <div class="head text-center my-4 pt-3">
                    <h3 style="color:#1cc88a;font-family:sans" class="text-dark pt-2" >Form Edit Data Absensi</h3> 
                </div>
            </div>

            <!-- Form Edit Data -->
                <div class="card shadow mb-4">
                        <div class="card-header bg-warning py-3">
                            <h6 class="m-0 font-weight-bold text-dark">Edit Absensi di Form Berikut:</h6>
                            <a href="data-mata-kuliah.php" class="btn btn-secondary float-right">Kembali</a>
                        </div>

                        <div class="card-body">
                       <form action="" method="POST" id="form">
                            <input type="hidden" name="id" value=<?= $_GET['id']?>>
                            <input type="hidden" name="user-id" value="<?= $_SESSION['id']?>">
                            <input type="hidden" name="ubah-absensi">
                            <input type="hidden" name="jam-masuk" value="<?= $data_absen['jam_masuk']?>">
                            <input type="hidden" name="jam-keluar" value="<?= $data_absen['jam_keluar']?>">
                            <div class="form-group">
                                <label for="" class="form-label">Nama Mata Kuliah<sup
                                        class="text-danger">*</sup></label>
                                <select name="id-mata-kuliah" class="form-control" required>
                                    
                                    <?php foreach (ambil_data_mata_kuliah() as $data):?>
                                    <option value="<?= $data['id_mata_kuliah']?>||<?=$data['id_dosen_pengampu']?>"
                                        <?= $_GET['id-dosen'] == $data['id_dosen_pengampu'] && $_GET['id-matkul'] == $data['id_mata_kuliah'] ? "selected" :""?>>
                                        <?= $data['matkul']?> - <?= $data['dosen_pengampu']?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="" class="form-label">Judul Presensi<sup class="text-danger">*</sup></label>
                                <input name="judul-presensi" value="<?= $data_absen['nama']?>" class="form-control"
                                    required>
                            </div>

                            <div class="form-group">
                                <label for="" class="form-label">Waktu Masuk <sup class="text-danger">*</sup></label>
                                <input name="waktu-masuk" value="<?= $data_absen['jam_masuk']?>"
                                    class="time form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="" class="form-label">Waktu Keluar <sup class="text-danger">*</sup></label>
                                <input name="waktu-keluar" value="<?= $data_absen['jam_keluar']?>"
                                    class="time-exit form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="" class="form-label">Tanggal Absensi<sup class="text-danger">*</sup></label>
                                <input type="date" name="tanggal-absensi" value="<?= $data_absen['tgl_absen']?>"
                                    class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="" class="form-label">Waktu Dispensasi<sup
                                        class="text-danger">*</sup></label>
                                <input type="number" name="waktu-dispensasi"
                                    value="<?= $data_absen['waktu_dispensasi']?>" class="form-control" required>
                            </div>
                            <button class="btn btn-warning w-100" name="ubah-absensi">Edit Data Absensi</button>
                        </form>
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
        <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"></span>
                        </button>
                    </div>
                    <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <a class="btn btn-primary" href="login.php">Logout</a>
                    </div>
                </div>
            </div>
        </div>

       <?php include "js.php"?>
        <script>
        $(document).ready(function() {
            $('.time').timepicker({
                timeFormat: 'HH:mm:ss ',
                interval: 1,
                minTime: '6',
                maxTime: '11:59pm',
                defaultTime: $("[name=jam-masuk]").val(),
                dynamic: false,
                dropdown: true,
                scrollbar: true
            });
            $('.time-exit').timepicker({
                timeFormat: 'HH:mm:ss ',
                interval: 1,
                minTime: '6',
                maxTime: '11:59pm',
                defaultTime: $("[name=jam-keluar]").val(),
                dynamic: false,
                dropdown: true,
                scrollbar: true
            });

            $("#form").one('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    icon: 'question',
                    title: 'Konfirmasi',
                    text: 'Apakah data sudah benar ?',
                    showCancelButton: true,
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(this).submit();
                    }
                })

            })
        })
        </script>

</body>

</html>