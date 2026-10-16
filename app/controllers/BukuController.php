<?php
class BukuController
{
    public function __construct(private Buku $model) {}

    public function index(): void
    {
        $buku = $this->model->getAll();
        require __DIR__ . '/../views/buku/index.php';
    }

    public function create(): void
    {
        require __DIR__ . '/../views/buku/create.php';
    }

    public function store(): void
    {
        [$data, $error] = $this->validateInput($_POST);
        if ($error) {
            $this->redirect('create', 'error', $error);
        }

        try {
            $this->model->insert($data);
            $this->redirect('index', 'success', 'Data buku berhasil ditambahkan.');
        } catch (PDOException $e) {
            $this->redirect('create', 'error', $this->dbMessage($e));
        }
    }

    public function edit(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $buku = $this->model->findById($id);

        if (!$buku) {
            http_response_code(404);
            exit('Data buku tidak ditemukan.');
        }
        require __DIR__ . '/../views/buku/edit.php';
    }

    public function update(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        [$data, $error] = $this->validateInput($_POST);
        if ($error) {
            $this->redirect('edit&id=' . $id, 'error', $error);
        }

        try {
            $this->model->update($id, $data);
            $this->redirect('index', 'success', 'Data buku berhasil diperbarui.');
        } catch (PDOException $e) {
            $this->redirect('edit&id=' . $id, 'error', $this->dbMessage($e));
        }
    }
        public function delete(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        try {
            $this->model->delete($id);
            $this->redirect('index', 'success', 'Data buku berhasil dihapus.');
        } catch (PDOException $e) {
            $this->redirect('index', 'error', 'Data buku gagal dihapus.');
        }
    }

    private function validateInput(array $input): array
    {
        $data = [
            'judul'        => trim($input['judul'] ?? ''),
            'penulis'      => trim($input['penulis'] ?? ''),
            'penerbit'     => trim($input['penerbit'] ?? ''),
            'tahun_terbit' => trim($input['tahun_terbit'] ?? ''),
            'isbn'         => trim($input['isbn'] ?? '')
        ];

        foreach ($data as $nilai) {
            if ($nilai === '') {
                return [$data, 'Semua field wajib diisi.'];
            }
        }

        $tahunMax = (int) date('Y') + 1;
        if (!ctype_digit($data['tahun_terbit'])
            || (int) $data['tahun_terbit'] < 1000
            || (int) $data['tahun_terbit'] > $tahunMax) {
            return [$data, "Tahun terbit harus berupa angka 1000 - $tahunMax."];
        }

        if (!preg_match('/^[0-9-]{10,20}$/', $data['isbn'])) {
            return [$data, 'ISBN hanya boleh angka/tanda hubung (10-20 karakter).'];
        }

        return [$data, null];
    }

    private function dbMessage(PDOException $e): string
    {
        return $e->getCode() === '23000'
            ? 'ISBN sudah terdaftar. Gunakan ISBN yang berbeda.'
            : 'Operasi database gagal. Silakan coba lagi.';
    }

    private function redirect(string $action, string $type, string $message): void
    {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
        header('Location: index.php?page=buku&action=' . $action);
        exit;
    }
}