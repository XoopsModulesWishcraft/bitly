
CREATE TABLE `bitly_byday` (
  `byday_id` bigint(14) unsigned NOT NULL AUTO_INCREMENT,
  `shorten_id` bigint(14) unsigned DEFAULT '0',
  `oauth_id` bigint(14) unsigned DEFAULT '0',
  `day` tinyint(6) unsigned DEFAULT '0',
  `clicks` int(14) unsigned DEFAULT '0',
  `day_start` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`byday_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `bitly_byminute` (
  `byminute_id` bigint(14) unsigned NOT NULL AUTO_INCREMENT,
  `shorten_id` bigint(14) unsigned DEFAULT NULL,
  `oauth_id` int(14) unsigned DEFAULT NULL,
  `minute` tinyint(4) unsigned DEFAULT '0',
  `clicks` int(14) unsigned DEFAULT '0',
  PRIMARY KEY (`byminute_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `bitly_countries` (
  `countries_id` bigint(14) unsigned NOT NULL AUTO_INCREMENT,
  `shorten_id` bigint(14) unsigned DEFAULT '0',
  `oauth_id` int(14) unsigned DEFAULT '0',
  `clicks` int(14) unsigned DEFAULT '0',
  `country` varchar(6) DEFAULT '--',
  PRIMARY KEY (`countries_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `bitly_oauth` (
  `oauth_id` int(14) unsigned NOT NULL AUTO_INCREMENT,
  `mode` enum('valid','invalid') DEFAULT 'valid',
  `bitly_key` varchar(255) DEFAULT NULL,
  `bitly_username` varchar(255) DEFAULT NULL,
  `bitly_clientid` varchar(255) DEFAULT NULL,
  `bitly_secret` varchar(255) DEFAULT NULL,
  `bitly_access_token` varchar(255) DEFAULT NULL,
  `ip` varchar(64) DEFAULT NULL,
  `netbios` varchar(255) DEFAULT NULL,
  `uid` int(13) unsigned DEFAULT '0',
  `root` tinyint(2) unsigned DEFAULT '0',
  `calls` int(14) unsigned DEFAULT '0',
  `created` int(12) unsigned DEFAULT '0',
  `updated` int(12) unsigned DEFAULT '0',
  PRIMARY KEY (`oauth_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `bitly_referrers` (
  `referrer_id` bigint(14) unsigned NOT NULL AUTO_INCREMENT,
  `shorten_id` bigint(14) unsigned DEFAULT '0',
  `oauth_id` int(14) unsigned DEFAULT '0',
  `clicks` int(14) unsigned DEFAULT '0',
  `referrer` varchar(255) DEFAULT NULL,
  `referrer_app` varchar(255) DEFAULT NULL,
  `url` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`referrer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `bitly_shorten` (
  `shorten_id` bigint(14) unsigned NOT NULL AUTO_INCREMENT,
  `oauth_id` int(14) unsigned DEFAULT '0',
  `domain` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `hash` varchar(255) DEFAULT NULL,
  `global_hash` varchar(255) DEFAULT NULL,
  `long_url` varchar(255) DEFAULT NULL,
  `new_hash` tinyint(2) unsigned DEFAULT '0',
  `title` varchar(128) DEFAULT NULL,
  `user_clicks` int(14) unsigned DEFAULT '0',
  `global_clicks` int(14) unsigned DEFAULT '0',
  `crawled` int(12) unsigned DEFAULT '0',
  `expires` int(12) unsigned DEFAULT '0',
  `created` int(12) unsigned DEFAULT '0',
  `updated` int(12) unsigned DEFAULT '0',
  PRIMARY KEY (`shorten_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `bitly_userclicks` (
  `userclicks_id` bigint(14) unsigned NOT NULL AUTO_INCREMENT,
  `shorten_id` bigint(14) unsigned DEFAULT '0',
  `oauth_id` int(14) unsigned DEFAULT '0',
  `days` tinyint(4) unsigned DEFAULT '0',
  `total_clicks` int(14) unsigned DEFAULT '0',
  PRIMARY KEY (`userclicks_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `bitly_userclicks_clicks` (
  `userclicks_clicks_id` bigint(14) unsigned NOT NULL AUTO_INCREMENT,
  `userclicks_id` bigint(14) unsigned DEFAULT '0',
  `shorten_id` bigint(14) unsigned DEFAULT '0',
  `oauth_id` int(14) unsigned DEFAULT '0',
  `day_start` varchar(32) DEFAULT '',
  `clicks` int(14) unsigned DEFAULT '0',
  PRIMARY KEY (`userclicks_clicks_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `bitly_usercountries` (
  `usercountries_id` bigint(14) unsigned NOT NULL AUTO_INCREMENT,
  `shorten_id` bigint(14) unsigned DEFAULT '0',
  `oauth_id` int(14) unsigned DEFAULT '0',
  `days` tinyint(4) unsigned DEFAULT '0',
  PRIMARY KEY (`usercountries_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `bitly_usercountries_clicks` (
  `usercountries_clicks_id` bigint(14) unsigned NOT NULL AUTO_INCREMENT,
  `usercountries_id` bigint(14) unsigned DEFAULT '0',
  `shorten_id` bigint(14) unsigned DEFAULT '0',
  `oauth_id` int(14) unsigned DEFAULT '0',
  `country` varchar(6) DEFAULT '--',
  `clicks` int(14) unsigned DEFAULT '0',
  PRIMARY KEY (`usercountries_clicks_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `bitly_userrealtimelinks` (
  `realtime_id` bigint(14) unsigned NOT NULL AUTO_INCREMENT,
  `shorten_id` bigint(14) unsigned DEFAULT '0',
  `oauth_id` int(14) unsigned DEFAULT '0',
  `user_hash` varchar(255) DEFAULT NULL,
  `clicks` int(14) unsigned DEFAULT '0',
  `expires` int(12) unsigned DEFAULT '0',
  `created` int(12) unsigned DEFAULT '0',
  `updated` int(12) unsigned DEFAULT '0',
  PRIMARY KEY (`realtime_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `bitly_userreferrers` (
  `userreferrers_id` bigint(14) unsigned NOT NULL AUTO_INCREMENT,
  `shorten_id` bigint(14) unsigned DEFAULT '0',
  `oauth_id` int(14) unsigned DEFAULT '0',
  `days` tinyint(4) unsigned DEFAULT '0',
  PRIMARY KEY (`userreferrers_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `bitly_userreferrers_clicks` (
  `userreferrers_clicks_id` bigint(14) unsigned NOT NULL AUTO_INCREMENT,
  `userreferrers_id` bigint(14) unsigned DEFAULT '0',
  `shorten_id` bigint(14) unsigned DEFAULT '0',
  `oauth_id` int(14) unsigned DEFAULT '0',
  `referrer` varchar(255) DEFAULT '',
  `clicks` int(14) unsigned DEFAULT '0',
  PRIMARY KEY (`userreferrers_clicks_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
