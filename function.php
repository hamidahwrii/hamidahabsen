<?php
include "connection.php";
include "checker.php";
include "helper.php";

function login($form){
    global $connection;


    $username = $form['username'];
	$password = $form['password'];
	$check_username_query = "SELECT * FROM users WHERE username = '$username'";
	$check_username_result = mysqli_query($connection, $check_username_query);
	$username_in_db = mysqli_fetch_assoc($check_username_result);
	$role = $form['role'];
	if ($username_in_db !=   null) {
		if ($username== $username_in_db['username']){
			if (password_verify($password, $username_in_db['password'])){
                if (($role != $username_in_db['role']) && ($username_in_db['role'] == 3)) {
                    set_flash_message('login_failed','Maaf anda bukan Admin/Dosen');  
                    return;    
                }
                if (($role != $username_in_db['role']) && ($username_in_db['role'] == 1)) {
                    set_flash_message('login_failed','Maaf anda bukan Dosen/Mahasiswa');      
                    return;
                }
                if (($role != $username_in_db['role']) && ($username_in_db['role'] == 2)) {
                    set_flash_message('login_failed','Maaf anda bukan Admin/Mahasiswa');      
                    return;
                }
                $_SESSION['login']=true;
                $_SESSION['id']=$username_in_db ['id'];
                $_SESSION['username']=$username_in_db ['username'];
                $_SESSION['role']=$username_in_db['role']; 
                $_SESSION['foto']=$username_in_db['img'];
                $_SESSION['nama'] = $username_in_db['nama'];
				return redirect('index.php');
			} else{
			    set_flash_message('login_failed','Username atau password salah ');
		    }
		}else{
			set_flash_message('login_failed','Username atau password tidak ditemukan');
		}
	} else {
		set_flash_message('login_failed','Isi Username dan Password ');

	}

}

function tambah_user($form){
	global $connection;

	$username = htmlspecialchars(strtolower(stripcslashes($form['username'])));
 	$password = mysqli_escape_string($connection, $form['password']);
	$role = $form['role'];
	$has_password = password_hash($password, PASSWORD_DEFAULT);

	$query = $connection->query("SELECT * FROM users WHERE username = '$username'");
    if ($query->num_rows > 0) {
        set_flash_message('add_failed', 'Username Sudah Ada, Silahkan Periksa Kembali!');
        redirect('user.php');
        return;
    }

    if ($role == 1) {
        $connection->query("INSERT INTO users (username, password, nama, role) VALUES ('$username', '$has_password','$username','$role')");
    }

    if ($role == 2) {
        $nomor_induk = $form['nip'];
        $nama = $form['nama'];
        $connection->query("INSERT INTO users (username, password, nama, role, nomor_induk) VALUES ('$username', '$has_password', '$nama', '$role', '$nomor_induk')");
    }

    if ($role == 3) {
        $nomor_induk = $form['nim'];
        $nama = $form['nama'];
        $angkatan = $form['angkatan'];
        $kelas = $form['kelas'];
        $prodi = $form['prodi'];
        $jurusan = $form['jurusan'];
        $alamat = $form['alamat'];
        $connection->query("INSERT INTO users (username, password, nama, role, nomor_induk, angkatan, kelas, prodi, jurusan, alamat) VALUES ('$username', '$has_password', '$nama', '$role', '$nomor_induk', '$angkatan', '$kelas', '$prodi', '$jurusan', '$alamat')");
    }

	if ($connection->affected_rows > 0) {
		set_flash_message('add_success', 'Berhasil Melakukan Tambah Data User');
		redirect('user.php');
	} else {
		set_flash_message('add_failed', 'Gagal Melakukan Tambah Data User');
	}
}

function ambil_data_user() {
	global $connection;
 
	$users = $connection->query("SELECT * FROM users")->fetch_all(MYSQLI_ASSOC);
 
	return $users;
}

function ambil_data_mahasiswa() {
	global $connection;
 
	$users = $connection->query("SELECT * FROM users WHERE role='3'")->fetch_all(MYSQLI_ASSOC);
 
	return $users;
}

function ambil_data_dosen() {
	global $connection;
 
	$users = $connection->query("SELECT * FROM users WHERE role='2'")->fetch_all(MYSQLI_ASSOC);
 
	return $users;
}

function update_data_user($form){
	global $connection;

	$id=$form['id'];
	$username = htmlspecialchars(strtolower(stripcslashes($form['username'])));
 	$password = mysqli_escape_string($connection, $form['password']);
 	$nama = $form['nama'];
	$role = $form['role'];
	$nomor_induk = $form['nomor_induk'];
	$kelas = $form['kelas'];
	$angkatan = $form['angkatan'];
	$prodi = $form['prodi'];
	$jurusan = $form['jurusan'];
	$alamat = $form['alamat'];
	$has_password = password_hash($password, PASSWORD_DEFAULT);

	if ($password == "") {
		$connection->query("UPDATE users
		SET
			username='$username',
			nama='$nama',
			role='$role',
			nomor_induk ='$nomor_induk',
			kelas='$kelas',
			angkatan='$angkatan',
			prodi='$prodi',
			jurusan='$jurusan',
			alamat='$alamat'
		WHERE id = '$id'
		");
	} else {
		$connection->query("UPDATE users
		SET
			username='$username',
			password='$has_password',
			nama='$nama',
			role='$role',
			nomor_induk='$nomor_induk',
			kelas='$kelas',
			angkatan='$angkatan',
			prodi='$prodi',
			jurusan='$jurusan',
			alamat='$alamat'
		WHERE id = '$id'
		");
	}
	
	if ($connection->affected_rows > 0) {
		set_flash_message('add_success', 'Berhasil Melakukan Edit Data User');
		redirect('user.php');
	} else {
		set_flash_message('add_failed', 'Gagal Melakukan Edit Data User');
		redirect('user.php');
	}

}

function update_logo_auth($file) {
    global $connection;

    $nama_file_foto = upload_foto_logo($file, 'logo-auth');

    if(!$nama_file_foto) {
        return redirect('setting.php?halaman=setting');
    }

    $connection->query("UPDATE setting
			SET
				img = '$nama_file_foto'
			WHERE id = 1
		");

    set_flash_message('add_success', 'Berhasil Update Data Logo');

    return redirect('setting.php?halaman=setting');
}

function update_logo_sidebar($file) {
    global $connection;

    $nama_file_foto = upload_foto_logo($file, 'logo-sidebar');

    if(!$nama_file_foto) {
        return redirect('setting.php?halaman=setting');
    }

    $connection->query("UPDATE setting
			SET
				img = '$nama_file_foto'
			WHERE id = 2
		");

    set_flash_message('add_success', 'Berhasil Update Data Logo');

    return redirect('setting.php?halaman=setting');
}

function update_logo_surat($file) {
    global $connection;

    $nama_file_foto = upload_foto_logo($file, 'logo-surat');

    if(!$nama_file_foto) {
        return redirect('setting.php?halaman=setting');
    }

    $connection->query("UPDATE setting
			SET
				img = '$nama_file_foto'
			WHERE id = 3
		");

    set_flash_message('add_success', 'Berhasil Update Data Logo');

    return redirect('setting.php?halaman=setting');
}

function update_logo_data($file) {
    global $connection;

    $nama_file_foto = upload_foto_logo($file, 'logo-data');

    if(!$nama_file_foto) {
        return redirect('setting.php?halaman=setting');
    }

    $connection->query("UPDATE setting
			SET
				img = '$nama_file_foto'
			WHERE id = 4
		");

    set_flash_message('add_success', 'Berhasil Update Data Logo');

    return redirect('setting.php?halaman=setting');
}

function ambil_data_user_by_id($id) {
	global $connection;
 
	$users = $connection->query("SELECT * FROM users WHERE id = '$id'")->fetch_assoc();

	return $users;
}

function update_admin_profile($form, $file){
	global $connection;

	$id=$form['id'];
	$nama= htmlspecialchars(stripcslashes($form['nama']));
	$username= htmlspecialchars(stripcslashes($form['username']));


	if (!($file['foto']['name'] == "")) {
		$nama_file_foto = upload_foto_profil($file, 'foto');
		if(!$nama_file_foto) {
			return redirect('profile.php?id='.$id);
		}

		$connection->query("UPDATE users
			SET
				nama='$nama',
				username='$username',
				img = '$nama_file_foto'
			WHERE id = '$id'
		");
		$_SESSION['foto']=$nama_file_foto;
		$_SESSION['nama'] = $nama;
	
	}else {
		$connection->query("UPDATE users
		SET
			nama='$nama',
			username='$username'
		WHERE id = '$id'
		");
		$_SESSION['nama'] = $nama;

	}
	
	set_flash_message('add_success', 'Berhasil Update Data Profil');
}

function update_dosen_profile($form,$file){
	global $connection;

	$id=$form['id'];
	$nomor_induk= htmlspecialchars(stripcslashes($form['nomor_induk']));
	$nama= htmlspecialchars(stripcslashes($form['nama']));

	// upload gambar
	if (!($file['foto']['name'] == "")) {
		$nama_file_foto = upload_foto_profil($file, 'foto');
		if(!$nama_file_foto) {
			return redirect('profile.php?id='.$id);
		}

		$connection->query("UPDATE users
			SET
				nomor_induk='$nomor_induk',
				nama='$nama',
				img = '$nama_file_foto'
			WHERE id = '$id'
		");
		$_SESSION['foto']=$nama_file_foto;
		$_SESSION['nama'] = $nama;
	
	}else {
		$connection->query("UPDATE users
		SET
			nomor_induk='$nomor_induk',
			nama='$nama'
		WHERE id = '$id'
	");
	$_SESSION['nama'] = $nama;

	}

	
	set_flash_message('add_success', 'Berhasil Update Data Profil');

}

function update_mahasiswa_profile($form,$file){
	global $connection;
	
	$id=$form['id'];
	$nomor_induk= htmlspecialchars(strtolower(stripcslashes($form['nomor_induk'])));
	$nama= htmlspecialchars(stripcslashes($form['nama']));
	$kelas= htmlspecialchars(stripcslashes($form['kelas']));
	$tempat_lahir= htmlspecialchars(stripcslashes($form['tempat_lahir']));
	$tgl_lahir = htmlspecialchars(stripcslashes($form['tgl_lahir']));
	$angkatan= htmlspecialchars(stripcslashes($form['angkatan']));
	$prodi= htmlspecialchars(stripcslashes($form['prodi']));
	$jurusan= htmlspecialchars(stripcslashes($form['jurusan']));
	$alamat = htmlspecialchars(stripcslashes($form['alamat']));
	$moto_hidup =$form['moto_hidup'];
	$kemampuan_pribadi = $form['kemampuan_pribadi'];
	// upload gambar
	if (!($file['foto']['name'] == "")) {
		$nama_file_foto = upload_foto_profil($file, 'foto');
		if(!$nama_file_foto) {
			return redirect('profile.php?id='.$id);
		}

		$connection->query("UPDATE users
			SET
				nomor_induk='$nomor_induk',
				nama='$nama',
				kelas='$kelas',
				tempat_lahir='$tempat_lahir',
				tgl_lahir='$tgl_lahir',
				angkatan='$angkatan',
				prodi='$prodi',
				jurusan='$jurusan',
				alamat='$alamat',
				moto_hidup='$moto_hidup',
				kemampuan_pribadi='$kemampuan_pribadi',
				img = '$nama_file_foto'
			WHERE id = '$id'
		");

		$_SESSION['foto']=$nama_file_foto;
		$_SESSION['nama'] = $nama;
	}else {
		$connection->query("UPDATE users
			SET
				nomor_induk='$nomor_induk',
				nama='$nama',
				kelas='$kelas',
				tempat_lahir='$tempat_lahir',
				tgl_lahir='$tgl_lahir',
				angkatan='$angkatan',
				prodi='$prodi',
				jurusan='$jurusan',
				alamat='$alamat',
				moto_hidup='$moto_hidup',
				kemampuan_pribadi='$kemampuan_pribadi'
			WHERE id = '$id'
		");
		$_SESSION['nama'] = $nama;
	}

	
	set_flash_message('add_success', 'Berhasil Update Data Profil');

}

function tambah_absensi($form) {
    global  $connection;
    $data_matkul = explode('||', $form['id-mata-kuliah']);
    $nama_mata_kuliah = htmlspecialchars(stripcslashes($form['judul-presensi']));
    $id_mata_kuliah = $data_matkul[0];
    $user_id = $data_matkul[1];
    $waktu_masuk = $form['waktu-masuk'];
    $waktu_keluar = $form['waktu-keluar'];
    $tanggal_absensi = $form['tanggal-absensi'];
    $waktu_dispensasi = $form['waktu-dispensasi'];
    $timestamp_akhir_presensi = $tanggal_absensi .' '. $waktu_keluar;
    $connection->query("
        INSERT INTO 
            jadwal_presensi (user_id, nama, jam_masuk, jam_keluar, tgl_absen, waktu_dispensasi, mata_kuliah_id, timestamp_akhir_presensi)
        VALUES ('$user_id','$nama_mata_kuliah', '$waktu_masuk', '$waktu_keluar', '$tanggal_absensi', '$waktu_dispensasi', '$id_mata_kuliah', '$timestamp_akhir_presensi')    
    ");

    if ($connection->affected_rows > 0) {
        set_flash_message('berhasil_tambah_absen', 'Berhasil Melakukan Tambah Data Absensi');
    } else {
        set_flash_message('gagal_tambah_absen', 'Gagal Melakukan Tambah Data Absensi');
    }

    return redirect('data-absen.php?halaman=data-absen');

}

function ambil_data_absen_dosen($id) {
    global $connection;
    return $connection->query("
    SELECT
        jadwal_presensi.id AS presensi_id, 
        mata_kuliah.id AS id_matkul,
        mata_kuliah.`name` AS nama_matkul, 
        jadwal_presensi.nama AS nama_presensi, 
        jadwal_presensi.jam_masuk AS jam_masuk, 
        jadwal_presensi.jam_keluar AS jam_keluar, 
        jadwal_presensi.tgl_absen AS tgl_absen, 
        jadwal_presensi.waktu_dispensasi AS waktu_dispensasi
    FROM
	    jadwal_presensi
	INNER JOIN
	    mata_kuliah
	ON 
		jadwal_presensi.mata_kuliah_id = mata_kuliah.id
    WHERE
	    jadwal_presensi.user_id = '$id' 
     ")->fetch_all(MYSQLI_ASSOC);
}

function ambil_data_absen() {
    global $connection;
    return $connection->query("
    SELECT
        users.nama AS dosen_pengampu, 
        jadwal_presensi.nama AS nama_matkul, 
        jadwal_presensi.id AS presensi_id, 
        jadwal_presensi.jam_masuk AS jam_masuk, 
        jadwal_presensi.jam_keluar AS jam_keluar, 
        jadwal_presensi.tgl_absen AS tgl_absen, 
        jadwal_presensi.waktu_dispensasi AS waktu_dispensasi
    FROM
	    jadwal_presensi
	INNER JOIN
	    users
	ON 
		jadwal_presensi.user_id = users.id
    ")->fetch_all(MYSQLI_ASSOC);
}

function tambah_data_mata_kuliah($form) {
    global $connection;

    $nama_mata_kuliah = htmlspecialchars(stripcslashes($form['name']));
    $id_user = $form['dosen-pengampu'];
    $kode_kelas = random_strings(8);
    $kelas = $form['kelas'];
    $waktu_masuk = $form['waktu-masuk'];
    $waktu_keluar = $form['waktu-keluar'];
    $waktu_absen = $waktu_masuk .' - '.$waktu_keluar;

    $connection->query("
        INSERT INTO 
            mata_kuliah (user_id, name, enroll_code, kelas, waktu_absen)
        VALUES ('$id_user', '$nama_mata_kuliah', '$kode_kelas', '$kelas', '$waktu_absen') 
    ");

    if ($connection->affected_rows > 0) {
        set_flash_message('berhasil_tambah_mata_kuliah', 'Berhasil Melakukan Tambah Data Mata Kuliah');
    } else {
        set_flash_message('gagal_tambah_mata_kuliah', 'Gagal Melakukan Tambah Data Mata Kuliah');
    }

    return redirect('data-mata-kuliah.php?halaman=data-mata-kuliah');
}

function update_data_mata_kuliah($form)
{
    global $connection;
    
    $id = $form['id'];
    $nama_mata_kuliah = htmlspecialchars(stripcslashes($form['name']));
    $id_user = $form['dosen-pengampu'];
    $kode_kelas = $form['enroll-code'];
    $kelas = $form['kelas'];
    $waktu_masuk = $form['waktu-masuk'];
    $waktu_keluar = $form['waktu-keluar'];
    $waktu_absen = $waktu_masuk .' - '.$waktu_keluar;

    $connection->query("
        UPDATE mata_kuliah
        SET
            name = '$nama_mata_kuliah',
            user_id = '$id_user',
            enroll_code = '$kode_kelas',
            kelas = '$kelas',
            waktu_absen = '$waktu_absen'
        WHERE
            id = '$id'
    ");

    set_flash_message('berhasil_tambah_mata_kuliah', 'Berhasil Melakukan Edit Data Mata Kuliah');

    return redirect('data-mata-kuliah.php?halaman=data-mata-kuliah');
}

function ambil_data_mata_kuliah() {
    global $connection;
    return $connection->query("
    SELECT
        users.id AS id_dosen_pengampu, 
        users.nama AS dosen_pengampu,
        mata_kuliah.name AS matkul,
        mata_kuliah.id AS id_mata_kuliah,
        mata_kuliah.kelas AS kelas_mata_kuliah,
        mata_kuliah.waktu_absen AS waktu_absen,
        mata_kuliah.enroll_code AS enroll_code
    FROM
	    mata_kuliah
	INNER JOIN
	users
	ON 
		mata_kuliah.user_id = users.id")->fetch_all(MYSQLI_ASSOC);
}

function ambil_data_mata_kuliah_dosen($id)
{
    global $connection;
    return $connection->query("
    SELECT
        users.id AS id_dosen_pengampu, 
        users.nama AS dosen_pengampu,
        mata_kuliah.name AS matkul,
	    mata_kuliah.id AS id_mata_kuliah,
        mata_kuliah.kelas AS kelas_mata_kuliah,
        mata_kuliah.waktu_absen AS waktu_absen,
        mata_kuliah.enroll_code AS enroll_code
    FROM
	    mata_kuliah
	INNER JOIN
	    users
	ON 
		mata_kuliah.user_id = users.id
    WHERE mata_kuliah.user_id = '$id'
    ")->fetch_all(MYSQLI_ASSOC);
}

function ambil_data_mata_kuliah_by_id($id)
{
    global $connection;
    return $connection->query("
    SELECT
        *, 
        users.nama AS dosen_pengampu,
	    mata_kuliah.id AS id_mata_kuliah,
	    mata_kuliah.kelas AS kelas_mata_kuliah

    FROM
	    mata_kuliah
	INNER JOIN
	    users
	ON 
		mata_kuliah.user_id = users.id
    WHERE
		mata_kuliah.id = '$id' 
    ")->fetch_assoc();
}

function ambil_logo_auth() {
    global $connection;
    return $connection->query("
    SELECT
        *
    FROM
        setting
    WHERE
        name = 'auth'
    ")->fetch_assoc();
}

function ambil_logo_sidebar() {
    global $connection;
    return $connection->query("
    SELECT
        *
    FROM
        setting
    WHERE
        name = 'sidebar'
    ")->fetch_assoc();
}

function ambil_logo_surat() {
    global $connection;
    return $connection->query("
    SELECT
        *
    FROM
        setting
    WHERE
        name = 'surat'
    ")->fetch_assoc();
}

function ambil_logo_data() {
    global $connection;
    return $connection->query("
    SELECT
        *
    FROM
        setting
    WHERE
        name = 'data'
    ")->fetch_assoc();
}

function ambil_data_mata_kuliah_mahasiswa($id) {
    global $connection;
    return $connection->query("
    SELECT
        *, 
        mahasiswa_enroll.user_id AS id_mahasiswa_enroll, 
        mata_kuliah.user_id AS id_dosen_pengampu,
        mata_kuliah.id AS id_mata_kuliah
    FROM
	    mahasiswa_enroll
	INNER JOIN
	    mata_kuliah
	ON 
		mahasiswa_enroll.mata_kuliah_id = mata_kuliah.id
	INNER JOIN
	users
	ON 
		mata_kuliah.user_id = users.id
    WHERE
	mahasiswa_enroll.user_id = '$id'
    ")->fetch_all(MYSQLI_ASSOC);
}

function random_strings($length_of_string)
{

    // String of all alphanumeric character
    $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

    // Shuffle the $str_result and returns substring
    // of specified length
    return substr(str_shuffle($str_result),
        0, $length_of_string);
}
function ambil_data_absen_by_id($id) {
	global $connection;
 	return $connection->query("SELECT * FROM jadwal_presensi WHERE id = '$id'")->fetch_assoc();
}

function update_data_absen($form){
	global  $connection;
    $id = $form['id'];
    $data_matkul = explode('||', $form['id-mata-kuliah']);
    $nama_mata_kuliah = htmlspecialchars(stripcslashes($form['judul-presensi']));
    $id_mata_kuliah = $data_matkul[0];
    $user_id = $data_matkul[1];
    $waktu_masuk = $form['waktu-masuk'];
    $waktu_keluar = $form['waktu-keluar'];
    $tanggal_absensi = $form['tanggal-absensi'];
    $waktu_dispensasi = $form['waktu-dispensasi'];
    $timestamp_akhir_presensi = $tanggal_absensi .' '. $waktu_keluar;
	$connection->query("
		UPDATE jadwal_presensi
		SET 
			user_id ='$user_id', 
			nama='$nama_mata_kuliah', 
			jam_masuk='$waktu_masuk', 
			jam_keluar='$waktu_keluar', 
			tgl_absen='$tanggal_absensi', 
			waktu_dispensasi='$waktu_dispensasi', 
			mata_kuliah_id='$id_mata_kuliah',
			timestamp_akhir_presensi = '$timestamp_akhir_presensi'
		WHERE
		id = '$id'    
	");

	if ($connection->affected_rows > 0) {
		set_flash_message('berhasil_tambah_absen', 'Berhasil Melakukan Edit Data Absensi');
	} else {
		set_flash_message('gagal_tambah_absen', 'Gagal Melakukan Edit Data Absensi');
	}

	return redirect('data-absen.php?halaman=data-absen');
}

function ambil_jadwal_presensi_mahasiswa($id)
{
    global $connection;
    $date = date('y-m-d');
    return $connection->query("
        SELECT
            *,
            users.nama  as dosen_pengampu,
            jadwal_presensi.nama as judul_presensi,
            jadwal_presensi.id as id_presensi,
            mata_kuliah.`name` as mata_kuliah
        FROM
            jadwal_presensi
        INNER JOIN
            mata_kuliah
        ON 
            jadwal_presensi.mata_kuliah_id = mata_kuliah.id
        INNER JOIN
            mahasiswa_enroll
        ON 
            mata_kuliah.id = mahasiswa_enroll.mata_kuliah_id
        INNER JOIN
            users
        ON 
            mata_kuliah.user_id = users.id
        WHERE
            mahasiswa_enroll.user_id = '$id' AND
            jadwal_presensi.tgl_absen >= '$date'
        ORDER BY
	        tgl_absen DESC
    ")->fetch_all(MYSQLI_ASSOC);
}

function ambil_kehadiran_mahasiswa($id)
{
    global  $connection;
    return $connection->query("
        SELECT
            presensi_mahasiswa.id_jadwal_presensi as id_mata_kuliah
        FROM
            presensi_mahasiswa
        where id_mahasiswa = '$id'
    ")->fetch_all(MYSQLI_ASSOC);
}

function ambil_data_presensi_mahasiswa($id, $id_presensi)
{
    global $connection;
    return $connection->query("
    SELECT
        users.nama AS nama_mahasiswa, 
        users.kelas AS kelas,
        jadwal_presensi.nama AS judul_presensi, 
        jadwal_presensi.jam_masuk AS jam_masuk, 
        jadwal_presensi.jam_keluar AS jam_keluar, 
        jadwal_presensi.tgl_absen AS tgl_absensi, 
        presensi_mahasiswa.jam_presensi AS jam_presensi, 
        presensi_mahasiswa.tgl_presensi AS tgl_presensi, 
        presensi_mahasiswa.waktu_telat AS waktu_telat, 
        presensi_mahasiswa.`status` AS `status`, 
        presensi_mahasiswa.img AS img, 
        presensi_mahasiswa.geo_coordinate AS coordinate, 
        mata_kuliah.`name` AS nama_mata_kuliah
    FROM
	    jadwal_presensi
	INNER JOIN
	    presensi_mahasiswa
	ON 
		jadwal_presensi.id = presensi_mahasiswa.id_jadwal_presensi
	INNER JOIN
	    users
	ON 
		presensi_mahasiswa.id_mahasiswa = users.id
	INNER JOIN
	    mata_kuliah
	ON 
		jadwal_presensi.mata_kuliah_id = mata_kuliah.id
    WHERE
	    jadwal_presensi.id =  '$id_presensi' AND
	    presensi_mahasiswa.id_mahasiswa = '$id'
    ")->fetch_assoc();
}

function ambil_data_seluruh_presensi_mahasiswa($id_presensi)
{
    global $connection;
    return $connection->query("
    SELECT
        users.nama AS nama_mahasiswa, 
        users.kelas AS kelas,
        jadwal_presensi.nama AS judul_presensi, 
        jadwal_presensi.jam_masuk AS jam_masuk, 
        jadwal_presensi.jam_keluar AS jam_keluar, 
        jadwal_presensi.tgl_absen AS tgl_absensi, 
        presensi_mahasiswa.jam_presensi AS jam_presensi, 
        presensi_mahasiswa.tgl_presensi AS tgl_presensi, 
        presensi_mahasiswa.waktu_telat AS waktu_telat, 
        presensi_mahasiswa.`status` AS `status`, 
        presensi_mahasiswa.img AS img,
        presensi_mahasiswa.id_mahasiswa as id_mahasiswa,
        presensi_mahasiswa.id_jadwal_presensi as id_presensi, 
        presensi_mahasiswa.geo_coordinate AS coordinate, 
        mata_kuliah.`name` AS nama_mata_kuliah
    FROM
	    jadwal_presensi
	INNER JOIN
	    presensi_mahasiswa
	ON 
		jadwal_presensi.id = presensi_mahasiswa.id_jadwal_presensi
	INNER JOIN
	    users
	ON 
		presensi_mahasiswa.id_mahasiswa = users.id
	INNER JOIN
	    mata_kuliah
	ON 
		jadwal_presensi.mata_kuliah_id = mata_kuliah.id
    WHERE
	    jadwal_presensi.id =  '$id_presensi'
    ")->fetch_all(MYSQLI_ASSOC);
}

function ambiL_seluruh_data_absensi($id_matkul)
{
    global $connection;
    return $connection->query("
    SELECT
        jadwal_presensi.nama AS nama_presensi, 
        jadwal_presensi.jam_masuk AS jam_masuk, 
        jadwal_presensi.jam_keluar AS jam_keluar, 
        jadwal_presensi.tgl_absen AS tgl_absen, 
        jadwal_presensi.waktu_dispensasi AS waktu_dispensasi, 
        jadwal_presensi.id AS presensi_id, 
        mata_kuliah.name AS nama_matkul,
        mata_kuliah.kelas AS kelas_mata_kuliah,
        mata_kuliah.enroll_code AS kode_kelas,
        mata_kuliah.user_id AS id_dosen
    FROM
	    jadwal_presensi
	INNER JOIN
	    mata_kuliah
	ON 
		jadwal_presensi.mata_kuliah_id = mata_kuliah.id
    WHERE
	    mata_kuliah.id = '$id_matkul' 
	ORDER BY
	    tgl_absen DESC
    ")->fetch_all(MYSQLI_ASSOC);
}

function ambil_akumulasi_mahasiswa($id_mahasiswa) {
    global $connection;
    return $connection->query("
    SELECT
	    SUM(presensi_mahasiswa.waktu_telat) AS akumulasi, 
	    users.nama AS nama, 
	    users.nomor_induk AS npm,
	    users.prodi AS prodi,
	    users.jurusan AS jurusan,
	    users.kelas AS kelas, 
	    users.alamat AS alamat
    FROM
	    presensi_mahasiswa
	INNER JOIN
	    users
	ON 
		presensi_mahasiswa.id_mahasiswa = users.id
    WHERE
	    presensi_mahasiswa.id_mahasiswa = '$id_mahasiswa'
    GROUP BY
	    presensi_mahasiswa.id_mahasiswa
    ")->fetch_assoc();
}

function ambil_akumulasi_sakit_mahasiswa($id_mahasiswa) {
    global $connection;
    return $connection->query("
    SELECT
	    SUM(presensi_mahasiswa.waktu_sakit) AS akumulasi_sakit, 
	    users.nama AS nama, 
	    users.nomor_induk AS npm, 
	    users.alamat AS alamat, 
	    users.kelas AS kelas
    FROM
	    presensi_mahasiswa
	INNER JOIN
	    users
	ON 
		presensi_mahasiswa.id_mahasiswa = users.id
    WHERE
	    presensi_mahasiswa.id_mahasiswa = '$id_mahasiswa'
    GROUP BY
	    presensi_mahasiswa.id_mahasiswa
    ")->fetch_assoc();
}

function ambil_akumulasi_izin_mahasiswa($id_mahasiswa) {
    global $connection;
    return $connection->query("
    SELECT
	    SUM(presensi_mahasiswa.waktu_izin) AS akumulasi_izin, 
	    users.nama AS nama, 
	    users.nomor_induk AS npm, 
	    users.alamat AS alamat, 
	    users.kelas AS kelas
    FROM
	    presensi_mahasiswa
	INNER JOIN
	    users
	ON 
		presensi_mahasiswa.id_mahasiswa = users.id
    WHERE
	    presensi_mahasiswa.id_mahasiswa = '$id_mahasiswa'
    GROUP BY
	    presensi_mahasiswa.id_mahasiswa
    ")->fetch_assoc();
}

function ambil_akumulasi_dispen_mahasiswa($id_mahasiswa) {
    global $connection;
    return $connection->query("
    SELECT
	    SUM(presensi_mahasiswa.waktu_dispen) AS akumulasi_dispen, 
	    users.nama AS nama, 
	    users.nomor_induk AS npm, 
	    users.alamat AS alamat, 
	    users.kelas AS kelas
    FROM
	    presensi_mahasiswa
	INNER JOIN
	    users
	ON 
		presensi_mahasiswa.id_mahasiswa = users.id
    WHERE
	    presensi_mahasiswa.id_mahasiswa = '$id_mahasiswa'
    GROUP BY
	    presensi_mahasiswa.id_mahasiswa
    ")->fetch_assoc();
}

function ambil_list_akumulasi_keterlambatan()
{
    global $connection;
    return $connection->query("
    SELECT
        SUM(waktu_telat) AS akumulasi, 
        users.nama AS nama_mahasiswa, 
        users.nomor_induk AS nim, 
        users.tgl_lahir AS tgl_lahir, 
        users.alamat AS alamat, 
        users.img AS img, 
        users.kelas AS kelas,
        users.id AS id
    FROM
	    presensi_mahasiswa
	INNER JOIN
	    users
	ON 
		presensi_mahasiswa.id_mahasiswa = users.id
    GROUP BY
	    presensi_mahasiswa.id_mahasiswa
    ")->fetch_all(MYSQLI_ASSOC);
}

function upload_foto_profil($file, $name)
{
    $nama_file = $file[$name]['name'];
    $ukuran_file = $file[$name]['size'];
    $error_file = $file[$name]['error'];
    $tmp_name = $file[$name]['tmp_name'];

    // jika upload error
    if ($error_file == 4) {
        set_flash_message('add_failed', 'Error upload foto profil');
        return false;
    }

    // jika ekstensi tidak sesuai
    $ekstensi_valid = ['jpg', 'png', 'jpeg'];
    $ekstensi_gambar = strtolower(end(explode('.', $nama_file)));

    if (!in_array($ekstensi_gambar, $ekstensi_valid)) {
        set_flash_message('add_failed', 'Ekstensi foto tidak valid');
        return false;
    }

    // jika file lebih besar dari 1mb
    if ($ukuran_file > 1000000) {
        set_flash_message('add_failed', 'Ukuran file tidak boleh lebih besar dari 1 MB');
        return false;
    }

    // upload gambar ke folder img/profil
    move_uploaded_file($tmp_name, 'img/profil/'.$nama_file);
    return $nama_file;
}

function upload_foto_logo($file, $name)
{
    $nama_file = $file[$name]['name'];
    $ukuran_file = $file[$name]['size'];
    $error_file = $file[$name]['error'];
    $tmp_name = $file[$name]['tmp_name'];

    // jika upload error
    if ($error_file == 4) {
        set_flash_message('add_failed', 'Error upload foto profil');
        return false;
    }

    // jika ekstensi tidak sesuai
    $ekstensi_valid = ['jpg', 'png', 'jpeg'];
    $ekstensi_gambar = strtolower(end(explode('.', $nama_file)));

    if (!in_array($ekstensi_gambar, $ekstensi_valid)) {
        set_flash_message('add_failed', 'Ekstensi foto tidak valid');
        return false;
    }

    // jika file lebih besar dari 1mb
    if ($ukuran_file > 1000000) {
        set_flash_message('add_failed', 'Ukuran file tidak boleh lebih besar dari 1 MB');
        return false;
    }

    // upload gambar ke folder img/profil
    move_uploaded_file($tmp_name, 'img/'.$nama_file);
    return $nama_file;
}

function resetpw($form) {
    global $connection;

    $username = htmlspecialchars(strtolower(stripcslashes($form['username'])));
    $password = mysqli_escape_string($connection, $form['password']);
    $has_password = password_hash($password, PASSWORD_DEFAULT);

    $connection->query("
        UPDATE users SET
            password = '$has_password'
        WHERE username = '$username'
    ");

    set_flash_message('reset_success','Berhasil reset password');

    redirect('login.php');
}

function ambil_mahasiswa_enroll($id) {
    global $connection;

    $data = $connection->query("
        SELECT
        	users.id AS id_mahasiswa,
            users.nama AS nama, 
            users.nomor_induk AS nim, 
            users.kelas AS kelas, 
            users.prodi AS prodi, 
            users.jurusan AS jurusan
        FROM
            mahasiswa_enroll
            INNER JOIN
            users
            ON 
                mahasiswa_enroll.user_id = users.id
        WHERE
            mahasiswa_enroll.mata_kuliah_id = '$id'
    ")->fetch_all(MYSQLI_ASSOC);

    return $data;
}

function hitung_kompen($id) {
    global $connection;
    $waktu_kompen = 0;

    // ambil data group by date
    $data_kompen = $connection->query("
    SELECT
        SUM( presensi_mahasiswa.waktu_telat ) AS akumulasi_telat,
        presensi_mahasiswa.tgl_presensi,
        users.nama AS nama, 
	    users.nomor_induk AS npm, 
	    users.alamat AS alamat, 
	    users.kelas AS kelas
    FROM
        presensi_mahasiswa
        INNER JOIN users ON presensi_mahasiswa.id_mahasiswa = users.id 
    WHERE
        presensi_mahasiswa.id_mahasiswa = '$id' 
    GROUP BY
        presensi_mahasiswa.tgl_presensi
    ")->fetch_all(MYSQLI_ASSOC);


    // hitung waktu kompen
    foreach ($data_kompen as $kompen) {
	//Kelas Pagi dan Siang
    	if ($kompen['akumulasi_telat'] <= 100) {
            $waktu_kompen+= round(($kompen['akumulasi_telat'] * 4) / 50, 1);
        }

        if ($kompen['akumulasi_telat'] > 100 && $kompen['akumulasi_telat'] < 250) {
            $waktu_kompen += 9;
        }

        if ($kompen['akumulasi_telat'] > 250 && $kompen['akumulasi_telat'] < 300) {
            $waktu_kompen += 9;
        }
        
        if ($kompen['akumulasi_telat'] == 250 || $kompen['akumulasi_telat'] == 300) {
            $waktu_kompen += 10;
        }
    //Kelas Malam
        if ($kompen['akumulasi_telat'] > 300 && $kompen['akumulasi_telat'] < 500) {
            $waktu_kompen += 9;
        }

        if ($kompen['akumulasi_telat'] == 500) {
            $waktu_kompen += 10;
        }

    }
    return $waktu_kompen;
}

function ambil_mahasiswa_belum_absen($id_presensi) {
    global $connection;

    $jadwal_presensi = $connection->query("
    SELECT
        jadwal_presensi.mata_kuliah_id AS id_mata_kuliah, 
        jadwal_presensi.id AS id_presensi
    FROM
        jadwal_presensi
    WHERE
        jadwal_presensi.id = '$id_presensi'
    ")->fetch_assoc();

    $presensi_mahasiswa = $connection->query("
    SELECT
	    presensi_mahasiswa.id_mahasiswa AS id_mahasiswa
    FROM
	    presensi_mahasiswa
    WHERE presensi_mahasiswa.id_jadwal_presensi = '$id_presensi'
    ")->fetch_all();

    $id_matkul = $jadwal_presensi['id_mata_kuliah'];
    $id_mahasiswa_sudah_absen = implode(',',array_column($presensi_mahasiswa,'0'));

    if ($id_mahasiswa_sudah_absen != "" ) {
        # code...
        $mahasiswa_enroll = $connection->query("
        SELECT
            users.nama AS nama, 
            users.kelas AS kelas
        FROM
	        mahasiswa_enroll
	    INNER JOIN
	        users
	    ON 
		    mahasiswa_enroll.user_id = users.id
        WHERE
            mahasiswa_enroll.mata_kuliah_id = '$id_matkul' 
            AND
            mahasiswa_enroll.user_id NOT IN (".$id_mahasiswa_sudah_absen.")
        ")->fetch_all(MYSQLI_ASSOC);
    }else {
        $mahasiswa_enroll = $connection->query("
        SELECT
            users.nama AS nama, 
            users.kelas AS kelas
        FROM
	        mahasiswa_enroll
	    INNER JOIN
	        users
	    ON 
		    mahasiswa_enroll.user_id = users.id
        WHERE
            mahasiswa_enroll.mata_kuliah_id = '$id_matkul' 
        ")->fetch_all(MYSQLI_ASSOC);
    }

    return $mahasiswa_enroll;
}

function ambil_judul_presensi($id_presensi) {
    global $connection;

    return $connection->query("SELECT jadwal_presensi.nama AS judul_presensi FROM jadwal_presensi WHERE jadwal_presensi.id = '$id_presensi'")->fetch_assoc();
}

function ambil_mahasiswa_detail($id) {
    global $connection;

    $detaildata = $connection->query("
        SELECT
        	users.username AS username,
        	users.nomor_induk AS nomor_induk,
            users.nama AS nama, 
            users.kelas AS kelas,
            users.angkatan AS angkatan, 
            users.prodi AS prodi,
            users.jurusan AS jurusan,
            users.alamat AS alamat,
            users.tempat_lahir AS tempat_lahir,
            users.tgl_lahir AS tgl_lahir,
            users.moto_hidup AS moto_hidup,
            users.kemampuan_pribadi AS kemampuan_pribadi,
            users.img AS img
        FROM
            users
        WHERE
            users.id = '$id'
    ")->fetch_all(MYSQLI_ASSOC);

    return $detaildata;
}