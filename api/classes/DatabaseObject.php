<?php

class DatabaseObject
{
    static $co;

    public static function connect(){
        if(is_null(self::$co)){
            self::$co = connectToDb();
        }
        return self::$co;
    }
}