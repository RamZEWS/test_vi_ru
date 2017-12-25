<?php

    /*
     * Date: 23.12.2017 13:54:56
     * Please, write your migartion code to up() method
    */

    class init1514026496 {

        public static function up() {
            global $db;
            $db->query("CREATE TABLE IF NOT EXISTS `vi_courier` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `fio` varchar(100) DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB");
            $db->query("CREATE TABLE IF NOT EXISTS `vi_region` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `name` varchar(100) DEFAULT NULL,
                `duration` int(11) DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB");

            $db->query("CREATE TABLE IF NOT EXISTS `vi_trip` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `region_id` int(11) NOT NULL,
                `courier_id` int(11) NOT NULL,
                `date_start` date NOT NULL,
                `date_end` date NOT NULL,
                PRIMARY KEY (`id`),
                FOREIGN KEY (region_id) REFERENCES vi_region(id),
                FOREIGN KEY (courier_id) REFERENCES vi_courier(id)
            ) ENGINE=InnoDB");
        }

        public static function down() {

        }
    }
    