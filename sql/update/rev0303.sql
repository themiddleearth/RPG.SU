ALTER TABLE `blog_post` ADD `post_time` INT UNSIGNED NOT NULL AFTER `dat` ;
ALTER TABLE `blog_comm` ADD `comm_time` INT UNSIGNED NOT NULL ;
ALTER TABLE `blog_post`
  DROP `time`,
  DROP `dat`;
ALTER TABLE `blog_comm`
  DROP `dat`,
  DROP `tim`;