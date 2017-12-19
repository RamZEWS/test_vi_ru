<?
class App {
    function includeBlock($name) {
        if($name = trim($name)) {
            require_once($_SERVER["DOCUMENT_ROOT"]."/parts/".$name.".php");
        }
    }
}