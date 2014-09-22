--
-- OneAuth
--

CREATE TABLE `users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) DEFAULT NULL,
  `password` char(255) DEFAULT NULL,
  `date_created` datetime NOT NULL,
  `date_seen` datetime NOT NULL,
  `ip` varchar(15) NOT NULL,
  `flags` varchar(10) DEFAULT NULL,
  `token` char(255) NOT NULL,
  `token_expiry` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
