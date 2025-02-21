SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- Banco de dados: `financecontrol`
CREATE DATABASE IF NOT EXISTS `financecontrol` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `financecontrol`;

-- --------------------------------------------------------
-- Tabela `users` - Tabela principal com informações do usuário
-- --------------------------------------------------------
CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` bigint DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `about_me` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `location`, `about_me`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@softui.com', '$2y$10$r3PVcQgRmcwRR9DbRZ.QtuD8LMrfTCVUmYK1BqgS/Ml/qv7BU.kuy', NULL, NULL, NULL, NULL, '2025-02-20 02:25:44', '2025-02-20 02:25:44'),
(2, 'marco', 'marcobubola@hotmail.com', '$2y$10$5UK9.xpZxp95iO/y0JoRVO6WO0wWj5kv3zu6hpgOhqXoEzZA6qADK', NULL, NULL, NULL, NULL, '2025-02-20 02:46:57', '2025-02-20 02:46:57');


--
-- Estrutura para tabela `type`
--

CREATE TABLE `type` (
  `id_type` int NOT NULL,
  `desc_type` varchar(45) NOT NULL,
  `hexcolor_type` varchar(45) DEFAULT NULL,
  `icon_type` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

-- --------------------------------------------------------
-- Tabela `banks` - Bancos onde as transações são registradas
-- --------------------------------------------------------
CREATE TABLE `banks` (
  `id_bank` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `registration_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`id_bank`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------
-- Tabela `cashbook` - Registro de transações financeiras
-- --------------------------------------------------------
CREATE TABLE `cashbook` (
  `id` int NOT NULL AUTO_INCREMENT,
  `value` decimal(10,2) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `date` date NOT NULL,
  `is_pending` tinyint(1) NOT NULL DEFAULT '0',
  `attachment` varchar(255) DEFAULT NULL,
  `inc_datetime` datetime DEFAULT NULL COMMENT 'insert date',
  `edit_datetime` datetime DEFAULT NULL COMMENT 'edit date',
  `user_id` bigint UNSIGNED NOT NULL,
  `category_id` int NOT NULL,
  `type_id` int NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `segment_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  FOREIGN KEY (`category_id`) REFERENCES `category` (`id_category`),
  FOREIGN KEY (`type_id`) REFERENCES `type` (`id_type`),
  FOREIGN KEY (`segment_id`) REFERENCES `segment` (`id`),
  INDEX (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------
-- Tabela `category` - Categorias para transações
-- --------------------------------------------------------
CREATE TABLE `category` (
  `id_category` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  `user_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`id_category`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Tabela `clients` - Clientes relacionados às transações
-- --------------------------------------------------------
CREATE TABLE `clients` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `address` text,
  `user_id` bigint UNSIGNED NOT NULL,
  `registration_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Estrutura para tabela `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- --------------------------------------------------------
-- Tabela `invoice` - Faturas vinculadas aos bancos
-- --------------------------------------------------------
CREATE TABLE `invoice` (
  `id_invoice` int NOT NULL AUTO_INCREMENT,
  `id_bank` int NOT NULL,
  `description` varchar(255) NOT NULL,
  `installments` varchar(255) NOT NULL,
  `value` decimal(10,2) NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `category_id` int NOT NULL,
  PRIMARY KEY (`id_invoice`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  FOREIGN KEY (`id_bank`) REFERENCES `banks` (`id_bank`),
  FOREIGN KEY (`category_id`) REFERENCES `category` (`id_category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


--
-- Estrutura para tabela `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------


-- --------------------------------------------------------
-- Tabela `products` - Produtos vendidos
-- --------------------------------------------------------
CREATE TABLE `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `stock_quantity` int DEFAULT '0',
  `category_id` int NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`category_id`) REFERENCES `category` (`id_category`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


--
-- Estrutura para tabela `role`
--

CREATE TABLE `role` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `can_admin` smallint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Índices de tabela `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Índices de tabela `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);


---- Índices de tabela `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `user`
--
ALTER TABLE `user`
  ADD KEY `tb_user_role_id` (`role_id`);

--
-- Índices de tabela `type`
--
ALTER TABLE `type`
  ADD PRIMARY KEY (`id_type`);

-- --------------------------------------------------------
-- Tabela `segment` - Segmentos de clientes ou produtos
-- --------------------------------------------------------
CREATE TABLE `segment` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `category_id` int DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`category_id`) REFERENCES `category` (`id_category`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Tabela `targets` - Metas financeiras
-- --------------------------------------------------------
CREATE TABLE `targets` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT 'Default Title',
  `target_date` date DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `value` decimal(10,2) DEFAULT NULL,
  `is_completed` tinyint(1) DEFAULT '0',
  `user_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Tabela `transaction` - Transações financeiras
-- --------------------------------------------------------
CREATE TABLE `transaction` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `client_id` int NOT NULL,
  `total_value` decimal(10,2) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Tabela `transaction_items` - Itens das transações
-- --------------------------------------------------------
CREATE TABLE `transaction_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `transaction_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int DEFAULT '1',
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`transaction_id`) REFERENCES `transaction` (`id`),
  FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;




--
-- AUTO_INCREMENT de tabela `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;


--
-- AUTO_INCREMENT de tabela `role`
--
ALTER TABLE `role`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;


AUTO_INCREMENT de tabela `type`
--
ALTER TABLE `type`
  MODIFY `id_type` int NOT NULL AUTO_INCREMENT;

-- --------------------------------------------------------
-- Trigger para atualizar o estoque após a transação
-- --------------------------------------------------------
DELIMITER $$
CREATE TRIGGER `update_stock_after_transaction` AFTER INSERT ON `transaction_items` FOR EACH ROW BEGIN
    UPDATE `products`
    SET `stock_quantity` = `stock_quantity` - NEW.quantity
    WHERE `id` = NEW.product_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------
-- Índices para otimizar as consultas
-- --------------------------------------------------------

-- Índices para tabela `banks`
ALTER TABLE `banks`
  ADD INDEX `idx_banks_user_id` (`user_id`);

-- Índices para tabela `cashbook`
ALTER TABLE `cashbook`
  ADD INDEX `idx_cashbook_date` (`date`),
  ADD INDEX `idx_cashbook_user_id` (`user_id`);

-- Índices para tabela `category`
ALTER TABLE `category`
  ADD INDEX `idx_category_user_id` (`user_id`);

-- Índices para tabela `clients`
ALTER TABLE `clients`
  ADD INDEX `idx_clients_user_id` (`user_id`),
  ADD INDEX `idx_clients_name` (`name`);

-- Índices para tabela `invoice`
ALTER TABLE `invoice`
  ADD INDEX `idx_invoice_user_id` (`user_id`),
  ADD INDEX `idx_invoice_value` (`value`);

-- Índices para tabela `products`
ALTER TABLE `products`
  ADD INDEX `idx_products_category_id` (`category_id`),
  ADD INDEX `idx_products_user_id` (`user_id`);

-- Índices para tabela `segment`
ALTER TABLE `segment`
  ADD INDEX `idx_segment_category_id` (`category_id`),
  ADD INDEX `idx_segment_user_id` (`user_id`);

-- Índices para tabela `targets`
ALTER TABLE `targets`
  ADD INDEX `idx_targets_user_id` (`user_id`),
  ADD INDEX `idx_targets_target_date` (`target_date`);

-- Índices para tabela `transaction`
ALTER TABLE `transaction`
  ADD INDEX `idx_transaction_user_id` (`user_id`),
  ADD INDEX `idx_transaction_client_id` (`client_id`),
  ADD INDEX `idx_transaction_date` (`date`);

-- Índices para tabela `transaction_items`
ALTER TABLE `transaction_items`
  ADD INDEX `idx_transaction_items_transaction_id` (`transaction_id`),
  ADD INDEX `idx_transaction_items_product_id` (`product_id`);

-- Índices para tabela `type`
ALTER TABLE `type`
  ADD INDEX `idx_type_desc_type` (`desc_type`);

-- Índices para tabela `users`
ALTER TABLE `users`
  ADD INDEX `idx_users_email` (`email`);

COMMIT;

