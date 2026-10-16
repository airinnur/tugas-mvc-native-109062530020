<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Buku</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Tambah Buku</h2>

    <?php if ($errors): ?>
        <p class="pesan gagal">Data belum valid. Periksa isian di bawah ini.</p>
    <?php endif; ?>

    <form method="post" action="index.php?page=buku&action=store">
        <label>ISBN</label><br>
        <input type="text" name="isbn" value="<?= htmlspecialchars($old['isbn'] ?? '') ?>"><br>
        <small class="error"><?= htmlspecialchars($errors['isbn'] ?? '') ?></small><br>

        <label>Judul</label><br>
        <input type="text" name="judul" value="<?= htmlspecialchars($old['judul'] ?? '') ?>"><br>
        <small class="error"><?= htmlspecialchars($errors['judul'] ?? '') ?></small><br>

        <label>Penulis</label><br>
        <input type="text" name="penulis" value="<?= htmlspecialchars($old['penulis'] ?? '') ?>"><br>
        <small class="error"><?= htmlspecialchars($errors['penulis'] ?? '') ?></small><br>

        <label>Penerbit</label><br>
        <input type="text" name="penerbit" value="<?= htmlspecialchars($old['penerbit'] ?? '') ?>"><br>
        <small class="error"><?= htmlspecialchars($errors['penerbit'] ?? '') ?></small><br>

        <label>Tahun Terbit</label><br>
        <input type="number" name="tahun_terbit" value="<?= htmlspecialchars($old['tahun_terbit'] ?? '') ?>"><br>
        <small class="error"><?= htmlspecialchars($errors['tahun_terbit'] ?? '') ?></small><br>

        <label>Stok</label><br>
        <input type="number" name="stok" value="<?= htmlspecialchars($old['stok'] ?? '') ?>"><br>
        <small class="error"><?= htmlspecialchars($errors['stok'] ?? '') ?></small><br><br>

        <button type="submit">Simpan</button>
        <a href="index.php?page=buku">Kembali</a>
    </form>
</body>
</html>