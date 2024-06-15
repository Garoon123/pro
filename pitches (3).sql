CREATE TABLE `pitches` (
  `pitch_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `location_id` int(11) DEFAULT NULL,
  `xaafada_id` int(11) DEFAULT NULL,
  `type` enum('5','7','11') NOT NULL,
  `availability_status` tinyint(1) NOT NULL DEFAULT 1,
  `price_per_hour` decimal(10,2) NOT NULL,
  `rating` decimal(3,2) DEFAULT NULL CHECK (`rating` >= 0 and `rating` <= 5),
  `image` varchar(100) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `aproved` tinyint(1) DEFAULT NULL,
  `phone_number` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
ALTER TABLE `pitches`
  ADD PRIMARY KEY (`pitch_id`),
  ADD KEY `location_id` (`location_id`),
  ADD KEY `xaafada_id` (`xaafada_id`),
  ADD KEY `fk_user_id` (`user_id`);
ALTER TABLE `pitches`
  MODIFY `pitch_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
ALTER TABLE `pitches`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `pitches_ibfk_1` FOREIGN KEY (`location_id`) REFERENCES `locations` (`location_id`),
  ADD CONSTRAINT `pitches_ibfk_2` FOREIGN KEY (`xaafada_id`) REFERENCES `xaafada` (`id`);
COMMIT;