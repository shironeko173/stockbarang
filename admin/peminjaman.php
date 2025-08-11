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

//get data
//ambil data total
$get1 = mysqli_query($conn, "select * from peminjaman");
$count1 = mysqli_num_rows($get1); //menghitung seluruh kolom database

//ambil data peminjaman yang statusnya dipinjam
$get2 = mysqli_query($conn, "select * from peminjaman where status='Dipinjam'");
$count2 = mysqli_num_rows($get2); //menghitung seluruh kolom database yang statusnya dipinjam

//ambil data peminjaman yang statusnya kembali
$get3 = mysqli_query($conn, "select * from peminjaman where status='Kembali'");
$count3 = mysqli_num_rows($get3); //menghitung seluruh kolom database yang statusnya kembali

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Peminjaman Barang</title>
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
            .display-4{
                font-weight: bold;
            }
            .card-body-icon{
                position: absolute;
                z-index: 0;
                top: 30px;
                right: 6px;
                opacity: 0.4;
                font-size: 75px;
            }
            
        </style>
        
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="home.php"><i class="fas fa-dolly-flatbed me-2 mb-1"></i><b>STOCK BARANG</b></a>
            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
            
            <!-- Navbar-->
            <ul class="navbar-nav ms-auto me-0 me-md-3 my-2 my-md-0">
                        <i class="fas fa-sign-out-alt me-2" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color:white;"></i>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="logout.php">Logout</a>
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
                            <a class="nav-link mt-2"  href="home.php" >
                                <div class="sb-nav-link-icon" ><i class="fas fa-dolly-flatbed"></i></div>
                               Stock Barang</b> 
                            </a>
                            <a class="nav-link" href="masuk.php" >
                                <div class="sb-nav-link-icon" ><i class="fas fa-box"></i></div>
                                Barang Masuk
                            </a>
                            <a class="nav-link" href="keluar.php" >
                                <div class="sb-nav-link-icon" ><i class="fas fa-dolly"></i></div>
                                Barang Keluar
                            </a>
                            <a class="nav-link" href="peminjaman.php" style="background-color:#0ba7a7;">
                                <div class="sb-nav-link-icon" style="color:white;"><i class="fas fa-boxes"></i></div>
                                <b style="color:white;"> Peminjaman Barang</b>
                            </a>
                            <hr>
                            <a class="nav-link" href="Admin.php">
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
                        <h1 class="mt-4">Peminjaman Barang</h1>
                    
                        <div class="card mb-4">
                            <div class="card-header">
                                 <!-- Button to Open the Modal -->
                                 <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal">
                                    Add Data
                                 </button>
                                 <br>
                                 <!--Card jumlah data peminjaman -->
                                 <div class="row text-white mt-4 ">
                                 <div class="card bg-info ms-5" style="width: 18rem;">
                                    <div class="card-body">
                                        <div class="card-body-icon">
                                        <i class="fas fa-cubes"></i>
                                        </div>
                                        <h5 class="card-title">TOTAL DATA</h5>
                                        <div class="display-4"><?=$count1;?></div>
                                    </div>
                                    </div>

                                    <div class="card bg-danger ms-5" style="width: 18rem;">
                                    <div class="card-body ">
                                        <div class="card-body-icon">
                                        <i class="fas fa-box-open"></i>
                                        </div>
                                        <h5 class="card-title">TOTAL DIPINJAM</h5>
                                        <div class="display-4"><?=$count2;?></div>
                                    </div>
                                    </div>

                                    <div class="card bg-success ms-5" style="width: 18rem;">
                                    <div class="card-body">
                                        <div class="card-body-icon">
                                        <i class="fas fa-box"></i>
                                        </div>
                                        <h5 class="card-title">TOTAL KEMBALI</h5>
                                        <div class="display-4"><?=$count3;?></div>
                                    </div>
                                    </div>
                                 </div>

                                 <div class="row mt-5">
                                     <div class="col">
                                        <form method="post" class="form-inline">
                                            <input type="date" name="tgl_mulai" >
                                            <input type="date" name="tgl_selesai" >
                                            <button type="submit" name="filter_tgl" class="btn btn-secondary btn-sm mb-1 ms-1" >Filter</button>
                                        </form>
                                     </div>
                                 </div>
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead style="background-color:#37bbcd;">
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Gambar</th>
                                            <th>Nama Barang</th>
                                            <th>Jumlah</th>
                                            <th>Kepada</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    <?php
                                        
                                        if(isset($_POST['filter_tgl'])){
                                            $mulai = $_POST['tgl_mulai'];
                                            $selesai = $_POST['tgl_selesai'];
                                            if($mulai!=null || $selesai!=null){
                                                $ambilsemuadatastock = mysqli_query($conn,"select * from peminjaman p, stock s where s.idbarang = p.idbarang and tanggalpinjam BETWEEN '$mulai' and DATE_ADD('$selesai', INTERVAL 1 DAY) order by idpeminjaman");
                                            } else {
                                                $ambilsemuadatastock = mysqli_query($conn,"select * from peminjaman p, stock s where s.idbarang = p.idbarang order by idpeminjaman");
                                            }
                                            
                                        } else {
                                            $ambilsemuadatastock = mysqli_query($conn,"select * from peminjaman p, stock s where s.idbarang = p.idbarang order by idpeminjaman");//untuk joint table ke table stock
                                        }
                                        
                                        while($data=mysqli_fetch_array($ambilsemuadatastock)){
                                            $idk = $data['idpeminjaman'];
                                            $idb = $data['idbarang'];
                                            $tanggal = $data['tanggalpinjam'];
                                            $namabarang = $data['namabarang'];
                                            $qty  = $data['qty'];
                                            $penerima = $data['peminjam'];
                                            $status = $data['status'];

                                            //cek pakai gambar atau tidak
                                            $gambar = $data['image']; //ambil gambar
                                            if($gambar==null){
                                                //jika tidak ada gambar
                                                $img = 'No Photo';
                                            } else {
                                                //jika ada gambar
                                                $img = '<img src="../images/'.$gambar.'" class="zoomable">';
                                            }
                                        
                                        ?>

                                        <tr>
                                            <td><?=$tanggal;?></td>
                                            <td><?=$img;?></td>
                                            <td><?=$namabarang;?></td>
                                            <td><?=$qty;?></td>
                                            <td><?=$penerima;?></td>
                                            <td><?=$status;?></td>
                                            <td> 

                                            <?php
                                                //cek status           
                                                if($status=='Dipinjam'){
                                                    echo ' <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#edit'.$idk.'">
                                                           Selesai
                                                           </button>';
                                                } else {
                                                    //jika status sudah kembali
                                                    echo 'Done!';
                                                }
                                               
                                            ?>
                                            </td>
                                        </tr>

                                         <!-- Edit Modal -->
                                         <div class="modal fade" id="edit<?=$idk;?>">
                                                <div class="modal-dialog">
                                                <div class="modal-content">

                                                <!-- Modal Header -->
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Selesaikan</h4>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>

                                                <!-- Modal body -->
                                                <form method="post">
                                                <div class="modal-body">
                                                    Apakah barang ini sudah selesai dipinjam?
                                                    <br><br>
                                                <input type="hidden" name="idpinjam" value="<?=$idk;?>">
                                                <input type="hidden" name="idbarang" value="<?=$idb;?>">
                                                <button type="submit" class="btn btn-primary" name="barangkembali">Sudah, Makasih ya hehe</button>
                                                </div> 
                                                </form>

                                                </div>
                                                </div>
                                            </div>
                                        </div>

                                        <?php
                                        };

                                    ?>

                                    </tbody>
                                </table>
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

     <!-- The Modal -->
<div class="modal fade" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Add Data Peminjaman</h4>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <!-- Modal body -->
      <form method="post">
      <div class="modal-body">
      <Select name="barangnya" class="form-control">
          <?php
            $ambilsemuadatanya = mysqli_query($conn,"select * from stock order by namabarang ASC");//ascending (berurut)
            while($fetcharray = mysqli_fetch_array($ambilsemuadatanya)){
                $namabarangnya = $fetcharray['namabarang'];
                $idbarangnya = $fetcharray['idbarang'];
          ?>

          <option value="<?=$idbarangnya;?>"><?=$namabarangnya;?></option>      

          <?php
            }
          ?>
      </select>
      <br>
      <input type="number" name="qty" placeholder="Quantity" class="form-control" min="1" required>
      <br>
      <input type="text" name="penerima" placeholder="Kepada" class="form-control" required>
      <br>
      <button type="submit" class="btn btn-primary" name="pinjam">Submit</button>
      </div> 
      </form>

    </div>
  </div>
</div>

</html>
