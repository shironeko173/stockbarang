<?php

$_SESSION['previous_url'] = $_SERVER['REQUEST_URI'];
//Membuat koneksi ke database
$conn = mysqli_connect("mysql.railway.internal","root","UhMzqiSqKTqYJuSJxNMOuHvRqcasUVpb","stockbarang");

    if (isset($_GET['msg'])) {
        if ($_GET['msg'] == 'berhasil') {
            echo "<p style='color: green;'>Data berhasil ditambahkan!</p>";
        } elseif ($_GET['msg'] == 'gagal') {
            echo "<p style='color: red;'>Data gagal ditambahkan!</p>";
        }
    }

// storage Railway
$folder = '/mnt/stockbarang_images';
if (file_exists($folder)) {
    $testFile = $folder . '/test.txt';
    $content = "Tes tulis file pada " . date('Y-m-d H:i:s');

    // Coba tulis file
    if (file_put_contents($testFile, $content) !== false) {
        echo "✅ Berhasil membuat file test.txt di folder stockbarang_images";
    } else {
        echo "❌ Gagal membuat file di folder stockbarang_images (permission error)";
    }
} else {
    echo "❌ Folder tidak ditemukan";
}


//-------------------------------------------------------------------BAGIAN STOCK-HOME----------------------------------------------------------------//

//Menambah barang baru 
if(isset($_POST['addnewbarang'])){
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];
    $stock = $_POST['stock'];

    //Gambar
    $allowed_extension = array('png','jpg','jpeg');
    $nama =$_FILES['file']['name'];//ngambil nama file gambar
    $dot = explode('.',$nama);
    $ekstensi = strtolower(end($dot));//ngambil ekstensinya
    $ukuran = $_FILES['file']['size']; // ngambil size filenya
    $file_tmp = $_FILES['file']['tmp_name']; //ngambil lokasi filenya

    //penamaan file -> enkripsi (mencegah nama yang sama)
    $image = md5(uniqid($nama,true) . time()).'.'.$ekstensi; //menggabungkan nama file yang dienkripsi dengan ekstensinya

    //validasi udah ada atau belum
    $cek = mysqli_query($conn,"select * from stock where namabarang='$namabarang'");
    $hitung = mysqli_num_rows($cek);

    if($hitung<1){
        //jika belum ada
        if($ukuran==0){
            //jika tidak ingin upload gambar
            $addtotable = mysqli_query($conn,"insert into stock (namabarang, deskripsi, stock) values('$namabarang','$deskripsi','$stock')");
            if($addtotable){
                header('Location: home.php?msg=berhasil');
                exit;
            } else {
                header('location:home.php?msg=gagal');
                exit;
            }
        } else if(in_array($ekstensi, $allowed_extension) === true){// jika ingin upload gambar, proses upload gambar
            //validasi ukuran file
            if($ukuran < 100000000){ //~ 10mb


                // Gunakan path relatif
                // move_uploaded_file($file_tmp, '/images/'.$image);
                move_uploaded_file($file_tmp, '/app/stockbarang_images/'.$image); //Railway Path
                
                $addtotable = mysqli_query($conn,"insert into stock (namabarang, deskripsi, stock, image) values('$namabarang','$deskripsi','$stock','$image')");
                if($addtotable){
                    header('location:home.php');
                    exit;
                } else {
                    header('location:home.php?msg=gagal');
                    exit;
                }
            } else {
                //kalau filenya lebih dari 10mb
                echo '
            <script>
              alert("Maaf, Ukuran terlalu besar.");
              window.location.href="home.php";
            </script>
            ';
            }

        } else {
            //kalau filenya bukan png/jpg
            echo '
            <script>
              alert("Maaf, File harus png/jpg/jpeg.");
              window.location.href="home.php";
            </script>
            ';
        }

  } else {
      //jika sudah ada nama barangnya
      echo '
      <script>
        alert("Maaf, Nama barang sudah terdaftar. Silahkan Add barang pada menu Barang Masuk.");
        window.location.href="home.php";
      </script>
      ';
  }
};



//Update info barang
if(isset($_POST['updatebarang'])){
    $idb = $_POST['idb'];
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];
    
    //Gambar
    $allowed_extension = array('png','jpg','jpeg');
    $nama =$_FILES['file']['name'];//ngambil nama file gambar
    $dot = explode('.',$nama);
    $ekstensi = strtolower(end($dot));//ngambil ekstensinya
    $ukuran = $_FILES['file']['size']; // ngambil size filenya
    $file_tmp = $_FILES['file']['tmp_name']; //ngambil lokasi filenya

    //penamaan file -> enkripsi (mencegah nama yang sama)
    $image = md5(uniqid($nama,true) . time()).'.'.$ekstensi; //menggabungkan nama file yang dienkripsi dengan ekstensinya

    if($ukuran==0){
        //jika tidak ingin update gambar
        $update = mysqli_query($conn,"update stock set namabarang='$namabarang', deskripsi='$deskripsi' where idbarang = '$idb'");
    if($update){
            header('location:home.php');
            exit;
        } else {
            header('location:home.php?msg=gagal');
            exit;
        }
    } else if(in_array($ekstensi, $allowed_extension) === true){// jika ingin update/upload gambar, proses upload gambar
        //validasi ukuran file
        if($ukuran < 100000000){ //~ 10mb
            
            // move_uploaded_file($file_tmp, '/images/'.$image);
            move_uploaded_file($file_tmp, '/app/stockbarang_images/'.$image); // Path Railway
            
            $update = mysqli_query($conn,"update stock set namabarang='$namabarang', deskripsi='$deskripsi', image='$image' where idbarang = '$idb'");
    if($update){
                header('location:home.php');
                exit;
            } else {
                header('location:home.php?msg=gagal');
                exit;
            }
        } else {
            //kalau filenya lebih dari 10mb
            echo '
        <script>
          alert("Maaf, Ukuran terlalu besar.");
          window.location.href="home.php";
        </script>
        ';
        }

    } else {
        //kalau filenya bukan png/jpg
        echo '
        <script>
          alert("Maaf, File harus png/jpg/jpeg.");
          window.location.href="home.php";
        </script>
        ';
    }
};



//Menghapus barang dari stock
if(isset($_POST['hapusbarang'])){
    $idb = $_POST['idb'];

    $gambar = mysqli_query($conn, "select * from stock where idbarang='$idb'");
    $get = mysqli_fetch_array($gambar);
    // $img = 'images/'.$get['image'];
    $img = '/app/stockbarang_images/'.$get['image']; //Railway path
    unlink($img);

    $hapus = mysqli_query($conn, "delete from stock where idbarang='$idb'");
    if($hapus){
        header('location:home.php');
        exit;
    } else {
        header('location:home.php?msg=gagal'); 
        exit;
    }
    
};


//--------------------------------------------------------------------BAGIAN BARANG MASUK-------------------------------------------------------------------//

//Menambah barang masuk
if(isset($_POST['barangmasuk'])){
    $barangnya = $_POST['barangnya'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $cekstocksekarang = mysqli_query($conn, "select * from stock where idbarang='$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);

    $stocksekarang = $ambildatanya['stock'];
    $tambahkanstocksekarangdenganquantity = $stocksekarang+$qty;

    $addtomasuk = mysqli_query($conn,"insert into masuk (idbarang, keterangan, qty) values('$barangnya','$penerima','$qty')");
    $updatestockmasuk = mysqli_query($conn, "update stock set stock='$tambahkanstocksekarangdenganquantity' where idbarang='$barangnya'"); 
    if($addtomasuk&&$updatestockmasuk){
        header('location:masuk.php');
        exit;
    } else {
        header('location:masuk.php?msg=gagal');
        exit;
    }
};

//Mengubah data barang masuk
if(isset($_POST['updatebarangmasuk'])){
    $idb = $_POST['idb'];
    $idm = $_POST['idm'];
    $deskripsi = $_POST['keterangan'];
    $qty = $_POST['qty'];

    $lihatstock = mysqli_query($conn, "select  * from stock where idbarang='$idb'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $stockskrg = $stocknya['stock'];

    $qtyskrg = mysqli_query($conn, "select * from masuk where idmasuk='$idm'");
    $qtynya = mysqli_fetch_array($qtyskrg);
    $qtyskrg = $qtynya['qty'];//qty yang diinput

    if($qty>$qtyskrg){
        $selisih = $qty-$qtyskrg;
        $tambahin = $stockskrg + $selisih;
        $tambahistocknya = mysqli_query($conn, "update stock set stock='$tambahin' where idbarang='$idb'");
        $updatenya = mysqli_query($conn, "update masuk set qty='$qty', keterangan='$deskripsi' where idmasuk='$idm'");

        if($tambahistocknya&&$updatenya){
            header('location:masuk.php');
            exit;
        } else {
            header('location:masuk.php?msg=gagal');
            exit;
        }
    } else {
        $selisih = $qtyskrg-$qty;
        $kurangin = $stockskrg - $selisih;
        $kurangistocknya = mysqli_query($conn, "update stock set stock='$kurangin' where idbarang='$idb'");
        $updatenya = mysqli_query($conn, "update masuk set qty='$qty', keterangan='$deskripsi' where idmasuk='$idm'");

        if($kurangistocknya&&$updatenya){
            header('location:masuk.php');
            exit;
        } else {
            header('location:masuk.php?msg=gagal');
            exit;
        }
    }
};

//Menghapus barang masuk
if(isset($_POST['hapusbarangmasuk'])){
    $idb = $_POST['idb'];
    $qty = $_POST['kty'];
    $idm = $_POST['idm'];

    $getdatastock = mysqli_query($conn,"select * from stock where idbarang='$idb'");
    $data = mysqli_fetch_array($getdatastock);
    $stock = $data['stock'];

    $selisih = $stock-$qty;
    
    $update = mysqli_query($conn, "update stock set stock='$selisih' where idbarang='$idb'");
    $hapusdata= mysqli_query($conn, "delete from masuk where idmasuk='$idm'"); 

    if($update&&$hapusdata){
        header('location:masuk.php');
        exit;
    } else{
        header('location:masuk.php?msg=gagal');
        exit;
    }

}


//--------------------------------------------------------------------BAGIAN BARANG KELUAR--------------------------------------------------------------------//

//Menambah barang keluar
if(isset($_POST['addbarangkeluar'])){
    $barangnya = $_POST['barangnya'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $cekstocksekarang = mysqli_query($conn, "select * from stock where idbarang='$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);

    $stocksekarang = $ambildatanya['stock'];

    if($stocksekarang >= $qty){
        //kalau barangnya cukup
        $tambahkanstocksekarangdenganquantity = $stocksekarang-$qty;

        $addtokeluar = mysqli_query($conn,"insert into keluar (idbarang, penerima, qty) values('$barangnya','$penerima','$qty')");
        $updatestockmasuk = mysqli_query($conn, "update stock set stock='$tambahkanstocksekarangdenganquantity' where idbarang='$barangnya'"); 
        if($addtokeluar&&$updatestockmasuk){
            header('location:keluar.php');
            exit;
    } else {
        header('location:keluar.php?msg=gagal');
        exit;
        }
    } else {
        //Kalau barangnya tidak cukup
        echo '
        <script>
            alert("stock saat ini tidak mencukupi");
            window.location.href="home.php";
        </script>
        ';        
    }

};


//Mengubah data barang keluar
if(isset($_POST['updatebarangkeluar'])){
    $idb = $_POST['idb'];
    $idk = $_POST['idk'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $lihatstock = mysqli_query($conn, "select * from stock where idbarang='$idb'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $stockskrg = $stocknya['stock'];

    $qtyskrg = mysqli_query($conn, "select * from keluar where idkeluar='$idk'");
    $qtynya = mysqli_fetch_array($qtyskrg);
    $qtyskrg = $qtynya['qty'];
    
    $stocktotal = $stockskrg + $qtyskrg;

    if($stocktotal >= $qty){ //apabila barang cukup untuk dikeluarkan maka akan dijalankan, Inputan user
        if($qty>$qtyskrg){
                $selisih = $qty-$qtyskrg;
                $kurangin = $stockskrg - $selisih;
                $kurangistocknya = mysqli_query($conn, "update stock set stock='$kurangin' where idbarang='$idb'");
                $updatenya = mysqli_query($conn, "update keluar set qty='$qty', penerima='$penerima' where idkeluar='$idk'");

                if($kurangistocknya&&$updatenya){
                    header('location:keluar.php');
                    exit;
                } else {
                    header('location:keluar.php?msg=gagal');
                    exit;
                }
        } else {
            $selisih = $qtyskrg-$qty;
            $tambahin = $stockskrg + $selisih;
            $tambahistocknya = mysqli_query($conn, "update stock set stock='$tambahin' where idbarang='$idb'");
            $updatenya = mysqli_query($conn, "update keluar set qty='$qty', penerima='$penerima' where idkeluar='$idk'");

            if($tambahistocknya&&$updatenya){
                header('location:keluar.php');
                exit;
            } else {
                header('location:keluar.php?msg=gagal');
                exit;
            }
        }
    } else {
        //Kalau barangnya tidak cukup
        echo '
        <script>
            alert("stock saat ini tidak mencukupi");
            window.location.href="home.php";
        </script>
        ';  
    }
};



//Menghapus barang Keluar
if(isset($_POST['hapusbarangkeluar'])){
    $idb = $_POST['idb'];
    $qty = $_POST['kty'];
    $idk = $_POST['idk'];

    $getdatastock = mysqli_query($conn,"select * from stock where idbarang='$idb'");
    $data = mysqli_fetch_array($getdatastock);
    $stock = $data['stock']; //sudah diganti

    $selisih = $stock+$qty;
    
    $update = mysqli_query($conn, "update stock set stock='$selisih' where idbarang='$idb'");
    $hapusdata= mysqli_query($conn, "delete from keluar where idkeluar='$idk'"); 

    if($update&&$hapusdata){
        header('location:keluar.php');
        exit;
    } else{
        header('location:keluar.php?msg=gagal');
        exit;
    }

}




//--------------------------------------------------------------------BAGIAN KELOLA ADMIN--------------------------------------------------------------------//

//Menambah admin baru
if(isset($_POST['addadmin'])){
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $queryinsert = mysqli_query($conn, " insert into login (email, password, role) values ('$email','$password','$role')");

    if($queryinsert){
        //if berhasil
        header('location:admin.php');
        exit;
    } else {
        //kalau gagal insert ke database
        header('location:admin.php');
        exit;
    }

};


//Edit data admin
if(isset($_POST['updateadmin'])){
    $emailbaru = $_POST['emailadmin'];
    $passwordbaru = $_POST['passwordbaru'];
    $idnya = $_POST['id'];

    $queryupdate = mysqli_query($conn, "update login set email='$emailbaru', password='$passwordbaru' where iduser='$idnya'");

    if($queryupdate){
        //if berhasil
        header('location:admin.php');
        exit;
    } else {
        //kalau gagal insert ke database
        header('location:admin.php');
        exit;
    }

};


//Hapus admin
if(isset($_POST['hapusadmin'])){
    $id = $_POST['id'];

    $querydelete = mysqli_query($conn, "delete from login where iduser='$id'");

    if($querydelete){
        //if berhasil
        header('location:admin.php');
        exit;
    } else {
        //kalau gagal insert ke database
        header('location:admin.php');
        exit;
    }
};


//----------------------------------------------------------------------BAGIAN PEMINJAMAN BARANG------------------------------------------------------------------//

//Meminjam Barang
if(isset($_POST['pinjam'])){
    $idbarang = $_POST['barangnya'];      //Nama Variable & nama Value->dari form name untuk mengambil idbarang
    $qty = $_POST['qty']; //mengambil jumlah quantity
    $penerima = $_POST['penerima']; // mengambil nama penerima

    //ambil stock sekarang
    $cekstok_saat_ini = mysqli_query($conn,"select * from stock where idbarang='$idbarang'");
    $stok_nya = mysqli_fetch_array($cekstok_saat_ini);
    $stok = $stok_nya['stock']; //ini valuenya stock sekarang

    if($stok >= $qty){ //kalau barangnya cukup

    //kurangin stocknya
    $new_stock = $stok-$qty;

    //mulai query insert
    $insertpinjam = mysqli_query($conn, "INSERT INTO peminjaman (idbarang, qty, peminjam) values('$idbarang', '$qty', '$penerima')");


    //mengurangi stock di table stock
    $kurangistok = mysqli_query($conn,"update stock set stock='$new_stock' where idbarang='$idbarang'");

    if($insertpinjam&&$kurangistok){
        //jika berhasil
        echo '
        <script>
            alert("Berhasil");
            window.location.href="peminjaman.php";
        </script>
        ';   
    } else {
        //jika gagal
        echo '
        <script>
            alert("Gagal");
            window.location.href="peminjaman.php";
        </script>
        ';   
         }
    } else {
        //Kalau barangnya tidak cukup
        echo '
        <script>
            alert("Jangan pinjam terlalu banyak ya.. Stocknya tidak cukup :D");
            window.location.href="home.php";
        </script>
        ';        
    }

}


//menyelesaikan pinjaman
if(isset($_POST['barangkembali'])){
    $idpinjam = $_POST['idpinjam'];
    $idbarang = $_POST['idbarang'];

    //eksekusi query
    $update_status = mysqli_query($conn,"update peminjaman set status='Kembali' where idpeminjaman='$idpinjam'");

    //ambil stock sekarang
    $cekstok_saat_ini = mysqli_query($conn,"select * from stock where idbarang='$idbarang'");
    $stok_nya = mysqli_fetch_array($cekstok_saat_ini);
    $stok = $stok_nya['stock']; //ini valuenya

     //ambil qty dari id peminjaman sekarang
     $qty_saat_ini = mysqli_query($conn,"select * from peminjaman where idpeminjaman='$idpinjam'");
     $qty_nya = mysqli_fetch_array($qty_saat_ini);
     $qty1 = $qty_nya['qty']; //ini valuenya

    //Tambahin stocknya
    $new_stock = $qty1+$stok;

    //Mengembalikan stocknya
    $kembalikan_stock = mysqli_query($conn,"update stock set stock='$new_stock' where idbarang='$idbarang'");

    if($update_status&&$kembalikan_stock){
        //jika berhasil
        echo '
        <script>
            alert("Berhasil");
            window.location.href="peminjaman.php";
        </script>
        ';   
    } else {
        //jika gagal
        echo '
        <script>
            alert("Gagal");
            window.location.href="peminjaman.php";
        </script>
        ';   
    }
}

//--------------------------------------------------------------------BAGIAN CATATAN-------------------------------------------------------------------//

//Menambah Catatan
if(isset($_POST['addnewpesan'])){
    $namapengirim = $_POST['namapengirim'];
    $pesan = $_POST['editor1'];

    
    $insertcatatan = mysqli_query($conn, " insert into catatan (namapengirim, isi) values ('$namapengirim','$pesan')");

    if($insertcatatan){
        //if berhasil
        header('location:catatan.php');
        exit;
    } else {
        //kalau gagal insert ke database
        header('location:catatan.php');
        exit;
    }

};

//-------------------------------------------------------------BAGIAN FUNGSI TAMBAHAN-----------------------------------------------------------//
// Pastikan parameter file dikirim
// if (!isset($_GET['file']) || empty($_GET['file'])) {
//     http_response_code(400);
//     echo "Parameter 'file' tidak ditemukan.";
//     exit;
// }

if (isset($_GET['img'])) {
    $filename = basename($_GET['img']);
    $file = '/app/stockbarang_images/' . $filename;

    if (file_exists($file) && is_file($file)) {
        $mime = mime_content_type($file);
        header('Content-Type: ' . $mime);
        readfile($file);
        exit;
    } else {
        http_response_code(404);
        echo "File tidak ditemukan.";
    }
}




?>



