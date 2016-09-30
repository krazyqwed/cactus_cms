<?php
session_start();

if (!isset($_SESSION['install_step']) && file_exists('application/config/config.php')) {
  header('Location: /');
}

if (!isset($_SESSION['install_step'])) {
/*---------------------------------------------------------------
* 1. CREATE CONFIG FILES
*---------------------------------------------------------------*/

  $configs = array(
    'application/config/config.php.default',
    'application/config/database.php.default',
    'application/config/front.php.default'
  );

  foreach ($configs as $conf) {
    copy($conf, str_replace('.default', '', $conf));
  }

  $_SESSION['install_step'] = 2;

  header('Refresh:0');
} elseif ($_SESSION['install_step'] == 2 && !isset($_POST['step'])) {
/*---------------------------------------------------------------
* 2. DB SETTINGS
*---------------------------------------------------------------*/

include('install/template.php');

} elseif ($_SESSION['install_step'] == 2 && $_POST['step'] == 'db') {
  $db_conf = file_get_contents('application/config/database.php.default');

  $conn = mysqli_connect($_POST['host'].':'.$_POST['port'], $_POST['username'], $_POST['password']);

  if (!$conn) {
    $conn->close();

    header('Refresh:0');
  }

  $db_conf = str_replace('{host}', $_POST['host'], $db_conf);
  $db_conf = str_replace('{port}', $_POST['port'], $db_conf);
  $db_conf = str_replace('{username}', $_POST['username'], $db_conf);
  $db_conf = str_replace('{password}', $_POST['password'], $db_conf);
  $db_conf = str_replace('{database}', $_POST['database'], $db_conf);

  $_SESSION['db_host'] = $_POST['host'];
  $_SESSION['db_port'] = $_POST['port'];
  $_SESSION['db_username'] = $_POST['username'];
  $_SESSION['db_password'] = $_POST['password'];
  $_SESSION['db_database'] = $_POST['database'];

  if (!$conn->select_db($_POST['database'])) {
    $conn->query('CREATE DATABASE ' . $_POST['database'] . ' CHARACTER SET utf8 COLLATE utf8_general_ci');
  }

  $conn->close();

  file_put_contents('application/config/database.php', $db_conf);

  $_SESSION['install_step'] = 3;

  header('Refresh:0');
} elseif ($_SESSION['install_step'] == 3) {
/*---------------------------------------------------------------
* 3. CREATING MAIN DB TABLES
*---------------------------------------------------------------*/
  $conn = mysqli_connect($_SESSION['db_host'].':'.$_SESSION['db_port'], $_SESSION['db_username'], $_SESSION['db_password'], $_SESSION['db_database']);

  $conn->set_charset("utf8");

  $conn->query("
    CREATE TABLE IF NOT EXISTS `blocks` (
      `block_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
      `block_folder` varchar(256) NOT NULL,
      `controller` varchar(64) NOT NULL,
      `method` varchar(64) NOT NULL,
      `name` varchar(64) NOT NULL,
      `description` text NOT NULL,
      PRIMARY KEY (`block_id`)
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
  ");

  $conn->query("
    CREATE TABLE IF NOT EXISTS `contents` (
      `content_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Tartalom ID',
      `title` varchar(128) NOT NULL,
      `content_type` tinyint(4) NOT NULL,
      `content` text NOT NULL,
      `name` varchar(64) NOT NULL,
      `description` text NOT NULL,
      `categories` text NOT NULL,
      PRIMARY KEY (`content_id`)
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
  ");

  $conn->query("
    CREATE TABLE IF NOT EXISTS `documentation` (
      `entry_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `title` varchar(256) NOT NULL,
      `content` text NOT NULL,
      `date` date NOT NULL,
      `active` tinyint(4) NOT NULL,
      PRIMARY KEY (`entry_id`)
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
  ");

  $conn->query("
    CREATE TABLE IF NOT EXISTS `files` (
      `file_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `filename` varchar(64) NOT NULL,
      `ext` varchar(5) NOT NULL,
      `mime` varchar(32) NOT NULL,
      `filesize` int(11) NOT NULL,
      `visible_name` varchar(128) NOT NULL,
      PRIMARY KEY (`file_id`)
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
  ");

  $conn->query("
    CREATE TABLE IF NOT EXISTS `images` (
      `image_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `filename` varchar(64) NOT NULL,
      `ext` varchar(5) NOT NULL,
      `filesize` int(11) NOT NULL,
      `visible_name` varchar(128) NOT NULL,
      `width` int(11) NOT NULL,
      `height` int(11) NOT NULL,
      `cropped` tinyint(1) NOT NULL,
      `crop_x1` int(11) NOT NULL,
      `crop_y1` int(11) NOT NULL,
      `crop_x2` int(11) NOT NULL,
      `crop_y2` int(11) NOT NULL,
      PRIMARY KEY (`image_id`)
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
  ");

  $conn->query("
      CREATE TABLE IF NOT EXISTS `layouts` (
      `layout_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `folder` varchar(64) NOT NULL,
      `name` varchar(64) NOT NULL,
      `description` text NOT NULL,
      PRIMARY KEY (`layout_id`)
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
  ");

  $conn->query("
    INSERT INTO `layouts` (`layout_id`, `folder`, `name`, `description`) VALUES
    (1, 'main', 'Alapértelmezett', 'Az oldal alapértelmezett elrendezése'),
    (2, 'content', 'Tartalmi oldal', 'Egyszerű tartalmi oldal a wysiwyg-es oldalakhoz.');
  ");

  $conn->query("
    CREATE TABLE IF NOT EXISTS `layout_overrides` (
      `layout_override_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `layout_id` int(10) unsigned NOT NULL,
      `url_pattern` varchar(128) NOT NULL,
      PRIMARY KEY (`layout_override_id`)
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
  ");

  $conn->query("
    CREATE TABLE IF NOT EXISTS `layout_parts` (
      `layout_part_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `layout_id` int(10) unsigned NOT NULL,
      `position` varchar(64) NOT NULL,
      `url` varchar(64) NOT NULL,
      `css_id` varchar(64) NOT NULL,
      `css_class` varchar(128) NOT NULL,
      `part_type` varchar(64) NOT NULL,
      `part_id` int(10) unsigned NOT NULL,
      `weight` int(10) unsigned NOT NULL,
      `name` varchar(64) NOT NULL,
      `description` text NOT NULL,
      `active` int(11) NOT NULL,
      PRIMARY KEY (`layout_part_id`),
      KEY `layout` (`layout_id`)
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
  ");

  $conn->query("
    INSERT INTO `layout_parts` (`layout_part_id`, `layout_id`, `position`, `url`, `css_id`, `css_class`, `part_type`, `part_id`, `weight`, `name`, `description`, `active`) VALUES
    (1, 2, 'header', '*', '', '', 'menu', 1, 0, 'Felső menü', '', 1),
    (2, 2, 'content', 'cikkek/[any]', '', '', 'block', 3, 0, 'Cikkek', '', 1),
    (3, 1, 'header', '*', '', '', 'menu', 1, 0, 'Felső menü', '', 1),
    (4, 1, 'content', '*', '', '', 'block', 3, 0, 'Cikkek', '', 1),
    (5, 2, 'content', 'teszt/tartalom', '', '', 'content', 5, 1, 'Teszt tartalom', '', 1);
  ");

  $conn->query("
    CREATE TABLE IF NOT EXISTS `layout_positions` (
      `layout_position_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `layout_id` int(10) unsigned NOT NULL,
      `key` varchar(32) NOT NULL,
      `name` varchar(32) NOT NULL,
      `width` int(11) NOT NULL,
      PRIMARY KEY (`layout_position_id`)
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
  ");

  $conn->query("
    INSERT INTO `layout_positions` (`layout_position_id`, `layout_id`, `key`, `name`, `width`) VALUES
    (1, 1, 'header', 'Fejléc', 12),
    (2, 1, 'left', 'Bal oldal', 3),
    (3, 1, 'content', 'Tartalom', 6),
    (4, 1, 'right', 'Jobb oldal', 3),
    (5, 1, 'footer', 'Lábléc', 12),
    (6, 2, 'header', 'Fejléc', 12),
    (7, 2, 'content', 'Tartalom', 12),
    (8, 2, 'footer', 'Lábléc', 12);
  ");

  $conn->query("
    CREATE TABLE IF NOT EXISTS `menu_items` (
      `menu_item_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `menu_id` int(10) unsigned NOT NULL,
      `parent_id` int(10) unsigned NOT NULL,
      `url` varchar(64) NOT NULL,
      `weight` int(11) NOT NULL,
      `label` varchar(64) NOT NULL,
      `active` tinyint(4) NOT NULL,
      `description` text NOT NULL,
      PRIMARY KEY (`menu_item_id`),
      KEY `menu_id` (`menu_id`),
      KEY `parent_id` (`parent_id`)
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
  ");

  $conn->query("
    CREATE TABLE IF NOT EXISTS `menu_items_lang` (
      `menu_item_id` int(10) unsigned NOT NULL,
      `lang` varchar(2) NOT NULL,
      `label` varchar(64) NOT NULL,
      `description` text NOT NULL,
      PRIMARY KEY (`menu_item_id`),
      KEY `menu_item_id` (`menu_item_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
  ");

  $conn->query("
    CREATE TABLE IF NOT EXISTS `menus` (
      `menu_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `css_class` varchar(128) NOT NULL,
      `template` text NOT NULL,
      `name` varchar(64) NOT NULL,
      `description` text NOT NULL,
      PRIMARY KEY (`menu_id`)
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
  ");

  $conn->query("
    CREATE TABLE IF NOT EXISTS `permissions` (
      `permission_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `table` varchar(64) NOT NULL,
      `field` varchar(64) NOT NULL,
      `controller` varchar(64) NOT NULL,
      `action` varchar(64) NOT NULL,
      `enabled` tinyint(1) NOT NULL DEFAULT '1',
      PRIMARY KEY (`permission_id`)
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
  ");

  $conn->query("
    CREATE TABLE IF NOT EXISTS `roles` (
      `role_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `key` varchar(32) NOT NULL,
      `name` varchar(64) NOT NULL,
      PRIMARY KEY (`role_id`)
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
  ");

  $conn->query("
    DROP TRIGGER IF EXISTS `delete_role`;
    DELIMITER ;;
    CREATE TRIGGER `delete_role` AFTER DELETE ON `roles` FOR EACH ROW BEGIN
      DELETE FROM role_permissions WHERE role_id = OLD.role_id;
    END;;
    DELIMITER ;
  ");

  $conn->query("
    INSERT INTO `roles` (`role_id`, `name`) VALUES
    (1, 'admin');
  ");

  $conn->query("
    CREATE TABLE IF NOT EXISTS `role_permissions` (
      `role_permission_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `role_id` int(10) unsigned NOT NULL,
      `permission_id` int(10) unsigned NOT NULL,
      `enabled` tinyint(1) NOT NULL,
      PRIMARY KEY (`role_permission_id`)
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
  ");

  $conn->query("
    CREATE TABLE IF NOT EXISTS `routes` (
      `route_id` bigint(20) NOT NULL AUTO_INCREMENT,
      `slug` varchar(64) NOT NULL,
      `controller` varchar(64) NOT NULL,
      PRIMARY KEY (`route_id`),
      KEY `slug` (`slug`)
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
  ");

  $conn->query("
    CREATE TABLE IF NOT EXISTS `routes_lang` (
      `route_id` bigint(20) NOT NULL,
      `lang` varchar(2) NOT NULL,
      `slug` varchar(64) NOT NULL,
      `controller` varchar(64) NOT NULL,
      KEY `slug` (`slug`),
      KEY `route_id` (`route_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
  ");

  $conn->query("
    CREATE TABLE IF NOT EXISTS `seo` (
      `seo_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `url_pattern` varchar(128) NOT NULL,
      `title` varchar(60) NOT NULL,
      `description` varchar(150) NOT NULL,
      PRIMARY KEY (`seo_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
  ");

  $conn->query("
    CREATE TABLE IF NOT EXISTS `settings` (
      `setting_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `slogen` text NOT NULL,
      `footer` text NOT NULL,
      `banner_image` text NOT NULL,
      `logo_image` text NOT NULL,
      `file_sandbox` text NOT NULL,
      `content_image` text NOT NULL,
      PRIMARY KEY (`setting_id`)
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
  ");

  $conn->query("
    CREATE TABLE IF NOT EXISTS `settings_lang` (
      `setting_id` int(10) unsigned NOT NULL,
      `lang` varchar(2) NOT NULL,
      `slogen` text NOT NULL,
      `footer` text NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
  ");

  $conn->query("
    CREATE TABLE IF NOT EXISTS `system_settings` (
      `system_setting_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `multi_language_enabled` tinyint(1) NOT NULL,
      `site_default_language` varchar(2) NOT NULL,
      `minify_css` tinyint(1) NOT NULL,
      `minify_js` tinyint(1) NOT NULL,
      PRIMARY KEY (`system_setting_id`)
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
  ");

  $conn->query("
    INSERT INTO `system_settings` (`system_setting_id`, `multi_language_enabled`, `site_default_language`, `minify_css`, `minify_js`) VALUES
    (1, 0, 'hu', 0, 0);
  ");

  $conn->query("
    CREATE TABLE IF NOT EXISTS `users` (
      `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `username` varchar(64) NOT NULL,
      `password` varchar(32) NOT NULL,
      `role_id` int(10) unsigned NOT NULL,
      `remember_token` varchar(32) NOT NULL,
      `last_login` datetime NOT NULL,
      PRIMARY KEY (`user_id`)
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
  ");

  $conn->query("
    DROP TRIGGER IF EXISTS `create_user`;
    DELIMITER ;;
    CREATE TRIGGER `create_user` AFTER INSERT ON `users` FOR EACH ROW BEGIN
      INSERT INTO user_settings (user_id) VALUES (NEW.user_id);
    END;;
    DELIMITER ;
  ");

  $conn->query("
    DROP TRIGGER IF EXISTS `delete_user`;
    DELIMITER ;;
    CREATE TRIGGER `delete_user` AFTER DELETE ON `users` FOR EACH ROW BEGIN
      DELETE FROM user_settings WHERE user_id = OLD.user_id;
      DELETE FROM user_permissions WHERE user_id = OLD.user_id;
    END;;
    DELIMITER ;
  ");

  $conn->query("
    INSERT INTO `users` (`user_id`, `username`, `password`, `remember_token`, `last_login`) VALUES
    (1, 'admin', '827ccb0eea8a706c4c34a16891f84e7b', 'ea48f89e52ff3890dbe8d2b90149cc99', '2014-01-16 19:08:30');
  ");

  $conn->query("
    CREATE TABLE IF NOT EXISTS `user_permissions` (
      `user_permission_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `user_id` int(10) unsigned NOT NULL,
      `permission_id` int(10) unsigned NOT NULL,
      `table` varchar(64) NOT NULL,
      `field` varchar(64) NOT NULL,
      `controller` varchar(64) NOT NULL,
      `action` varchar(64) NOT NULL,
      `enabled` tinyint(1) NOT NULL,
      PRIMARY KEY (`user_permission_id`)
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
  ");

  $conn->query("
    CREATE TABLE IF NOT EXISTS `user_settings` (
      `user_setting_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `user_id` int(10) unsigned NOT NULL,
      `full_name` varchar(128) NOT NULL,
      `email` varchar(128) NOT NULL,
      `profile_image` text NOT NULL,
      `lockscreen_enable` tinyint(1) NOT NULL,
      `lockscreen_timeout` int(10) unsigned NOT NULL,
      `lockscreen_image` text NOT NULL,
      PRIMARY KEY (`user_setting_id`)
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
  ");

  $conn->query("
    INSERT INTO `user_settings` (`user_setting_id`, `user_id`, `full_name`, `email`) VALUES
    (1, 1, 'Kocsis Dávid', 'krazyqwed@gmail.com');
  ");
}

