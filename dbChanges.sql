ALTER TABLE whatsdinner.reviewed
MODIFY review int;

DELETE FROM whatsdinner.user;

ALTER TABLE `user`
ADD `email` VARCHAR(255) NOT NULL;

ALTER TABLE `user`
ADD `password` VARCHAR(45) NOT NULL;

ALTER TABLE `user` CHANGE `password` `password` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;
