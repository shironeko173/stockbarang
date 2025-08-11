<?php
session_start();
if (!isset($_SESSION['log']) || $_SESSION['log'] != 'Logged' || !isset($_SESSION['role']) || $_SESSION['role'] != 'user') {
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
        <title>Catatan</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
        <script src="ckeditor/ckeditor.js"></script>
        <script src="https://code.jquery.com/jquery-3.4.1.js"></script>
        <script src="https://kit.fontawesome.com/52631200a0.js" crossorigin="anonymous"></script>
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
                            <b style="color:#F5F5F5;">Hi USER !</b></span>
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
                            <a class="nav-link" href="peminjaman.php">
                                <div class="sb-nav-link-icon" ><i class="fas fa-boxes"></i></div>
                                Peminjaman Barang
                            </a>
                            <hr>
                            <a class="nav-link" href="catatan.php" style="background-color:#0ba7a7;">
                                <div class="sb-nav-link-icon" style="color:white;"><i class="fas fa-edit"></i></div>
                                <b style="color:white;"> Catatan</b>
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
                        <h1 class="mt-4">Catatan</h1>
                    
                        <div class="card mb-4">
                        <div class="card-header">
                                 <!-- Button to Open the Modal -->
                                 <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal">
                                    Add Pesan
                                 </button>
                            </div>
                           
                            <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead style="background-color:#37bbcd;">
                                        <tr>
                                            <th class="col-2">Tanggal</th>
                                            <th class="col-2">Nama Pengirim</th>
                                            <th class="col-6">Isi Pesan</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    <?php
                                  
                                        $ambilsemuadatacatatan = mysqli_query($conn,"select * from catatan c");//untuk joint table ke table catatan
                                     
                                        while($data=mysqli_fetch_array($ambilsemuadatacatatan)){
                                            $tanggal = $data['tanggalpesan'];
                                            $namapengirim = $data['namapengirim'];
                                            $isipesan = $data['isi'];
                                          
                                        
                                        ?>

                                        <tr>
                                            <td><?=$tanggal;?></td>
                                            <td><?=$namapengirim;?></td>
                                            <td><?=$isipesan;?></td>
                                        </tr>
                                        
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
        <h4 class="modal-title">Add Pesan</h4>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <!-- Modal body -->
      <div class="container">
        <form method="post" class="m-4" >
                <input type="text" name="namapengirim" placeholder="Nama Pengirim" class="form-control" required>
                    <br>
            <label for="comment">Comments:</label>
            <textarea name="editor1"></textarea>
                <script>
                        CKEDITOR.replace( 'editor1' );
                </script>
                    <br>
            <button type="submit" class="btn btn-primary" name="addnewpesan">Submit</button>
        </form>
      </div>

    </div>
  </div>
</div>

</html>
