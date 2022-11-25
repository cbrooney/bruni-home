CREATE TABLE IF NOT EXISTS `test_table`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `created_at` datetime NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO test_table (id, created_at)
VALUES (1, '2022-10-03 19:36:56');
INSERT INTO test_table (id, created_at)
VALUES (2, '2022-10-03 19:37:31');
