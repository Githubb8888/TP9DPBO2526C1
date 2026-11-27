<?php
require_once __DIR__ . '/KontrakPresenterJadwal.php';
require_once __DIR__ . '/../models/KontrakModelJadwal.php';
require_once __DIR__ . '/../views/KontrakViewJadwal.php';

class PresenterJadwal implements KontrakPresenterJadwal
{
    private const DAFTAR_STATUS = ['Terjadwal', 'Selesai', 'Dibatalkan'];

    private KontrakModelJadwal $model;
    private KontrakViewJadwal $view;

    public function __construct(KontrakModelJadwal $model, KontrakViewJadwal $view)
    {
        $this->model = $model;
        $this->view = $view;
    }

    private function esc(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    private function formatTanggal(string $tanggal): string
    {
        $waktu = strtotime($tanggal);
        return $waktu === false ? $tanggal : date('d M Y', $waktu);
    }

    private function buildRowHtml(int $no, JadwalInterview $jadwal): string
    {
        $id = $jadwal->getId();
        $status = $jadwal->getStatus();
        return '<tr>'
            . '<td class="muted">' . $no . '</td>'
            . '<td><strong>' . $this->esc($jadwal->getNamaKandidat()) . '</strong></td>'
            . '<td>' . $this->esc($jadwal->getPewawancara()) . '</td>'
            . '<td>' . $this->esc($this->formatTanggal($jadwal->getTanggal())) . '</td>'
            . '<td>' . $this->esc(substr($jadwal->getJam(), 0, 5)) . '</td>'
            . '<td>' . $this->esc($jadwal->getLokasi()) . '</td>'
            . '<td><span class="badge badge-' . strtolower($status) . '">' . $this->esc($status) . '</span></td>'
            . '<td class="aksi">'
            . '<a class="link-edit" href="index.php?page=jadwal&amp;screen=edit&amp;id=' . $id . '">Edit</a>'
            . '<form method="post" action="index.php?page=jadwal" onsubmit="return confirm(\'Hapus jadwal ini?\')">'
            . '<input type="hidden" name="action" value="hapus">'
            . '<input type="hidden" name="id" value="' . $id . '">'
            . '<button type="submit" class="link-hapus">Hapus</button>'
            . '</form>'
            . '</td>'
            . '</tr>';
    }

    private function buildOpsiKandidat(?int $terpilih): string
    {
        $html = '<option value="">-- Pilih Kandidat --</option>';
        foreach ($this->model->getOpsiKandidat() as $opsi) {
            $pilih = ((int)$opsi['id'] === $terpilih) ? ' selected' : '';
            $html .= '<option value="' . (int)$opsi['id'] . '"' . $pilih . '>' . $this->esc($opsi['nama']) . '</option>';
        }
        return $html;
    }

    private function buildOpsiStatus(string $terpilih): string
    {
        $html = '';
        foreach (self::DAFTAR_STATUS as $status) {
            $pilih = ($status === $terpilih) ? ' selected' : '';
            $html .= '<option value="' . $status . '"' . $pilih . '>' . $status . '</option>';
        }
        return $html;
    }

    private function tanggalValid(string $tanggal): bool
    {
        $d = DateTime::createFromFormat('Y-m-d', $tanggal);
        return $d !== false && $d->format('Y-m-d') === $tanggal;
    }

    private function jamValid(string $jam): bool
    {
        return preg_match('/^([01]\d|2[0-3]):[0-5]\d(:[0-5]\d)?$/', $jam) === 1;
    }

    private function dataValid(int $kandidatId, string $pewawancara, string $tanggal, string $jam, string $lokasi): bool
    {
        return $kandidatId > 0
            && $pewawancara !== ''
            && $lokasi !== ''
            && $this->tanggalValid($tanggal)
            && $this->jamValid($jam);
    }

    private function normalisasiStatus(string $status): string
    {
        return in_array($status, self::DAFTAR_STATUS, true) ? $status : 'Terjadwal';
    }

    public function tampilkanJadwal(): string
    {
        $listJadwal = $this->model->getAllJadwal();
        $totalJadwal = count($listJadwal);

        $rowsHtml = '';
        if ($totalJadwal === 0) {
            $rowsHtml = '<tr><td colspan="8" class="kosong">Belum ada jadwal interview.</td></tr>';
        } else {
            $no = 1;
            foreach ($listJadwal as $jadwal) {
                $rowsHtml .= $this->buildRowHtml($no, $jadwal);
                $no++;
            }
        }

        return $this->view->renderDaftar($rowsHtml, $totalJadwal);
    }

    public function tampilkanFormJadwal(?int $id = null): string
    {
        if ($id !== null) {
            $jadwal = $this->model->getJadwalById($id);
            if ($jadwal === null) {
                header('Location: index.php?page=jadwal');
                exit();
            }

            $formData = [
                'id' => $jadwal->getId(),
                'pewawancara' => $jadwal->getPewawancara(),
                'tanggal' => $jadwal->getTanggal(),
                'jam' => substr($jadwal->getJam(), 0, 5),
                'lokasi' => $jadwal->getLokasi(),
            ];
            return $this->view->renderForm(
                'Ubah Jadwal Interview',
                'ubah',
                $formData,
                $this->buildOpsiKandidat($jadwal->getKandidatId()),
                $this->buildOpsiStatus($jadwal->getStatus())
            );
        }

        return $this->view->renderForm(
            'Tambah Jadwal Interview',
            'tambah',
            [],
            $this->buildOpsiKandidat(null),
            $this->buildOpsiStatus('Terjadwal')
        );
    }

    public function tambahJadwal(int $kandidatId, string $pewawancara, string $tanggal, string $jam, string $lokasi, string $status): void
    {
        $pewawancara = trim($pewawancara);
        $lokasi = trim($lokasi);
        if (!$this->dataValid($kandidatId, $pewawancara, $tanggal, $jam, $lokasi)) {
            return;
        }
        $this->model->addJadwal($kandidatId, $pewawancara, $tanggal, $jam, $lokasi, $this->normalisasiStatus($status));
    }

    public function ubahJadwal(int $id, int $kandidatId, string $pewawancara, string $tanggal, string $jam, string $lokasi, string $status): void
    {
        $pewawancara = trim($pewawancara);
        $lokasi = trim($lokasi);
        if (!$this->dataValid($kandidatId, $pewawancara, $tanggal, $jam, $lokasi)) {
            return;
        }
        $this->model->updateJadwal($id, $kandidatId, $pewawancara, $tanggal, $jam, $lokasi, $this->normalisasiStatus($status));
    }

    public function hapusJadwal(int $id): void
    {
        $this->model->deleteJadwal($id);
    }
}
