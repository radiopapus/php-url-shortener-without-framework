CREATE TABLE `url` (
    `id` SERIAL,
    `originalUrl` text CHARACTER SET utf8 NOT NULL,
    `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

ALTER TABLE url AUTO_INCREMENT=100000000;