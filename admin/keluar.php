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
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Barang Keluar</title>
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
                            <a class="nav-link" href="keluar.php" style="background-color:#0ba7a7;">
                                <div class="sb-nav-link-icon" style="color:white;"><i class="fas fa-dolly"></i></div>
                                <b style="color:white;">Barang Keluar</b>
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
                        <h1 class="mt-4">Barang Keluar</h1>
                    
                        <div class="card mb-4">
                            <div class="card-header">
                                 <!-- Button to Open the Modal -->
                                 <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal">
                                    Add Barang
                                 </button>
                                 <br>
                                 <div class="row mt-4">
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
                                            <th>Penerima</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    <?php
                                        
                                        if(isset($_POST['filter_tgl'])){
                                            $mulai = $_POST['tgl_mulai'];
                                            $selesai = $_POST['tgl_selesai'];
                                            if($mulai!=null || $selesai!=null){
                                                $ambilsemuadatastock = mysqli_query($conn,"select * from keluar k, stock s where s.idbarang = k.idbarang and tanggal BETWEEN '$mulai' and DATE_ADD('$selesai', INTERVAL 1 DAY)");
                                            } else {
                                                $ambilsemuadatastock = mysqli_query($conn,"select * from keluar k, stock s where s.idbarang = k.idbarang");
                                            }
                                            
                                        } else {
                                            $ambilsemuadatastock = mysqli_query($conn,"select * from keluar k, stock s where s.idbarang = k.idbarang");//untuk joint table ke table stock
                                        }
                                        
                                        while($data=mysqli_fetch_array($ambilsemuadatastock)){
                                            $idk = $data['idkeluar'];
                                            $idb = $data['idbarang'];
                                            $tanggal = $data['tanggal'];
                                            $namabarang = $data['namabarang'];
                                            $qty  = $data['qty'];
                                            $penerima = $data['penerima'];

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
                                            <td> 
                                                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#edit<?=$idk;?>">
                                                    Edit
                                                </button> 
                                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete<?=$idk;?>">
                                                    Delete
                                                </button>
                                            </td>
                                        </tr>

                                         <!-- Edit Modal -->
                                         <div class="modal fade" id="edit<?=$idk;?>">
                                                <div class="modal-dialog">
                                                <div class="modal-content">

                                                <!-- Modal Header -->
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Edit Barang</h4>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>

                                                <!-- Modal body -->
                                                <form method="post">
                                                <div class="modal-body">
                                                <input type="text" name="penerima" value="<?=$penerima;?>" class="form-control" required>
                                                <br>
                                                <input type="number" name="qty" value="<?=$qty;?>" class="form-control" required>
                                                <br>
                                                <input type="hidden" name="idb" value="<?=$idb;?>">
                                                <input type="hidden" name="idk" value="<?=$idk;?>">
                                                <button type="submit" class="btn btn-primary" name="updatebarangkeluar">Submit</button>
                                                </div> 
                                                </form>

                                                </div>
                                                </div>
                                            </div>
                                        </div>

                                            <!-- Delete Modal -->
                                            <div class="modal fade" id="delete<?=$idk;?>">
                                                <div class="modal-dialog">
                                                <div class="modal-content">

                                                <!-- Modal Header -->
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Hapus Barang?</h4>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>

                                                <!-- Modal body -->
                                                <form method="post">
                                                <div class="modal-body">
                                                Apakah Anda yakin ingin menghapus <?=$namabarang;?>?
                                                <input type="hidden" name="idb" value="<?=$idb;?>">
                                                <input type="hidden" name="kty" value="<?=$qty;?>">
                                                <input type="hidden" name="idk" value="<?=$idk;?>">
                                                <br>
                                                <br>
                                                <button type="submit" class="btn btn-danger" name="hapusbarangkeluar">Hapus</button>
                                                </div> 
                                                </form>

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
        <h4 class="modal-title">Add Barang Keluar</h4>
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
      <button type="submit" class="btn btn-primary" name="addbarangkeluar">Submit</button>
      </div> 
      </form>

    </div>
  </div>
</div>

</html>
