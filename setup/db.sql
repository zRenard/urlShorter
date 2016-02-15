-- Table for short url
CREATE TABLE `list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` text NOT NULL,
  `url` text NOT NULL,
  `creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `validity` timestamp NULL DEFAULT NULL,
  `hits` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COMMENT='Shorter URL list';

-- Table for additional logging informations
CREATE TABLE `hits` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `code` varchar(100) NOT NULL,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ip` varchar(20) NOT NULL DEFAULT '',
  `host` varchar(200) NOT NULL DEFAULT '',
  `server` varchar(200) NOT NULL DEFAULT '',
  `serverport` varchar(10) NOT NULL DEFAULT '',
  `method` varchar(20) NOT NULL DEFAULT '',
  `uri` varchar(200) NOT NULL DEFAULT '',
  `agent` varchar(100) NOT NULL DEFAULT '',
  `referer` varchar(200) NOT NULL DEFAULT '',
  `status` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COMMENT='Shorter URL Hits Logs';
