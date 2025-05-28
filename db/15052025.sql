DROP TABLE IF EXISTS `master_tipe_kamar`;
CREATE TABLE master_tipe_kamar (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipe_kamar VARCHAR(50) NOT NULL,
    kelas_kamar VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted INT(11) NOT NULL DEFAULT 0
);

INSERT INTO master_tipe_kamar (id, tipe_kamar, kelas_kamar) VALUES
(1, 'DD', 'Deluxe Double Room'),
(2, 'DT', 'Deluxe Twin Room'),
(3, 'ED', 'Deluxe King Room');

DROP TABLE IF EXISTS `master_tipe_kamar_platform_tarif`;
CREATE TABLE master_tipe_kamar_platform_tarif (
    id INT AUTO_INCREMENT PRIMARY KEY,
    master_tipe_kamar_id INT NOT NULL,
    coa_type_id INT NOT NULL,
    jumlah double NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (master_tipe_kamar_id) REFERENCES master_tipe_kamar(id) ON DELETE CASCADE
);

INSERT INTO master_tipe_kamar_platform_tarif (master_tipe_kamar_id, coa_type_id, jumlah) VALUES
-- Deluxe Double Room (DD)
(1, 214, 271350),
(1, 209, 261300),
(1, 44, 225000);

ALTER TABLE transaction_journal_header
ADD COLUMN data JSON AFTER type;

ALTER TABLE transaction_journal
ADD COLUMN status_pembayaran INT(11) NOT NULL DEFAULT 0 AFTER credit;

ALTER TABLE transaction_journal
ADD COLUMN metode_pembayaran VARCHAR(100) NULL AFTER status_pembayaran;

ALTER TABLE transaction_journal
ADD COLUMN bukti VARCHAR(255) NULL AFTER metode_pembayaran;