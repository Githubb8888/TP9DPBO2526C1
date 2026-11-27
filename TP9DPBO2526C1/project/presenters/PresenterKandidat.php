<?php
require_once __DIR__ . '/KontrakPresenter.php';
require_once __DIR__ . '/../models/KontrakModel.php';
require_once __DIR__ . '/../views/KontrakView.php';

class PresenterKandidat implements KontrakPresenter
{
    private KontrakModel $model;
    private KontrakView $view;

    public function __construct(KontrakModel $model, KontrakView $view)
    {
        $this->model = $model;
        $this->view = $view;
    }

    private function esc(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    private function buildRowHtml(int $no, Kandidat $kandidat): string
    {
        $id = $kandidat->getId();
        return '<tr>'
            . '<td class="muted">' . $no . '</td>'
            . '<td><strong>' . $this->esc($kandidat->getNama()) . '</strong></td>'
            . '<td>' . $this->esc($kandidat->getPosisi()) . '</td>'
            . '<td>' . $this->esc($kandidat->getDomisili()) . '</td>'
            . '<td>' . $kandidat->getSkorTes() . ' / 100</td>'
            . '<td>' . $kandidat->getPengalamanTahun() . ' Tahun</td>'
            . '<td class="aksi">'
            . '<a class="link-edit" href="index.php?page=kandidat&amp;screen=edit&amp;id=' . $id . '">Edit</a>'
            . '<form method="post" action="index.php?page=kandidat" onsubmit="return confirm(\'Hapus kandidat ini?\')">'
            . '<input type="hidden" name="action" value="hapus">'
            . '<input type="hidden" name="id" value="' . $id . '">'
            . '<button type="submit" class="link-hapus">Hapus</button>'
            . '</form>'
            . '</td>'
            . '</tr>';
    }

    private function dataValid(string $nama, string $posisi, string $domisili): bool
    {
        return $nama !== '' && $posisi !== '' && $domisili !== '';
    }

    private function batasiSkor(int $skor): int
    {
        return max(0, min(100, $skor));
    }

    public function tampilkanKandidat(): string
    {
        $listKandidat = $this->model->getAllKandidat();
        $totalKandidat = count($listKandidat);

        $rowsHtml = '';
        if ($totalKandidat === 0) {
            $rowsHtml = '<tr><td colspan="7" class="kosong">Belum ada data kandidat.</td></tr>';
        } else {
            $no = 1;
            foreach ($listKandidat as $kandidat) {
                $rowsHtml .= $this->buildRowHtml($no, $kandidat);
                $no++;
            }
        }

        return $this->view->renderDaftar($rowsHtml, $totalKandidat);
    }

    public function tampilkanFormKandidat(?int $id = null): string
    {
        if ($id !== null) {
            $kandidat = $this->model->getKandidatById($id);
            if ($kandidat === null) {
                header('Location: index.php?page=kandidat');
                exit();
            }

            $formData = [
                'id' => $kandidat->getId(),
                'nama' => $kandidat->getNama(),
                'posisi' => $kandidat->getPosisi(),
                'domisili' => $kandidat->getDomisili(),
                'skorTes' => $kandidat->getSkorTes(),
                'pengalamanTahun' => $kandidat->getPengalamanTahun(),
            ];
            return $this->view->renderForm('Ubah Data Kandidat', 'ubah', $formData);
        }

        return $this->view->renderForm('Tambah Kandidat Baru', 'tambah', []);
    }

    public function tambahKandidat(string $nama, string $posisi, string $domisili, int $skorTes, int $pengalamanTahun): void
    {
        $nama = trim($nama);
        $posisi = trim($posisi);
        $domisili = trim($domisili);
        if (!$this->dataValid($nama, $posisi, $domisili)) {
            return;
        }
        $this->model->addKandidat($nama, $posisi, $domisili, $this->batasiSkor($skorTes), max(0, $pengalamanTahun));
    }

    public function ubahKandidat(int $id, string $nama, string $posisi, string $domisili, int $skorTes, int $pengalamanTahun): void
    {
        $nama = trim($nama);
        $posisi = trim($posisi);
        $domisili = trim($domisili);
        if (!$this->dataValid($nama, $posisi, $domisili)) {
            return;
        }
        $this->model->updateKandidat($id, $nama, $posisi, $domisili, $this->batasiSkor($skorTes), max(0, $pengalamanTahun));
    }

    public function hapusKandidat(int $id): void
    {
        $this->model->deleteKandidat($id);
    }
}
