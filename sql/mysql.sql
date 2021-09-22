CREATE TABLE `leebuyer_signup_actions` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '流水號',
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '標題',
  `detail` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '細節',
  `action_date` datetime NOT NULL COMMENT '活動日期',
  `end_date` datetime NOT NULL COMMENT '報名截止日期',
  `number` smallint(5) unsigned NOT NULL COMMENT '人數',
  `setup` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '設置',
  `uid` mediumint(8) unsigned NOT NULL COMMENT '使用者登入編號',
  `enable` enum('1','0') NOT NULL COMMENT '活動開關',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
