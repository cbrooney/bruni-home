CREATE TABLE IF NOT EXISTS `test_table`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `created_at` datetime NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `file_list` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `run` int(11) NOT NULL,
    `full_path` varchar(300) NOT NULL,
    `file_name` varchar(100) NOT NULL,
    `relative_path` varchar(200) NOT NULL,
    `file_type` varchar(20) DEFAULT NULL,
    `personen` varchar(300) DEFAULT NULL,
    `comment` varchar(300) DEFAULT NULL,
    `file_size` bigint(20) DEFAULT NULL,
    `hash` varchar(300) DEFAULT NULL,
    `hashing_duration_ms` int(11) DEFAULT NULL,
    `m_time` datetime DEFAULT NULL,
    `a_time` datetime DEFAULT NULL,
    `c_time` datetime DEFAULT NULL,
    `created_at` datetime NOT NULL,
    PRIMARY KEY (`id`),
    KEY `run_index` (`run`),
    KEY `full_path_index` (`full_path`),
    KEY `m_time_index` (`m_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `query_collection` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `type` varchar(20) NOT NULL,
    `active` tinyint(4) NOT NULL DEFAULT 0,
    `query` text NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
