<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE); //mengatur jenis error pada script PHP
//E_ERROR = eror serius(menghentikan eksekusi program)
//E_WARNING = error peringatan
//E_PARSE = error pada script php
$connection = new mysqli ("localhost", "root", "", "db_absen"); //melakukan koneksi

//cek koneksi
if ($connection->connect_error != null) {
    echo  "Gagal terhubung ke Database";
    die(); // menghentikan eksekusi program
}