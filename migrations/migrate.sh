#!/usr/bin/env php
<?php

$_SERVER['DOCUMENT_ROOT'] = __DIR__ . '/../';

echo "\n *** MIGRATION DATABASE TOOL **** \n\n";

require_once('../parts/init.php');

global $db;

$db->query("CREATE TABLE IF NOT EXISTS `deploy_migrations` (
    `ID` int(11) NOT NULL AUTO_INCREMENT,
    `NAME` varchar(50) DEFAULT NULL,
    `MIGRATION` int(12) NOT NULL,
    PRIMARY KEY (`ID`)
) ENGINE=InnoDB");

if (!isset($argv[1])) {
    echo "please use params: 'create', 'migrate' or 'up', 'undeploy', 'downgrate' or 'down'\n\n";
    exit();
}

switch ($argv[1]) {
    case 'create':
        echo "CREATE DATABASE MIGRATION......\n\n";
        $name = false;
        if (isset($argv[2])) {
            $name = preg_replace('/[^A-Za-z_]/', '', $argv[2]);
        }
        if ($name) {
            $time = time();
            $date = date('d.m.Y H:i:s');

    $new_migration = '<?php

    /*
     * Date: '.$date.'
     * Please, write your migartion code to up() method
    */

    class '.$name.$time.' {

        public static function up() {

        }

        public static function down() {

        }
    }
    ';
            $f = fopen('files/' . $name . $time .'.php', 'w+');
            fputs($f, $new_migration);
            fclose($f);
            echo "MIGRATION CREATE SUCCESS files/$name$time.php\n\n";
        } else {
            echo "please, write migration name (A-z, _)\n\n";
            exit();
        }
    break;

    case 'undeploy':
        if (isset($argv[2])) {
            $name = preg_replace('/[^A-Za-z_]/', '', $argv[2]);
            $db->query("DELETE FROM `deploy_migrations` WHERE NAME LIKE '".$name."%'");
        } else {
            echo "please, write migration name (A-z, _)\n\n";
            exit();
        }
    break;

    case 'deploy':
        if (isset($argv[2])) {
            $name = preg_replace('/[^A-Za-z_\d]/', '', $argv[2]);
            $t = preg_replace('/[^0-9]/', '', $name);
            $db->query("INSERT INTO `deploy_migrations` (`MIGRATION`, `NAME`) VALUES ($t, '$name')") or die(mysql_error());
        } else {
            echo "please, write migration name (A-z, _)\n\n";
            exit();
        }
    break;

    case 'down':
    case 'downgrate':
        if (isset($argv[2])) {
            $name = preg_replace('/[^A-Za-z_]/', '', $argv[2]);
            $res = $db->query("SELECT NAME FROM `deploy_migrations` WHERE NAME LIKE '".$name."%'");
            if ($f = $res->fetch_assoc()) {
                $file = $f['NAME'] . '.php';
                $db->query("START TRANSACTION");
                echo "$file...";
                require('files/'.$file);
                $className = str_replace('.php', '', $file);
                try {
                    $ob = new $className();
                    $ob->down();
                    $result = $db->query("DELETE FROM `deploy_migrations` WHERE NAME = '$className'");
                    if (!$result) {
                        throw new Exception(mysql_error());
                    }
                    $db->query("COMMIT");
                } catch ( Exception $e ) {
                    $db->query("ROLLBACK");
                    $db->query("SET AUTOCOMMIT=1");
                    echo "!!! ERROR DOWNGRATE: $className\n\n";
                    exit();
                }
                echo "ok\n";
            } else {
                echo "Not found ".$name."\n\n";
            }
        } else {
            echo "please, write migration name (A-z, _)\n\n";
            exit();
        }
    break;

    case 'migrate':
    case 'up':
        echo "START DATABASE MIGRATION......\n\n";

        $res = $db->query("SELECT * FROM `deploy_migrations`");
        $skipMigrations = Array();
        while($row = $res->fetch_assoc()) {
            $skipMigrations[] = $row['MIGRATION'];
        }

        $migrations = Array();
        $dir = opendir('files');
        while ($file = readdir($dir)) {
            if ( $file != "." && $file != ".." && !is_dir( $dir . $file ) && strpos($file,'.php') > 0) {
                $time = preg_replace('/[^0-9]/', '', $file);
                if (!in_array($time, $skipMigrations)) {
                    echo "Find new migration $file...\n";
                    $migrations[(int) $time] = $file;
                }
            }
        }

        if (count($migrations) == 0) {
            echo "Nothing to migrate.\n\n";
            exit();
        }

        echo "\nMIGRATE....\n\n";
        $db->query("SET AUTOCOMMIT=0");
        if (ksort($migrations)) {
            foreach($migrations as $t => $file) {
                $db->query("START TRANSACTION");
                echo "$file...";
                require('files/'.$file);
                $className = str_replace('.php', '', $file);
                try {
                    $ob = new $className();
                    $ob->up();
                    $result = $db->query("INSERT INTO `deploy_migrations` (`MIGRATION`, `NAME`) VALUES ($t, '$className')");
                    if (!$result) {
                        throw new Exception(mysql_error());
                    }
                    $db->query("COMMIT");
                } catch ( Exception $e ) {
                    $db->query("ROLLBACK");
                    $db->query("SET AUTOCOMMIT=1");
                    echo $e->getMessage();
                    echo "!!! ERROR MIGRATE: $className\n\n";
                    exit();
                }
                echo "ok\n";
            }
        }
        $db->query("SET AUTOCOMMIT=1");

        echo " SUCCESS......\n\n";

    break;
    default:
        echo "please use params: 'create', 'migrate' or 'up', 'undeploy', 'downgrate' or 'down'\n\n";
}