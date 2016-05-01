CREATE TABLE `wifi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bssid` varchar(18) NOT NULL,
  `latitude` decimal(8,6) NOT NULL,
  `longitude` decimal(8,6) NOT NULL,
  `ssid` varchar(255) NOT NULL,
  `encryption` varchar(10) NOT NULL,
  `connection_mode` varchar(20) NOT NULL,
  `channel` tinyint(4) NOT NULL,
  `receive_level` tinyint(4) NOT NULL,
  `spotted` datetime NOT NULL,
  `created` datetime NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
