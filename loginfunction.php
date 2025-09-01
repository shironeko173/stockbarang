<?php
session_start();

$koneksi = mysqli_connect("mysql.railway.internal","root","AKascEkJYbnhFoejtQkIorFpuEHyjFif","stockbarang");

//login
if(isset($_POST['login'])){
    $email = $_POST['uname'];
    $password = $_POST['psw'];

    $cekuser = mysqli_query($koneksi,"select * from login where email='$email' and password='$password'");
    $hitung = mysqli_num_rows($cekuser);

    if($hitung>0){
        //kalau data ditemukan
        $ambildatarole = mysqli_fetch_array($cekuser);
        $role = $ambildatarole['role'];

        $_SESSION['log'] = 'Logged';
        $_SESSION['email'] = $email;
        $_SESSION['role'] = $role;

        if($role=='admin'){
            //kalau dia admin
            header('location:admin/home.php');
        } else {
            //kalau dia bukan admin
            header('location:user/home.php');
        }

    } else {
        //kalau data tidak ditemukan

        echo '<script>
        alert("Maaf, Email atau Password Anda Salah.");
        window.location.href="index.php";
      </script>';
    }
};

//lupa password
if(isset($_POST['gantipass'])){
    $email = $_POST['uname'];
    $pswbaru = $_POST['pswbaru'];

    //cek apakah emailnya ada di database
    $cekemail = mysqli_query($koneksi,"select * from login where email='$email'");
    $nemu = mysqli_num_rows($cekemail);

    if($nemu>0){

        $queryupdate = mysqli_query($koneksi, "update login set password='$pswbaru' where email='$email'");
        if($queryupdate){
            //if berhasil
            echo '
            <script>
              alert("Selamat, Password anda berhasil diubah.");
              window.location.href="index.php";
            </script>
            ';
        } else {
            //kalau gagal insert ke database
            header('location:lupapassword.php');
        }
    } else {
        //jika ga nemu emailnya
        echo '
            <script>
              alert("Maaf, Email anda tidak ditemukan.");
              window.location.href="lupapassword.php";
            </script>
            ';
    }

}
?>