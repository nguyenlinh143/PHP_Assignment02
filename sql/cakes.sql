CREATE DATABASE cakes;
GRANT USAGE ON *.* TO 'appuser'@'localhost' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON cakes.* TO 'appuser'@'localhost';
FLUSH PRIVILEGES;

USE cakes;

CREATE TABLE IF NOT EXISTS `cakes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(100),
  `order_date` datetime,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

INSERT INTO `cakes` (`name`, `description`, `price`, `image`, `order_date`) VALUES
('Chocolate Cake', 'A delicious chocolate cake', 12.99, 'chocolate_cake.jpg', NOW()),
('Strawberry Cheesecake', 'A creamy cheesecake topped with fresh strawberries', 18.99, 'strawberry_cheesecake.jpg', NOW()),
('Carrot Cake', 'A classic carrot cake with cream cheese frosting', 15.99, 'carrot_cake.jpg', NOW());
