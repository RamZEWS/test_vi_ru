<?php

    /*
     * Date: 25.12.2017 14:39:30
     * Please, write your migartion code to up() method
    */

    class fill_data1514201970 {

        public static function up() {
            require_once($_SERVER['DOCUMENT_ROOT'] . '/models/Courier.php');
            require_once($_SERVER['DOCUMENT_ROOT'] . '/models/Region.php');
            require_once($_SERVER['DOCUMENT_ROOT'] . '/models/Trip.php');

            $couriers = [
                'Иванов Иван Иванович', 
                'Лукоянов Роман Сергеевич', 
                'Петров Иван Петрович', 
                'Фамилия Имя Отчество',
                'Сергеев Сергей Сергеевич',
                'Анастасия Петровна Штрудель',
                'Кукуся Кусь Кукусевич',
                'Сталин Иосиф Виссарионович',
                'Ульянов Владимир Ильич',
                'Райво Хаапасало'
            ];
            $courier_ids = [];
            foreach($couriers as $fio) {
                $courier_ids[] = Courier::insert(['fio' => $fio]);
            }

            $regions = [
                ['name' => 'Санкт-Петербург', 'duration' => 4],
                ['name' => 'Уфа', 'duration' => 6],
                ['name' => 'Нижний Новгород', 'duration' => 2],
                ['name' => 'Владимир', 'duration' => 1],
                ['name' => 'Кострома', 'duration' => 3],
                ['name' => 'Екатеринбург', 'duration' => 10],
                ['name' => 'Ковров', 'duration' => 5],
                ['name' => 'Воронеж', 'duration' => 2],
                ['name' => 'Самара', 'duration' => 7],
                ['name' => 'Астрахань', 'duration' => 14],
            ];
            $region_ids = [];
            foreach($regions as $r) {
                $rid = Region::insert($r);
                if($rid) $region_ids[$rid] = $r['duration'];
            }

            $stop_date = '2017-12-01';

            if($courier_ids && $region_ids) {
                foreach($courier_ids as $cid) {
                    $start = '2015-06-01';
                    while($start < $stop_date){
                        $rid = array_rand($region_ids);
                        $d = $region_ids[$rid];
                        $end = date('Y-m-d', strtotime('+ '.$d.' days', strtotime($start)));
                        Trip::insert([
                            'courier_id' => $cid,
                            'region_id' => $rid,
                            'date_start' => $start,
                            'date_end' => $end
                        ]);
                        $start = date('Y-m-d', strtotime('+ 1 day', strtotime($end)));
                    }
                }
            }

        }

        public static function down() {

        }
    }
    