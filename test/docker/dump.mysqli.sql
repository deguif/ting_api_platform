CREATE TABLE `user` (
    `firstname` VARCHAR(25),
    `lastname` VARCHAR(25),
    PRIMARY KEY (`firstname`)
);

CREATE TABLE `filter` (
    `name` VARCHAR(25),
    `value` VARCHAR(25),
    `value_partial` VARCHAR(25),
    `value_start` VARCHAR(25),
    `value_end` VARCHAR(25),
    `value_word_start` VARCHAR(25),
    `value_ipartial` VARCHAR(25),
    PRIMARY KEY (`name`)
);
