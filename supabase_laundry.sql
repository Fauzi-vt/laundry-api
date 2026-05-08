-- Supabase PostgreSQL Export for Rumah Laundry
-- Generated for Ready to Import (PostgreSQL Syntax)

-- Disable triggers and foreign key constraints temporarily
SET session_replication_role = 'replica';

-- ----------------------------
-- Drop existing tables
-- ----------------------------
DROP TABLE IF EXISTS "transaction_details" CASCADE;
DROP TABLE IF EXISTS "transactions" CASCADE;
DROP TABLE IF EXISTS "services" CASCADE;
DROP TABLE IF EXISTS "personal_access_tokens" CASCADE;
DROP TABLE IF EXISTS "failed_jobs" CASCADE;
DROP TABLE IF EXISTS "job_batches" CASCADE;
DROP TABLE IF EXISTS "jobs" CASCADE;
DROP TABLE IF EXISTS "cache_locks" CASCADE;
DROP TABLE IF EXISTS "cache" CASCADE;
DROP TABLE IF EXISTS "sessions" CASCADE;
DROP TABLE IF EXISTS "password_reset_tokens" CASCADE;
DROP TABLE IF EXISTS "users" CASCADE;

-- ----------------------------
-- Table structure for users
-- ----------------------------
CREATE TABLE "users" (
  "id" bigint PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
  "name" text NOT NULL,
  "email" text NOT NULL UNIQUE,
  "email_verified_at" timestamp with time zone NULL,
  "password" text NOT NULL,
  "role" text NOT NULL DEFAULT 'user',
  "phone" text NULL,
  "whatsapp" text NULL,
  "address" text NULL,
  "latitude" decimal(10,7) NULL,
  "longitude" decimal(10,7) NULL,
  "remember_token" text NULL,
  "created_at" timestamp with time zone DEFAULT now(),
  "updated_at" timestamp with time zone DEFAULT now()
);

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO "users" ("id", "name", "email", "password", "role", "phone", "whatsapp", "address", "created_at", "updated_at") OVERRIDING SYSTEM VALUE VALUES
(1, 'Administrator', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '081234567890', '081234567890', 'Jl. Laundry No. 1', now(), now()),
(2, 'rifafauzi', 'user@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', '089876543210', '089876543210', 'Jl. Pelanggan No. 2', now(), now());

-- ----------------------------
-- Table structure for services
-- ----------------------------
CREATE TABLE "services" (
  "id" bigint PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
  "name" text NOT NULL,
  "category" text NOT NULL DEFAULT 'Umum',
  "price" decimal(10,2) NOT NULL,
  "unit" text NOT NULL DEFAULT 'kg',
  "description" text NULL,
  "created_at" timestamp with time zone DEFAULT now(),
  "updated_at" timestamp with time zone DEFAULT now()
);

-- ----------------------------
-- Records of services
-- ----------------------------
INSERT INTO "services" ("id", "name", "category", "price", "unit", "description", "created_at", "updated_at") OVERRIDING SYSTEM VALUE VALUES
(1, 'Cuci Selimut / Bedcover', 'Satuan', 60000.00, 'pcs', 'Layanan cuci selimut dan bedcover bersih dan wangi.', now(), now()),
(2, 'Cuci Sepatu Premium', 'Spesial', 35000.00, 'pasang', 'Cuci sepatu manual dengan sabun khusus premium.', now(), now()),
(3, 'Cuci Kiloan Standar', 'Kiloan', 6000.00, 'kg', 'Cuci kering lipat tanpa setrika.', now(), now()),
(4, 'Cuci Kiloan Kilat', 'Kiloan', 10000.00, 'kg', 'Layanan express 1 hari selesai.', now(), now());

-- ----------------------------
-- Table structure for transactions
-- ----------------------------
CREATE TABLE "transactions" (
  "id" bigint PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
  "user_id" bigint NOT NULL REFERENCES "users"("id") ON DELETE CASCADE,
  "invoice_code" text NOT NULL UNIQUE,
  "total_price" decimal(15,2) NOT NULL DEFAULT 0.00,
  "status" text NOT NULL DEFAULT 'baru',
  "address" text NULL,
  "phone" text NULL,
  "payment_method" text NULL,
  "delivery_type" text NULL,
  "created_at" timestamp with time zone DEFAULT now(),
  "updated_at" timestamp with time zone DEFAULT now(),
  CONSTRAINT "transactions_status_check" CHECK ("status" IN ('baru','cuci','kering','setrika','selesai','diambil'))
);

-- ----------------------------
-- Records of transactions
-- ----------------------------
INSERT INTO "transactions" ("id", "user_id", "invoice_code", "total_price", "status", "address", "phone", "payment_method", "delivery_type", "created_at", "updated_at") OVERRIDING SYSTEM VALUE VALUES
(1, 2, 'INV-20260502-98414', 60000.00, 'selesai', 'Jl. Pelanggan No. 2', '089876543210', 'transfer', 'bawa_sendiri', now() - interval '2 hours', now() - interval '2 hours'),
(2, 2, 'INV-20260502-22B88', 52600.00, 'diambil', 'Jl. Pelanggan No. 2', '089876543210', 'cash', 'bawa_sendiri', now() - interval '5 hours', now() - interval '5 hours');

-- ----------------------------
-- Table structure for transaction_details
-- ----------------------------
CREATE TABLE "transaction_details" (
  "id" bigint PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
  "transaction_id" bigint NOT NULL REFERENCES "transactions"("id") ON DELETE CASCADE,
  "service_id" bigint NOT NULL REFERENCES "services"("id") ON DELETE CASCADE,
  "quantity" decimal(8,2) NOT NULL,
  "price" decimal(10,2) NOT NULL,
  "subtotal" decimal(15,2) NOT NULL,
  "created_at" timestamp with time zone DEFAULT now(),
  "updated_at" timestamp with time zone DEFAULT now()
);

-- ----------------------------
-- Records of transaction_details
-- ----------------------------
INSERT INTO "transaction_details" ("transaction_id", "service_id", "quantity", "price", "subtotal", "created_at", "updated_at") VALUES
(1, 1, 1.00, 60000.00, 60000.00, now(), now()),
(2, 2, 1.00, 35000.00, 35000.00, now(), now()),
(2, 3, 2.93, 6000.00, 17600.00, now(), now());

-- Re-enable triggers and foreign key constraints
SET session_replication_role = 'origin';
