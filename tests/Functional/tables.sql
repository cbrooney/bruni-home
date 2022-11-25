CREATE TABLE IF NOT EXISTS `test_table`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `created_at` datetime NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
