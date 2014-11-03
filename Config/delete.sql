
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- pack
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `pack`;

-- ---------------------------------------------------------------------
-- product_pack
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `product_pack`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
