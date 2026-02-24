CREATE TABLE IF NOT EXISTS `categories` (
                                            `id` INTEGER NOT NULL AUTO_INCREMENT UNIQUE,
                                            `name` VARCHAR(255) NOT NULL,
    `info` TEXT,
    PRIMARY KEY(`id`)
    );

CREATE TABLE IF NOT EXISTS `admins` (
                                        `id` INTEGER NOT NULL AUTO_INCREMENT UNIQUE,
                                        `username` VARCHAR(255) NOT NULL UNIQUE,
    `fullname` VARCHAR(255),
    `info` TEXT,
    `pfp` VARCHAR(255),
    `pwd_hash` VARCHAR(255) NOT NULL,
    `2fa`   VARCHAR(255),
    PRIMARY KEY(`id`)
    );


CREATE TABLE IF NOT EXISTS `articles` (
  `id` INTEGER NOT NULL AUTO_INCREMENT UNIQUE,
  `author_id` INTEGER NOT NULL,
  `category_id` INTEGER NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `content` TEXT NOT NULL,
  `keywords` TEXT NOT NULL,
  `seo_title` VARCHAR(255) NOT NULL,
  `meta_description` TEXT NOT NULL,
  `thumbnail` VARCHAR(255) NOT NULL,
    PRIMARY KEY(`id`),

    FOREIGN KEY(`category_id`) REFERENCES `categories`(`id`)
    ON UPDATE NO ACTION ON DELETE CASCADE,

    FOREIGN KEY(`author_id`) REFERENCES `admins`(`id`)
    ON UPDATE NO ACTION ON DELETE CASCADE
    );

CREATE INDEX `articles_index_1`
    ON `articles` (`title`);
CREATE INDEX `articles_index_2`
    ON `articles` (`slug`);
CREATE INDEX `categories_index_0`
    ON `categories` (`name`);