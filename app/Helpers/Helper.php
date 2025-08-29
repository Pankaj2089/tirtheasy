<?php
if (!function_exists('greetUser')) {
    function greetUser($name)
    {
        return "Hello, " . ucfirst($name) . "!";
    }
}

if (!function_exists('settings')) {
    function settings(){
        $records = DB::table('settings')->where(array('id' => 1))->first();
        return $records;
    }
}
?>