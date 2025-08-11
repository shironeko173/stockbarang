<?php
session_start();
if (!isset($_SESSION['log']) || $_SESSION['log'] != 'Logged' || !isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    if (isset($_SESSION['previous_url'])) {
        header('Location: ' . $_SESSION['previous_url']);
    } else {
        header('Location: ../index.php');
    }
    exit();
}
require '../function.php';

$email = $_SESSION['email'];

//Dapetin ID barang yang dipassing di halaman sebelumnya " ?id= "
$idbarang = $_GET['id'];//get id barang
//Get informasi barang berdasarkan database
$get = mysqli_query($conn, " select * from stock where idbarang='$idbarang'");
$fetch = mysqli_fetch_assoc($get);
//set variable
$namabarang = $fetch['namabarang'];
$deskripsi = $fetch['deskripsi'];
$stock = $fetch['stock'];

 //cek pakai gambar atau tidak
 $gambar = $fetch['image']; //ambil gambar
 if($gambar==null){
     //jika tidak ada gambar
     $img = 'No Photo';
 } else {
     //jika ada gambar
     $img = '<img src="../images/'.$gambar.'" class="zoomable">';
 }


?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Home-Detail Barang</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.4.1.js"></script>
        <script src="https://kit.fontawesome.com/52631200a0.js" crossorigin="anonymous"></script>
        <style>
            .zoomable{
                width: 100px;
            }
            .zoomable:hover{
                transform: scale(1.5);
                transition: 0.3s ease;
            }
            a{
                text-decoration:none;
                color:black;
            }
        </style>
        
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="home.php" ><i class="fas fa-dolly-flatbed me-2 mb-1"></i><b>STOCK BARANG</b></a>
            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
            
            <!-- Navbar-->
            <ul class="navbar-nav ms-auto me-0 me-md-3 my-2 my-md-0">
                        <i class="fas fa-sign-out-alt me-2" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color:white; font-size: 30px;"></i>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="logout.php" >Logout</a>
                    </ul>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <span class="navbar-brand ps-4 m-2" href="home.php" style=" border-top: 1px solid white; border-bottom: 1px solid white;">
                            <i class="fas fa-user fa-fw me-2 " style="color:#F5F5F5;"></i>
                            <b style="color:#F5F5F5;">Hi Admin !</b></span>
                            <a class="nav-link mt-2"  href="home.php" style="background-color:#0ba7a7;">
                                <div class="sb-nav-link-icon" style="color:white;"><i class="fas fa-dolly-flatbed"></i></div>
                               <b style="color:white;">Stock Barang</b> 
                            </a>
                            <a class="nav-link" href="masuk.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-box"></i></div>
                                Barang Masuk
                            </a>
                            <a class="nav-link" href="keluar.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-dolly"></i></div>
                                Barang Keluar
                            </a>
                            <a class="nav-link" href="peminjaman.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-boxes"></i></div>
                                Peminjaman Barang
                            </a>
                            <hr>
                            <a class="nav-link" href="admin.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                                Kelola Akun
                            </a>
                            <a class="nav-link" href="catatan.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-edit"></i></div>
                                Catatan
                            </a>
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Logged in as:</div>
                       <?=$email;?>
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Detail Barang</h1>
                    
                        <div class="card mb-4 mt-4">
                            <div class="card-header">
                               <h2><?=$namabarang;?></h2>
                                <?=$img;?>
                            </div>
                            <div class="card-body">
                            
                            <div class="row">
                                <div class="col-md-3">Deskripsi</div>
                                <div class="col-md-9">: <?=$deskripsi;?></div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">Stock</div>
                                <div class="col-md-9">: <?=$stock;?></div>
                            </div>

                            <br><br><hr>

                                <h3>Barang Masuk</h3>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="barangmasuk" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal</th>
                                            <th>Keterangan</th>
                                            <th>Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        $ambildatamasuk = mysqli_query($conn, "select * from masuk where idbarang='$idbarang'");
                                        $i = 1;

                                        while($fetch=mysqli_fetch_array($ambildatamasuk)){
                                            $tanggal = $fetch['tanggal'];
                                            $keterangan = $fetch['keterangan'];
                                            $quantity = $fetch['qty'];
                                        
                                        ?>

                                        <tr>
                                            <td><?=$i++;?></td>
                                            <td><?=$tanggal;?></td>
                                            <td><?=$keterangan;?></td>
                                            <td><?=$quantity;?></td>
                                        </tr>

                                        <?php
                                        };
                                        ?>

                                    </tbody>
                                     </table>
                                </div>


                                <br><br>

                                <h3>Barang Keluar</h3>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="barangkeluar" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal</th>
                                            <th>Penerima</th>
                                            <th>Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        $ambildatakeluar = mysqli_query($conn, "select * from keluar where idbarang='$idbarang'");
                                        $i = 1;

                                        while($fetch=mysqli_fetch_array($ambildatakeluar)){
                                            $tanggal = $fetch['tanggal'];
                                            $penerima = $fetch['penerima'];
                                            $quantity = $fetch['qty'];
                                        
                                        ?>

                                        <tr>
                                            <td><?=$i++;?></td>
                                            <td><?=$tanggal;?></td>
                                            <td><?=$penerima;?></td>
                                            <td><?=$quantity;?></td>
                                        </tr>

                                        <?php
                                        };
                                        ?>

                                    </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                </main>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Your Website 2021</div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
         <!-- untuk bootstrap 4 diganti jadi bootstrap 5-->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>
        

        <!-- untuk bootstrap 5 diganti jadi bootstrap 4 -->       
        <!--
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/datatables-demo.js"></script>
        -->
    </body>
  
</html>
