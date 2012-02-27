CREATE TABLE IF NOT EXISTS `wp_slideshows` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `slideshow_name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `slideshow_name_UNIQUE` (`slideshow_name`)
);

CREATE TABLE IF NOT EXISTS `wp_slideshow_slides` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slide_name` varchar(45) DEFAULT NULL,
  `slide_url` varchar(255) DEFAULT NULL,
  `slide_image_url` varchar(255) DEFAULT NULL,
  `slide_text` text,
  `slideshow_id` int(11) DEFAULT NULL,
  `slide_no`int DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `slideshow_id` (`slideshow_id`),
  CONSTRAINT `slideshow_id` FOREIGN KEY (`slideshow_id`) REFERENCES `wp_slideshows` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
);

