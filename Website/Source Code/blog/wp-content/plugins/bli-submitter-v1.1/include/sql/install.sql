CREATE TABLE `%%WP-PREFIX%%posts_sent_to_bli` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `url` varchar(767) NOT NULL,
  `sent_date_time` datetime NOT NULL,
  `extra` text,
  PRIMARY KEY (`id`),
  KEY `url` (`url`(767))
) ENGINE=InnoDB DEFAULT CHARSET=latin1;