-- This schema is based on Thomas Leister's guide for setting up dovecot+postfix+rspamd
-- See: https://thomas-leister.de/en/mailserver-debian-stretch/
-- Changes
--      * A "name" field for people who need a name or description to know who's
--        using the account and/or it purpouse
--      * A panel_users table so i can scrap in the near future the in_memory
--        "database" (because it won't survive application updates as it's defined
--        in security.yml)

CREATE TABLE `domains` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `domain` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `domain` (`domain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `accounts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL,
  `domain` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` tinytext DEFAULT NULL,
  `quota` int(10) unsigned DEFAULT 0,
  `enabled` tinyint(1) DEFAULT 0,
  `sendonly` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`,`domain`),
  KEY `domain` (`domain`),
  CONSTRAINT `accounts_ibfk_1` FOREIGN KEY (`domain`) REFERENCES `domains` (`domain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `aliases` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `source_username` varchar(64) NOT NULL,
  `source_domain` varchar(255) NOT NULL,
  `destination_username` varchar(64) NOT NULL,
  `destination_domain` varchar(255) NOT NULL,
  `enabled` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `source_username` (`source_username`,`source_domain`,`destination_username`,`destination_domain`),
  KEY `source_domain` (`source_domain`),
  CONSTRAINT `aliases_ibfk_1` FOREIGN KEY (`source_domain`) REFERENCES `domains` (`domain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `tlspolicies` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `domain` varchar(255) NOT NULL,
  `policy` enum('none','may','encrypt','dane','dane-only','fingerprint','verify','secure') NOT NULL,
  `params` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `domain` (`domain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `panel_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(25) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `password` varchar(120) DEFAULT NULL,
  `enabled` tinyint(4) DEFAULT NULL,
  `role` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `panel_users_username_uindex` (`username`),
  KEY `panel_users_enabled_index` (`enabled`),
  KEY `panel_users_role_index` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;