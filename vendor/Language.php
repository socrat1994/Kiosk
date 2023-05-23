<?php
namespace General;

class Language
{
    public static $dic;

    public static function get()
    {
        if(Session::get('lang'))
        {
            return Session::get('lang');
        }
        $env = EnvReader::getInstance();
        return $env->get('LANG');
    }

    public static function set($lang)
    {
        Session::set('lang', $lang) ;
    }

    public static function t($toTarnslate, $file = false)
    {
        $file = $file??get_called_class();
        if(!isset(Self::$dec[$file])) {
           Self::dictionary($file);
        }
        return Self::$dic[$file][$toTarnslate]??$toTarnslate;
    }

    public static function dictionary($file)
    {
        $lang = Self::get();
        Self::$dic[$file] = include(__DIR__ . '/../languages/' . $lang . '/' . $file . '_dictionary.php');
        return Self::$dic[$file];
    }

}
?>