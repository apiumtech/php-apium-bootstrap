<?php


class ConfUrlUtils
{

    public static function extractInstallDir($cwd)
    {

        $matches = array();
        if ( preg_match('%^(.+)(public_html|www|test|build|processes|backoffice|consumers).*$%',$cwd,$matches) )
        {
            return $matches[1];
        }
        else {
            return ConfUrlUtils::treatInstallDir(dirname($cwd));
        }
    }

    public static function treatInstallDir($originalPath)
    {
        return $originalPath."/";
    }

    public static function detectBaseUrl()
    {
        return ($_SERVER["HTTP_HOST"]!=null) ? $_SERVER["HTTP_HOST"] : "http://localhost";
    }

    public static function getDatabaseConfFile($base)
    {
        $base = (substr($base, 0, 7) =="http://") ? substr($base, 7) : $base;

        if (strpos($base,'localhost')>0) return '/database.dev.conf.php';

        $databaseConfigFile = null;
        switch($base) {
            case "localhost":
                $databaseConfigFile = '/database.dev.conf.php';
                break;
            case "test.yoursite.com":
                $databaseConfigFile = '/database.pre.conf.php';
                break;
            case "backoffice.yoursite.com":
                $databaseConfigFile = '/database.prod.conf.php';
                break;
            default:
                $databaseConfigFile = '/database.prod.conf.php';
        }
        return $databaseConfigFile;
    }

}