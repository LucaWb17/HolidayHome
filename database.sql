-- Create the users table
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create the bookings table
CREATE TABLE `bookings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `check_in` date NOT NULL,
  `check_out` date NOT NULL,
  `guests` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default users
-- Note: The passwords are 'admin' and 'user' respectively.
INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`) VALUES
(1, 'Admin', 'admin@villaparadiso.com', '$2y$10$N.xT3v3j/4yG7v.D.Ab2a.I/2G.L1G.S1E.B2A.O3F.G4H.I5J.K6', 'admin'),
(2, 'Test User', 'user@test.com', '$2y$10$m.A.i.L.f.O.r.a.U.s.E.r.P.a.s.s.w.o.r.d.1.2.3.4.5.6', 'user');

-- Set auto-increment start value
ALTER TABLE `users` AUTO_INCREMENT = 3;
