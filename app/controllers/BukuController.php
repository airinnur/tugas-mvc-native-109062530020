<?php
class BukuController
{
    public function __construct(private Buku $model) {}
 
    // Tampilkan daftar buku
    public function index(): void
    {
        $buku  = $this->model->getAll();
        $pesan = $_SESSION['pesan'] ?? null;   // pesan sukses/gagal (sekali tampil)
        unset($_SESSION['pesan']);
        require __DIR__ . '/../views/buku/index.php';
    }
 
    // Tampilkan form tambah
    public function create(): void
    {
        $errors = [];
        $old    = [];
        require __DIR__ . '/../views/buku/create.php';
    }
 
    // Simpan buku baru
    public function store(): void
    {
        $this->harusPost();
        [$data, $errors] = $this->validasi($_POST);
 
        if (!$errors && $this->model->isbnExists($data['isbn'])) {
            $errors['isbn'] = 'ISBN sudah terdaftar.';
        }
        if ($errors) {                          // tampilkan form lagi + pesan error
            $old = $data;
            require __DIR__ . '/../views/buku/create.php';
            return;
        }
 
        try {
            $this->model->insert($data);
            $this->pesan('sukses', 'Buku berhasil ditambahkan.');
        } catch (PDOException $e) {
            $this->pesan('gagal', 'Buku gagal disimpan.');
        }
        $this->redirect();
    }
 
    // Tampilkan form edit
    public function edit(): void
    {
        $id   = (int) ($_GET['id'] ?? 0);
        $buku = $this->model->findById($id);
 
        if (!$buku) {
            http_response_code(404);
            exit('Data buku tidak ditemukan.');
        }
 
        $errors = [];
        $old    = $buku;
        require __DIR__ . '/../views/buku/edit.php';
    }
 
    // Simpan perubahan buku
    public function update(): void
    {
        $this->harusPost();
        $id   = (int) ($_POST['id'] ?? 0);
        $buku = $this->model->findById($id);
 
        if (!$buku) {
            $this->pesan('gagal', 'Data buku tidak ditemukan.');
            $this->redirect();
        }
 
        [$data, $errors] = $this->validasi($_POST);
 
        if (!$errors && $this->model->isbnExists($data['isbn'], $id)) {
            $errors['isbn'] = 'ISBN sudah dipakai buku lain.';
        }
        if ($errors) {
            $old       = $data;
            $old['id'] = $id;   // form edit butuh id untuk hidden input
            require __DIR__ . '/../views/buku/edit.php';
            return;
        }
 
        try {
            $this->model->update($id, $data);
            $this->pesan('sukses', 'Data buku berhasil diperbarui.');
        } catch (PDOException $e) {
            $this->pesan('gagal', 'Data buku gagal diperbarui.');
        }
        $this->redirect();
    }
 
    // Hapus buku
    public function delete(): void
    {
        $this->harusPost();
        $id = (int) ($_POST['id'] ?? 0);
 
        try {
            if ($this->model->delete($id)) {
                $this->pesan('sukses', 'Buku berhasil dihapus.');
            } else {
                $this->pesan('gagal', 'Buku gagal dihapus.');
            }
        } catch (PDOException $e) {
            $this->pesan('gagal', 'Terjadi kesalahan saat menghapus buku.');
        }
        $this->redirect();
    }
 
    /* ---------- fungsi bantu ---------- */
 
    // Mengembalikan [data yang sudah dirapikan, daftar error]
    private function validasi(array $input): array
    {
        $data = [
            'isbn'         => trim($input['isbn'] ?? ''),
            'judul'        => trim($input['judul'] ?? ''),
            'penulis'      => trim($input['penulis'] ?? ''),
            'penerbit'     => trim($input['penerbit'] ?? ''),
            'tahun_terbit' => trim($input['tahun_terbit'] ?? ''),
            'stok'         => trim($input['stok'] ?? ''),
        ];
        $errors = [];
 
        foreach ($data as $nama => $nilai) {
            if ($nilai === '') {
                $errors[$nama] = 'Wajib diisi.';
            }
        }
 
        if (!isset($errors['isbn']) && !preg_match('/^[0-9-]{10,17}$/', $data['isbn'])) {
            $errors['isbn'] = 'ISBN harus 10-17 karakter (angka atau tanda hubung).';
        }
        if (!isset($errors['tahun_terbit'])) {
            $tahun = filter_var($data['tahun_terbit'], FILTER_VALIDATE_INT);
            if ($tahun === false || $tahun < 1000 || $tahun > (int) date('Y') + 1) {
                $errors['tahun_terbit'] = 'Tahun terbit tidak valid.';
            }
        }
        if (!isset($errors['stok'])) {
            $stok = filter_var($data['stok'], FILTER_VALIDATE_INT);
            if ($stok === false || $stok < 0) {
                $errors['stok'] = 'Stok harus angka 0 atau lebih.';
            }
        }
 
        return [$data, $errors];
    }
 
    private function harusPost(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Gunakan metode POST.');
        }
    }
 
    private function pesan(string $jenis, string $teks): void
    {
        $_SESSION['pesan'] = ['jenis' => $jenis, 'teks' => $teks];
    }
 
    private function redirect(): never
    {
        header('Location: index.php?page=buku');
        exit;
    }
}
?>