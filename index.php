<?php
include_once("koneksi.php");

// Menyimpan atau memperbarui data
if (isset($_POST['simpan'])) {
    $isi = $conn->real_escape_string($_POST['isi']);
    $tgl_awal = $conn->real_escape_string($_POST['tgl_awal']);
    $tgl_akhir = $conn->real_escape_string($_POST['tgl_akhir']);
    
    if (isset($_POST['id'])) {
        $id = intval($_POST['id']);
        $ubah = $conn->query(query: "UPDATE kegiatan SET
                               isi = '$isi',
                               tanggal_awal = '$tgl_awal',
                               tanggal_akhir = '$tgl_akhir'
                               WHERE id = '$id'");
    } else {
        $tambah = $conn->query("INSERT INTO kegiatan (isi, tgl_awal, tgl_akhir, status)
                                 VALUES ('$isi', '$tgl_awal', '$tgl_akhir', '0')");
    }

    echo "<script>
            document.location='index.php';
          </script>";
}

// Menghapus atau mengubah status
if (isset($_GET['aksi'])) {
    $id = intval($_GET['id']);
    
    if ($_GET['aksi'] == 'hapus') {
        $hapus = $conn->query("DELETE FROM kegiatan WHERE id = '$id'");
    } elseif ($_GET['aksi'] == 'ubah_status') {
        $status = intval($_GET['status']);
        $ubah_status = $conn->query("UPDATE kegiatan SET status = '$status' WHERE id = '$id'");
    }

    echo "<script>
            document.location='index.php';
          </script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>To Do List</title>
</head>
<body>
    <div class="container">
        <h3>
            To Do List
            <small class="text-muted">Catat semua ~kenangan~, maksudnya targetmu disini.</small>
        </h3>
        <hr>
        <!-- Form Input Data -->
        <form class="form row" method="POST" action="" name="myForm" onsubmit="return(validate());">
            <?php
            $isi = '';
            $tgl_awal = '';
            $tgl_akhir = '';
            if (isset($_GET['id'])) {
                $ambil = $conn->query("SELECT * FROM kegiatan WHERE id='" . $_GET['id'] . "'");
                while ($row = $ambil->fetch_assoc()) {
                    $isi = $row['isi'];
                    $tgl_awal = $row['tanggal_awal'];
                    $tgl_akhir = $row['tanggal_akhir'];
                }
                echo '<input type="hidden" name="id" value="' . $_GET['id'] . '">';
            }
            ?>
            <div class="col">
                <label for="inputIsi" class="form-label fw-bold">Kegiatan</label>
                <input type="text" class="form-control" name="isi" id="inputIsi" placeholder="Kegiatan" value="<?php echo $isi; ?>">
            </div>
            <div class="col">
                <label for="inputTanggalAwal" class="form-label fw-bold">Tanggal Awal</label>
                <input type="date" class="form-control" name="tgl_awal" id="inputTanggalAwal" value="<?php echo $tgl_awal; ?>">
            </div>
            <div class="col mb-2">
                <label for="inputTanggalAkhir" class="form-label fw-bold">Tanggal Akhir</label>
                <input type="date" class="form-control" name="tgl_akhir" id="inputTanggalAkhir" value="<?php echo $tgl_akhir; ?>">
            </div>
            <div class="col">
            <button type="submit" class="btn btn-primary rounded-pill px-3" name="simpan">Simpan</button>
            </div>
        </form>

        <!-- Table -->
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Kegiatan</th>
                    <th scope="col">Awal</th>
                    <th scope="col">Akhir</th>
                    <th scope="col">Status</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT * FROM kegiatan ORDER BY status, tgl_awal");
                $no = 1;
                while ($data = $result->fetch_assoc()) {
                ?>
                    <tr>
                        <th scope="row"><?php echo $no++ ?></th>
                        <td><?php echo $data['isi'] ?></td>
                        <td><?php echo $data['tgl_awal'] ?></td>
                        <td><?php echo $data['tgl_akhir'] ?></td>
                        <td>
                            <?php
                            if ($data['status'] == '1') {
                            ?>
                                <a class="btn btn-success rounded-pill px-3" type="button"
                                   href="index.php?id=<?php echo $data['id'] ?>&aksi=ubah_status&status=0">
                                   Sudah
                                </a>
                            <?php
                            } else {
                            ?>
                                <a class="btn btn-warning rounded-pill px-3" type="button"
                                   href="index.php?id=<?php echo $data['id'] ?>&aksi=ubah_status&status=1">
                                   Belum
                                </a>
                            <?php
                            }
                            ?>
                        </td>
                        <td>
                            <a class="btn btn-info rounded-pill px-3" href="index.php?id=<?php echo $data['id'] ?>">Ubah</a>
                            <a class="btn btn-danger rounded-pill px-3" href="index.php?id=<?php echo $data['id'] ?>&aksi=hapus">Hapus</a>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>