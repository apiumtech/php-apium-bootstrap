<?php

require_once dirname(__FILE__) . '/../libs/utils/ConfUrlUtils.php';
require_once dirname(__FILE__) . '/../www/utils/StringUtils.class.php';

error_reporting(E_ERROR ^ E_DEPRECATED);
ini_set('display_errors', 'On');

date_default_timezone_set("GMT");

$cwd = getcwd();

$base = ConfUrlUtils::detectBaseUrl();

define('INSTALL_BASE', ConfUrlUtils::extractInstallDir($cwd));

define('PORTAL_BASE', INSTALL_BASE . 'www/');

define('WS_BASE', INSTALL_BASE . '/ws/');

define('API_BASE', INSTALL_BASE . 'services/');

define('CONFIG_BASE', INSTALL_BASE . 'config/');

//include_paths

set_include_path(get_include_path() . PATH_SEPARATOR . CONFIG_BASE .
    PATH_SEPARATOR . PORTAL_BASE .
    PATH_SEPARATOR . API_BASE .
    PATH_SEPARATOR . PORTAL_BASE . 'utils/' .
    PATH_SEPARATOR . WS_BASE .
    PATH_SEPARATOR . PORTAL_BASE . 'utils/' . 'Exceptions/');



$databaseConfFile = ConfUrlUtils::getDatabaseConfFile($base);

include_once dirname(__FILE__) . $databaseConfFile;

function isSensitiveFS()
{
    if (file_exists(strtoupper(__FILE__))&&file_exists(strtolower(__FILE__))) return false;
    return true;
}
function _for(Closure $pathResolver)
{
    return function ($class) use ($pathResolver) {
        $class = $pathResolver($class);
        if (!file_exists($class)) return false;
        include_once($class);
        return true;
    };
}

function _model(Closure $next = NULL)
{
    if ($next) $next();
    return function ($class) {
        return dirname(__FILE__) . '/../' . str_replace('\\', '/', $class) . '.php';
    };
}

function _services(Closure $next = NULL)
{
    if ($next) $next();
    return function ($class) {
        return dirname(__FILE__) . '/../' . str_replace('\\', '/', StringUtils::changeLastPart($class, "\\\\", function ($name) {
            return lcfirst($name);
        })) . '.php';
    };
}

function _documents(Closure $next = NULL)
{
    if ($next) $next();
    return function ($class) {
        return dirname(__FILE__) . '/../' . str_replace('\\', '/', $class) . '.class.php';
    };
}

function _doctrine(Closure $next = NULL)
{
    if ($next) $next();
    return function ($class) {
        return dirname(__FILE__) . '/..//libs/doctrine/' . str_replace('\\', '/', $class) . '.php';
    };
}

function _registerAutoloader(Closure $autoloader)
{
    spl_autoload_register($autoloader);
}

function _andFor(Closure $extractor)
{
    _registerAutoloader(_for($extractor));
}
function _andIfSensitiveFS(Closure $extractor)
{
    if (!isSensitiveFS()) return; //php native bug in include_once in MAC for file system insensitivity
    _registerAutoloader(_for($extractor));
}

_registerAutoloader(
    _for(
        _model(
            _andFor(
                _doctrine(
                    _andFor(
                        _documents(
                            _andIfSensitiveFS(
                                _services()
                            )
                        )
                    )
                )
            )
        )
    )
);




?>