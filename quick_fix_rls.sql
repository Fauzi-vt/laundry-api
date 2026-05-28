-- =====================================================
-- QUICK FIX: Data tidak muncul di Flutter
-- Jalankan di Supabase Dashboard > SQL Editor
-- =====================================================

-- 1. Pastikan tabel services bisa dibaca anon & authenticated
ALTER TABLE public.services DISABLE ROW LEVEL SECURITY;

-- 2. Pastikan tabel transactions bisa dibaca
ALTER TABLE public.transactions DISABLE ROW LEVEL SECURITY;

-- 3. Pastikan tabel transaction_details bisa dibaca
ALTER TABLE public.transaction_details DISABLE ROW LEVEL SECURITY;

-- 4. Pastikan tabel users bisa dibaca
ALTER TABLE public.users DISABLE ROW LEVEL SECURITY;

-- 5. Cek jumlah data di setiap tabel
SELECT 'users'               AS tabel, COUNT(*) AS jumlah FROM public.users
UNION ALL
SELECT 'services'            AS tabel, COUNT(*) AS jumlah FROM public.services
UNION ALL
SELECT 'transactions'        AS tabel, COUNT(*) AS jumlah FROM public.transactions
UNION ALL
SELECT 'transaction_details' AS tabel, COUNT(*) AS jumlah FROM public.transaction_details;
