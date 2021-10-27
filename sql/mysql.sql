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
  `candidate` tinyint(3) unsigned NOT NULL '候補功能',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `leebuyer_signup_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '流水號',
  `action_id` smallint(5) unsigned NOT NULL COMMENT '使用者報名之活動',
  `uid` mediumint(8) unsigned NOT NULL COMMENT '目前登入者編號',
  `signup_date` datetime NOT NULL COMMENT '報名時間',
  `accept` enum('1','0') DEFAULT NULL COMMENT '是否錄取',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `leebuyer_signup_data_center` (
  `mid` mediumint(9) unsigned NOT NULL AUTO_INCREMENT COMMENT '模組編號',
  `col_name` varchar(100) NOT NULL DEFAULT '' COMMENT '欄位名稱',
  `col_sn` mediumint(9) unsigned NOT NULL DEFAULT '0' COMMENT '欄位編號',
  `data_name` varchar(100) NOT NULL DEFAULT '' COMMENT '資料名稱',
  `data_value` text NOT NULL COMMENT '儲存值',
  `data_sort` mediumint(9) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `col_id` varchar(100) NOT NULL COMMENT '辨識字串',
  `sort` mediumint(9) unsigned COMMENT '顯示順序',
  `update_time` datetime NOT NULL COMMENT '更新時間',
  PRIMARY KEY (`mid`,`col_name`,`col_sn`,`data_name`,`data_sort`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `leebuyer_signup_files_center` (
  `files_sn` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '檔案流水號',
  `col_name` varchar(255) NOT NULL default '' COMMENT '欄位名稱',
  `col_sn` smallint(5) unsigned NOT NULL default 0 COMMENT '欄位編號',
  `sort` smallint(5) unsigned NOT NULL default 0 COMMENT '排序',
  `kind` enum('img','file') NOT NULL default 'img' COMMENT '檔案種類',
  `file_name` varchar(255) NOT NULL default '' COMMENT '檔案名稱',
  `file_type` varchar(255) NOT NULL default '' COMMENT '檔案類型',
  `file_size` int(10) unsigned NOT NULL default 0 COMMENT '檔案大小',
  `description` text NOT NULL COMMENT '檔案說明',
  `counter` mediumint(8) unsigned NOT NULL default 0 COMMENT '下載人次',
  `original_filename` varchar(255) NOT NULL default '' COMMENT '檔案名稱',
  `hash_filename` varchar(255) NOT NULL default '' COMMENT '加密檔案名稱',
  `sub_dir` varchar(255) NOT NULL default '' COMMENT '檔案子路徑',
  `upload_date` datetime NOT NULL COMMENT '上傳時間',
  `uid` mediumint(8) unsigned NOT NULL default 0 COMMENT '上傳者',
  `tag` varchar(255) NOT NULL default '' COMMENT '註記',
  PRIMARY KEY (`files_sn`)
) ENGINE=MyISAM;