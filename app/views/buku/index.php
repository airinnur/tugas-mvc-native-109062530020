<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Buku</title>
    <head>
    <meta charset="UTF-8">
    <title>Daftar Buku</title>
    <link rel="stylesheet" href="style.css">
</head>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Daftar Buku</h2>

       <?php if (!empty($pesan)): ?>
        <p class="pesan <?= htmlspecialchars($pesan['jenis']) ?>">
            <?= htmlspecialchars($pesan['teks']) ?>
        </p>
    <?php endif; ?>

    <a href="index.php?page=buku&action=create">Tambah Buku</a>

    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>No</th>
            <th>ISBN</th>
            <th>Judul</th>
            <th>Penulis</th>
            <th>Penerbit</th>
            <th>Tahun</th>
            <th>Stok</th>
            <th>Aksi</th>
        </tr>

        <?php if (empty($buku)): ?>
        <tr><td colspan="8">Belum ada data buku.</td></tr>
        <?php endif; ?>

        <?php foreach ($buku as $i => $b): ?>
        <tr>
            <td><?= $i + 1 ?></td>
            <td><?= htmlspecialchars($b['isbn']) ?></td>
            <td><?= htmlspecialchars($b['judul']) ?></td>
            <td><?= htmlspecialchars($b['penulis']) ?></td>
            <td><?= htmlspecialchars($b['penerbit']) ?></td>
            <td><?= htmlspecialchars($b['tahun_terbit']) ?></td>
            <td><?= htmlspecialchars($b['stok']) ?></td>
            <td>
                <a href="index.php?page=buku&action=edit&id=<?= (int) $b['id'] ?>">Edit</a>
                <form method="post"
                      action="index.php?page=buku&action=delete"
                      style="display:inline"
                      onsubmit="return confirm('Hapus buku ini?')">
                    <input type="hidden" name="id" value="<?= (int) $b['id'] ?>">
                    <button type="submit">Hapus</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>