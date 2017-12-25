<?
require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/Ajax.php');

$action = isset($_REQUEST["action"]) ? $_REQUEST["action"] : false;
if($action) {
    $result = Ajax::$action();
    /* Очищаем буфер вывода и отключаем запись в него */
    ob_end_clean();
    /* Включаем буфер только для функции json_encode */
    ob_start();
    /* Отправляем json */
    echo json_encode($result);
    /* Отправляем в буфер вывода */
    ob_end_flush();
    /* Прекращаем выполнение */
    exit();
}