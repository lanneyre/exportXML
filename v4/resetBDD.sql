USE `titrecaissedesdepots`;
DELETE FROM `datac` WHERE `datac`.`id` > 3;
ALTER TABLE `datac` auto_increment = 4;
DELETE FROM `datab` WHERE `datab`.`id` > 1 ORDER BY `datab`.`id` DESC;
ALTER TABLE `datab` auto_increment = 2;
TRUNCATE TABLE `titrecaissedesdepots`.`temp`;