CREATE TABLE IF NOT EXISTS `test_table`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `created_at` datetime NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `file_list` (
    `id` int NOT NULL AUTO_INCREMENT,
    `run` int NOT NULL,
    `full_path` varchar(300) NOT NULL,
    `file_name` varchar(100) NOT NULL,
    `relative_path` varchar(200) NOT NULL,
    `file_type` varchar(20) DEFAULT NULL,
    `file_size` int DEFAULT NULL,
    `hash` varchar(300) DEFAULT NULL,
    `m_time` datetime DEFAULT NULL,
    `a_time` datetime DEFAULT NULL,
    `c_time` datetime DEFAULT NULL,
    `created_at` datetime NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
