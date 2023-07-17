<?php
session_start();
include "function.php";

$list_absensi = ambiL_seluruh_data_absensi($_GET['id']);

$kehadiran = [];
foreach (ambil_kehadiran_mahasiswa($_SESSION['id']) as $data) {
    $kehadiran[] = $data['id_mata_kuliah'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "head.php"?>

    <title>List Data Pertemuan Absensi</title>

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

                    <!-- Tampilan Untuk admin dan dosen -->
                    <?php if ($_SESSION['role'] == 2 || $_SESSION['role'] == 1):?>
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">
                            <?= isset($list_absensi[0]['kode_kelas']) ? $list_absensi[0]['kode_kelas']: ""?> -
                            <?= isset($list_absensi[0]['nama_matkul']) ? $list_absensi[0]['nama_matkul']: ""?> -
                            <?= isset($list_absensi[0]['kelas_mata_kuliah']) ? $list_absensi[0]['kelas_mata_kuliah']: ""?>
                            </h1>
                    </div>

                    <div class="card-body card mb-4">
                        <div class="card-header bg-warning py-3">
                            <h6 class="m-0 font-weight-bold text-dark">List Data Pertemuan Absensi </h6>
                            <a href="data-mata-kuliah.php" class="btn btn-secondary float-right">Kembali</a>
                        </div>
                        <br>
                        <div>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-bordered" id="table-absensi">
                                    <thead>
                                        <tr>
                                            <th>Pertemuan</th>
                                            <th>Tanggal Absensi</th>
                                            <th>Mata Kuliah</th>
                                            <th>Waktu Absensi</th>
                                            <th>Waktu Dispensasi</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php foreach ($list_absensi as $data):?>
                                        <tr>
                                            <td><?= $data['nama_presensi'] ?></td>
                                            <td><?= $data['tgl_absen']?></td>
                                            <td><?= $data['nama_matkul']?></td>
                                            <td><?= $data['jam_masuk'].'-'.$data['jam_keluar']?></td>
                                            <td><?= $data['waktu_dispensasi']?></td>
                                            <td>
                                                <a href="list-presensi.php?id-presensi=<?= $data['presensi_id']?>"
                                                    class="btn btn-success">Lihat Absensi</a>

                                                <!-- Edit Hanya dilakukan Oleh Admin -->
                                                <?php if ($_SESSION['role'] == 1):?>
                                                <a href="ubah-data-absen.php?id-matkul=<?= $_GET['id']?>&id=<?= $data['presensi_id']?>&id-dosen=<?= $data['id_dosen']?>"
                                                    class="btn-edit btn btn-primary">Edit</a>
                                                <a href="hapus-data-absen.php?id=<?=$data['presensi_id']?>"
                                                    class="btn btn-danger">Hapus</a>
                                                <?php endif;?>
                                            </td>
                                        </tr>
                                        <?php endforeach;?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php endif;?>

                    <!-- Tampilan Untuk User Mahasiswa -->
                    <?php if ($_SESSION['role'] == 3):?>
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">
                            <?= isset($list_absensi[0]['nama_matkul']) ? $list_absensi[0]['nama_matkul']: ""?></h1>
                    </div>
                    
                    <div class="card-body card mb-4">
                        <div class="card-header bg-warning py-3">
                            <h6 class="m-0 font-weight-bold text-dark">List Data Pertemuan Absensi </h6>
                            <a href="list-kelas.php" class="btn btn-secondary float-right">Kembali</a>
                        </div>
                        <br>
                        <div>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-bordered" id="table-absensi">
                                    <thead>
                                        <tr>
                                            <th>Nama Pertemuan</th>
                                            <th>Mata Kuliah</th>
                                            <th>Waktu Absensi</th>
                                            <th>Tanggal Absensi</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($list_absensi as $data):?>
                                        <?php if (in_array($data['presensi_id'], $kehadiran)):?>
                                        <tr>
                                            <td><?= $data['nama_presensi'] ?></td>
                                            <td><?= $data['nama_matkul']?></td>
                                            <td><?= $data['jam_masuk'].'-'.$data['jam_keluar']?></td>
                                            <td><?= $data['tgl_absen']?></td>
                                            <td>
                                                <a href="list-presensi.php?id-presensi=<?= $data['presensi_id']?>&id-mahasiswa=<?= $_SESSION['id']?>"
                                                    class="btn btn-success">Lihat Detail</a>
                                            </td>
                                        </tr>
                                        <?php else:?>
                                        <tr>
                                            <td><?= $data['nama_presensi'] ?></td>
                                            <td><?= $data['nama_matkul']?></td>
                                            <td><?= $data['jam_masuk'].'-'.$data['jam_keluar']?></td>
                                            <td><?= $data['tgl_absen']?></td>
                                            <td><?= $data['waktu_dispensasi']?></td>
                                            <td>
                                                <?php
                                                    $jam = strtotime(date("H:i:s"));
                                                    $jam_masuk = strtotime($data['jam_masuk']);
                                                ?>
                                                <button class="btn-absen btn btn-warning"
                                                    <?= $jam < $jam_masuk ? "disabled" : ""?>
                                                    data-id="<?= $data['presensi_id']?>"
                                                    data-id-mahasiswa="<?= $_SESSION['id']?>">Isi Kehadiran</button>
                                            </td>
                                        </tr>
                                        <?php endif;?>
                                        <?php endforeach;?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php endif;?>
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

        <?php include "logout_modal.php"?>

        <?php include "js.php"?>
        <script>
        $(document).ready(function() {
            let tableListPresensi = $("#table-absensi").DataTable();
            $('.btn-absen').on('click', function() {
                let idAbsensi = $(this).attr('data-id');
                let idMahasiswa = $(this).attr('data-id-mahasiswa');
                Swal.fire({
                    title: "Isi Kehadiran",
                    allowOutsideClick: false,
                    showCancelButton: true,
                    html: `
                            <p>Status Kehadiran</p>
                            <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" name="status" value="Hadir">
                              <label class="form-check-label">Hadir</label>
                            </div>
                            <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" name="status" value="Sakit">
                              <label class="form-check-label">Sakit</label>
                            </div>
                            <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" name="status" value="Izin">
                              <label class="form-check-label">Izin</label>
                            </div>
                            <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" name="status" value="Dispen">
                              <label class="form-check-label">Dispensasi</label>
                            </div>
                            <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" name="status" value="Alpha">
                              <label class="form-check-label">Alpha</label>
                            </div>
                            <video id="video" class="w-100">Video stream not available.</video>
                            <img id="photo" alt="This photo will apear here" class="w-100" src="">
                            <canvas id="canvas" class="d-none"> </canvas>
                            <input type="hidden" name="coordinate">
                            <button class="take-photo btn btn-warning mt-2">Ambil Photo</button>
                           `,
                    didOpen: () => {
                        let video = $("#video").get(0);
                        $("#photo").hide()

                        // akses kamera
                        navigator.mediaDevices.getUserMedia({
                            video: true,
                            audio: false
                        }).then(function(stream) {
                            $("#video").show();
                            video.srcObject = stream;
                            video.play();
                            stream.active;
                        }).catch((error) => {
                            $("#video").hide();
                            $(".take-photo").hide();
                            swal.showValidationMessage(
                                "*Silahkan izinkan akses kamera pada browser")
                        })

                        // akses lokasi
                        navigator.geolocation.getCurrentPosition(
                            function(position) {
                                $('[name=coordinate]').val(position.coords.latitude +
                                    "," + position.coords.longitude)
                            },
                            function() {
                                swal.showValidationMessage(
                                    "*Silahkan izinkan akses lokasi pada browser")
                            }
                        );

                        //ambil photo
                        $(document).on('click', '.take-photo', function() {
                            let canvas = $("#canvas").get(0);
                            const context = canvas.getContext('2d');
                            canvas.width = 512;
                            canvas.height = 512;
                            context.drawImage(video, 0, 0, 512, 512);

                            const data = canvas.toDataURL('image/png');
                            $("#photo").attr('src', data);
                            $("#photo").show();
                            $("#video").hide();
                            $(this).removeClass("btn-warning")
                            $(this).removeClass("take-photo")
                            $(this).addClass("btn-danger")
                            $(this).addClass("retake-photo")
                            $(this).text("Ambil Ulang Photo")
                        })

                        // retake photo
                        $(document).on('click', '.retake-photo', function() {
                            $("#photo").hide();
                            $("#video").show();
                            $(this).removeClass("btn-danger")
                            $(this).removeClass("retake-photo")
                            $(this).addClass("take-photo")
                            $(this).addClass("btn-warning")
                            $(this).text("Ambil photo")
                        })
                    },
                    preConfirm: function() {
                        return new Promise(function(resolve) {
                            let status = $("[name=status]:checked").val()

                            if (status === undefined) {
                                swal.showValidationMessage("Pilih Status Kehadiran")
                                swal.enableButtons();
                                return 0;
                            }

                            if ($('[name=coordinate]').val().length === 0) {
                                swal.showValidationMessage(
                                    "Silahkan enable location pada browser")
                                swal.enableButtons();
                                return 0;
                            }

                            if ($("#photo").attr('src').length === 0) {
                                swal.showValidationMessage(
                                    "Silahkan ambil photo terlebih dahulu")
                                swal.enableButtons();
                                return 0;
                            }

                            console.log()
                            swal.resetValidationMessage();
                            resolve({
                                'imageData': $("#photo").attr('src'),
                                'status': status,
                                'coordinate': $('[name=coordinate]').val()
                            })
                        })
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: 'tambah-absensi.php',
                            method: 'POST',
                            data: {
                                'status': result.value.status,
                                'imageData': result.value.imageData,
                                'coordinate': result.value.coordinate,
                                'idAbsensi': idAbsensi,
                                'idMahasiswa': idMahasiswa
                            },
                            success: function(data) {
                                let response = JSON.parse(data);
                                if (response[0].code === 0) {
                                    Swal.fire('error', response[0].message, 'error')
                                    return 0;
                                }
                                window.location.reload();
                            }
                        })
                    }
                })
            })
        })
        </script>

</body>

</html>