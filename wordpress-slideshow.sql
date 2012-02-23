CREATE TABLE IF NOT EXISTS`wp_slideshows` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `slideshow_name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `slideshow_name_UNIQUE` (`slideshow_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS`wp_slideshow_slides` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slide_name` varchar(45) DEFAULT NULL,
  `slide_url` varchar(255) DEFAULT NULL,
  `slide_image_url` varchar(255) DEFAULT NULL,
  `slide_text` text,
  `slideshow_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `slideshow_id` (`slideshow_id`),
  CONSTRAINT `slideshow_id` FOREIGN KEY (`slideshow_id`) REFERENCES `wp_slideshows` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin;

