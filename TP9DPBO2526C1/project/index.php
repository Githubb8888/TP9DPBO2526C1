<?php
require_once __DIR__ . '/views/KontrakView.php';
require_once __DIR__ . '/views/KontrakViewJadwal.php';
require_once __DIR__ . '/models/TabelKandidat.php';
require_once __DIR__ . '/views/ViewKandidat.php';
require_once __DIR__ . '/presenters/PresenterKandidat.php';
require_once __DIR__ . '/models/TabelJadwal.php';
require_once __DIR__ . '/views/ViewJadwal.php';
require_once __DIR__ . '/presenters/PresenterJadwal.php';

const DB_HOST = 'localhost';
const DB_NAME = 'mvp_kandidat_db';
const DB_USER = 'root';
const DB_PASS = '';

$page = $_GET['page'] ?? 'kandidat';
if (!in_array($page, ['kandidat', 'jadwal'], true)) {
    $page = 'kandidat';
}

$isPost = $_SERVER['REQUEST_METHOD'] === 'POST';
$action = $_POST['action'] ?? '';
$screen = $_GET['screen'] ?? 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$postId = isset($_POST['id']) ? (int)$_POST['id'] : 0;

if ($page === 'jadwal') {
    $presenter = new PresenterJadwal(
        new TabelJadwal(DB_HOST, DB_NAME, DB_USER, DB_PASS),
        new ViewJadwal()
    );

    if ($isPost) {
        if ($action === 'tambah') {
            $presenter->tambahJadwal(
                (int)($_POST['kandidat_id'] ?? 0),
                (string)($_POST['pewawancara'] ?? ''),
                (string)($_POST['tanggal'] ?? ''),
                (string)($_POST['jam'] ?? ''),
                (string)($_POST['lokasi'] ?? ''),
                (string)($_POST['status'] ?? '')
            );
        } elseif ($action === 'ubah' && $postId > 0) {
            $presenter->ubahJadwal(
                $postId,
                (int)($_POST['kandidat_id'] ?? 0),
                (string)($_POST['pewawancara'] ?? ''),
                (string)($_POST['tanggal'] ?? ''),
                (string)($_POST['jam'] ?? ''),
                (string)($_POST['lokasi'] ?? ''),
                (string)($_POST['status'] ?? '')
            );
        } elseif ($action === 'hapus' && $postId > 0) {
            $presenter->hapusJadwal($postId);
        }
        header('Location: index.php?page=jadwal');
        exit();
    }

    if ($screen === 'add') {
        echo $presenter->tampilkanFormJadwal();
    } elseif ($screen === 'edit' && $id !== null) {
        echo $presenter->tampilkanFormJadwal($id);
    } else {
        echo $presenter->tampilkanJadwal();
    }
} else {
    $presenter = new PresenterKandidat(
        new TabelKandidat(DB_HOST, DB_NAME, DB_USER, DB_PASS),
        new ViewKandidat()
    );

    if ($isPost) {
        if ($action === 'tambah') {
            $presenter->tambahKandidat(
                (string)($_POST['nama'] ?? ''),
                (string)($_POST['posisi'] ?? ''),
                (string)($_POST['domisili'] ?? ''),
                (int)($_POST['skorTes'] ?? 0),
                (int)($_POST['pengalamanTahun'] ?? 0)
            );
        } elseif ($action === 'ubah' && $postId > 0) {
            $presenter->ubahKandidat(
                $postId,
                (string)($_POST['nama'] ?? ''),
                (string)($_POST['posisi'] ?? ''),
                (string)($_POST['domisili'] ?? ''),
                (int)($_POST['skorTes'] ?? 0),
                (int)($_POST['pengalamanTahun'] ?? 0)
            );
        } elseif ($action === 'hapus' && $postId > 0) {
            $presenter->hapusKandidat($postId);
        }
        header('Location: index.php?page=kandidat');
        exit();
    }

    if ($screen === 'add') {
        echo $presenter->tampilkanFormKandidat();
    } elseif ($screen === 'edit' && $id !== null) {
        echo $presenter->tampilkanFormKandidat($id);
    } else {
        echo $presenter->tampilkanKandidat();
    }
}
