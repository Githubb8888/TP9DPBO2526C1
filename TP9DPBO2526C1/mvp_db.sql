CREATE DATABASE IF NOT EXISTS `mvp_kandidat_db` DEFAULT CHARACTER SET utf8mb4;
USE `mvp_kandidat_db`;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `jadwal_interview`;
DROP TABLE IF EXISTS `kandidat`;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE `kandidat` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nama` VARCHAR(100) NOT NULL,
  `posisi` VARCHAR(100) NOT NULL,
  `domisili` VARCHAR(100) NOT NULL,
  `skorTes` INT NOT NULL DEFAULT 0,
  `pengalamanTahun` INT NOT NULL DEFAULT 0
) ENGINE=InnoDB;

CREATE TABLE `jadwal_interview` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `kandidat_id` INT NOT NULL,
  `pewawancara` VARCHAR(100) NOT NULL,
  `tanggal` DATE NOT NULL,
  `jam` TIME NOT NULL,
  `lokasi` VARCHAR(100) NOT NULL,
  `status` ENUM('Terjadwal','Selesai','Dibatalkan') NOT NULL DEFAULT 'Terjadwal',
  CONSTRAINT `fk_jadwal_kandidat` FOREIGN KEY (`kandidat_id`)
    REFERENCES `kandidat`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

INSERT INTO `kandidat` (`nama`, `posisi`, `domisili`, `skorTes`, `pengalamanTahun`) VALUES
('Raka Pratama', 'Backend Developer', 'Bandung', 92, 3),
('Nadia Kusuma', 'Frontend Developer', 'Surabaya', 90, 2),
('Sinta Maharani', 'UI/UX Designer', 'Jakarta', 88, 2),
('Dimas Nugroho', 'Data Analyst', 'Yogyakarta', 85, 1),
('Bagas Setiawan', 'QA Engineer', 'Bandung', 81, 1);

INSERT INTO `jadwal_interview` (`kandidat_id`, `pewawancara`, `tanggal`, `jam`, `lokasi`, `status`) VALUES
(1, 'Bu Rina Wulandari', '2026-12-01', '09:00:00', 'Ruang Meeting A', 'Terjadwal'),
(2, 'Pak Hendra Saputra', '2026-12-01', '13:30:00', 'Online (Google Meet)', 'Terjadwal'),
(3, 'Bu Rina Wulandari', '2026-11-20', '10:00:00', 'Ruang Meeting B', 'Selesai'),
(4, 'Pak Hendra Saputra', '2026-11-18', '15:00:00', 'Online (Zoom)', 'Dibatalkan');
