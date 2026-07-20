-- ============================================================
-- FILE: database/sql/update_existing_data.sql
-- Jalankan query ini di phpMyAdmin SETELAH migrate
-- ============================================================

-- 1. Set default category untuk spare_parts yang belum ada
UPDATE spare_parts SET category = 'Oli'    WHERE name LIKE '%Oli%'    AND category IS NULL;
UPDATE spare_parts SET category = 'Filter' WHERE name LIKE '%Filter%' AND category IS NULL;
UPDATE spare_parts SET category = 'Rem'    WHERE name LIKE '%Rem%'    AND category IS NULL;
UPDATE spare_parts SET category = 'Rem'    WHERE name LIKE '%Kampas%' AND category IS NULL;
UPDATE spare_parts SET category = 'Busi'   WHERE name LIKE '%Busi%'   AND category IS NULL;
UPDATE spare_parts SET category = 'Aki'    WHERE name LIKE '%Aki%'    AND category IS NULL;
UPDATE spare_parts SET category = 'AC'     WHERE name LIKE '%Radiator%' AND category IS NULL;
UPDATE spare_parts SET category = 'AC'     WHERE name LIKE '%Air Radiator%' AND category IS NULL;
UPDATE spare_parts SET category = 'Wiper'  WHERE name LIKE '%Wiper%'  AND category IS NULL;
UPDATE spare_parts SET category = 'Umum'   WHERE category IS NULL;

-- 2. Set brand dari nama produk yang sudah ada
UPDATE spare_parts SET brand = 'NGK'      WHERE name LIKE '%NGK%'      AND brand IS NULL;
UPDATE spare_parts SET brand = 'Denso'    WHERE name LIKE '%Denso%'    AND brand IS NULL;
UPDATE spare_parts SET brand = 'Shell'    WHERE name LIKE '%Shell%'    AND brand IS NULL;
UPDATE spare_parts SET brand = 'Prestone' WHERE name LIKE '%Prestone%' AND brand IS NULL;

-- 3. Set default category untuk services
UPDATE services SET category = 'Umum' WHERE category IS NULL OR category = '';

-- 4. Set kategori spesifik untuk services yang sudah ada
UPDATE services SET category = 'Mesin' WHERE name LIKE '%Oli%';
UPDATE services SET category = 'Umum'  WHERE name LIKE '%Ringan%';
UPDATE services SET category = 'Mesin' WHERE name LIKE '%Berat%';

-- Verifikasi hasilnya
SELECT name, category, brand FROM spare_parts ORDER BY category;
SELECT name, category FROM services;
