<?php
/**
 * A simple router for PHP students.
 *
 * @author Stéphane Bouvry<jacksay@jacksay.com>
 */
class JckRouter {

    protected static $INSTANCE;
    protected static $REAL_DOC_ROOT;
    protected static $REAL_BASE_URL;
    protected static $CONFIGURE;
    protected static $ROUTES = array();

    private function __construct() {
        
    }

    public static function getConf($docRoot = false, $relativeDocumentRoot = false) {
        
        if (self::$CONFIGURE === null) {
            if ($docRoot === false) {
                $docRoot = dirname($_SERVER['SCRIPT_NAME']);
            }
            if( $relativeDocumentRoot === false ){
                $relativeDocumentRoot = str_replace($_SERVER['DOCUMENT_ROOT'], '', $docRoot);
            }
            self::$CONFIGURE = array(
                'REAL_DOCROOT' => $docRoot,
                'RELATIVE_DOCROOT' => $relativeDocumentRoot
            );
        }
        return self::$CONFIGURE;
    }

    public static function getBasePath() {
        $conf = self::getConf();
        return $conf['REAL_DOCROOT'];
    }

    public static function initWithRootDir($directoryPath, $relativeDocRoot = false) {
        self::$CONFIGURE = null;
        self::getConf($directoryPath, $relativeDocRoot);
    }

    /**
     * Add route.
     * 
     * @param string $name An ASCII route name (use for make URL)
     * @param string $pattern Template of URL
     * @param boolean $strict Allow URL variables (Know as GET)
     * @param array $params Optionnal parameters (Not use now)
     */
    public static function addRoute($name, $pattern, $strict = false, $params = array()) {
        self::$ROUTES[$name] = array(
            'name' => $name,
            'url' => $pattern,
            'strict' => $strict,
            'params' => $params
        );
    }
    
    /**
     * Return an URL using $name and - if required - parameters
     * 
     * @param type $routeName
     * @param type $params
     * @return type
     * @throws Exception
     */
    public static function getUrl($routeName, $params = array()) {
        if (array_key_exists($routeName, self::$ROUTES)) {
            $route = self::$ROUTES[$routeName];
            $url = $route['url'];
            $config = self::getConf();
            if (preg_match_all("`\{([a-zA-Z0-9-_]+)\}`", $route['url'], $matches)) {
                foreach ($matches[1] as $match) {
                    if (array_key_exists($match, $params) === true) {
                        $url = preg_replace("`(\{" . $match . "\})`", $params[$match], $url);
                    } else {
                        throw new Exception("La route '$routeName' a un paramètre '$match' requis !", 500, null);
                    }
                }
            }
            return $config['RELATIVE_DOCROOT'] . $url;
        } else {
            throw new Exception("Route '$routeName' unknow.");
        }
    }
    
    private static function debug($mixedvar) {
        if (true) {
            echo "<div style=\"border: thin red solid; padding: .4em;margin: .25em;\">";
            var_dump($mixedvar);
            echo "</div>";
        }
    }
    
    /**
     * Return route for given URI.
     * 
     * @param type $requestUri
     * @return array|null
     */
    public static function getRoute($requestUri = false) {
        $config = self::getConf();
        if ($requestUri === false) {
            $requestUri = $_SERVER['REQUEST_URI'];
            $reg = '`^' . str_replace('/', '\/', $config['RELATIVE_DOCROOT']) . '`';
            $url = preg_replace($reg, '', $requestUri);
        } else {
            $url = $requestUri;
        }

        foreach (self::$ROUTES as $route) {
            $regEx = $route['url'];
            $paramsKey = array();
            $matches = array();
            if (preg_match_all("`\{([a-zA-Z0-9-_]+)\}`", $route['url'], $matches)) {
                $regRoute = $route['url'];
                foreach ($matches[1] as $match) {
                    $paramsKey[] = $match;
                }
                $regEx = preg_replace("`(\{[a-zA-Z0-9-_]+\})`", "([\w-_]*)", $regRoute);
            }
            $regEx = '`^' . $regEx . ($route['strict'] ? '' : '(\?.*)?') . '$`';
            if (preg_match($regEx, $url, $matches)) {
                $paramsValue = array();
                for ($i = 1; $i < count($matches); $i++) {
                    $paramsValue[] = $matches[$i];
                }
                if (count($paramsKey) > 0) {
                    $route['data'] = array_combine($paramsKey, $paramsValue);
                } else {
                    $route['data'] = array();
                }
                return $route;
            }
        }
        return null;
    }
}