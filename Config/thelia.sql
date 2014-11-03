
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- pack
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `pack`;

CREATE TABLE `pack`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `is_active` TINYINT(1) NOT NULL,
    `product_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `FI_product_is_pack` (`product_id`),
    CONSTRAINT `fk_product_is_pack`
        FOREIGN KEY (`product_id`)
        REFERENCES `product` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- product_pack
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `product_pack`;

CREATE TABLE `product_pack`
(
    `pack_id` INTEGER NOT NULL,
    `product_id` INTEGER NOT NULL,
    PRIMARY KEY (`pack_id`,`product_id`),
    INDEX `FI_product_in_pack` (`product_id`),
    CONSTRAINT `fk_productpack`
        FOREIGN KEY (`pack_id`)
        REFERENCES `pack` (`id`),
    CONSTRAINT `fk_product_in_pack`
        FOREIGN KEY (`product_id`)
        REFERENCES `product` (`id`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
