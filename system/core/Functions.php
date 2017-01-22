<?php defined('DIRECT') OR exit('No direct script access allowed');
/**
 * Main Functions
 *
 * Turan Karatuğ - <tkaratug@hotmail.com.tr>
 */


/**
 * Get current language
 * @return  string
 */
if( ! function_exists('get_lang') ) {
    function get_lang()
    {
		$titan	= Loader::getInstance();
        $config = $titan->config('language');

        if( ! isset($_SESSION) ) {
            session_start();
        }

        if( ! isset($_SESSION[md5('lang')]) ) {
            return $_SESSION[md5('lang')] = $config['default_language'];
        } else {
            return $_SESSION[md5('lang')];
        }
    }
}

/**
 * Set language
 * @param   $lang   string
 * @return  void
 */
if( ! function_exists('set_lang') ) {
    function set_lang($lang = '')
    {
        $titan	= Loader::getInstance();
        $config = $titan->config('language');

        if( ! is_string($lang) ) {
            return false;
        }

        if( empty($lang) ) {
            $lang = $config['default_language'];
        }

        if( ! isset($_SESSION) ) {
            session_start();
        }

        $_SESSION[md5('lang')] = $lang;
    }
}

/**
 * Get string with current language
 * @param   $file   string
 * @param   $key    string
 * @param   $change string
 * @return  string
 */
if ( ! function_exists('lang') ) {
    function lang($file = '', $key = '', $change = '')
    {
        global $lang;

        $titan	= Loader::getInstance();
        $config = $titan->config('language');

        if( ! is_string($file) || ! is_string($key) ) {
            return false;
        }

        $appLangDir = APP_DIR . 'languages/' . strtolower($config['languages'][get_lang()]) . '/' . strtolower($file) . '.php';
        $sysLangDir = SYSTEM_DIR . 'languages/' . strtolower($config['languages'][get_lang()]) . '/' . strtolower($file) . '.php';

        if( file_exists($appLangDir) ) {
            require_once $appLangDir;
        } elseif ( file_exists($sysLangDir) ) {
            require_once $sysLangDir;
        }

        $zone = strtolower($file);

        if( array_key_exists($key, $lang[$zone]) ) {
            $str = $lang[$zone][$key];

            // Change special words
            if( ! is_array($change) ) {
                if( ! empty($change) ) {
                    return str_replace('%s', $change, $str);
                } else {
                    return $str;
                }
            } else {
                if( ! empty($change) ) {
                    $keys = [];
                    $vals = [];

                    foreach($change as $key => $value) {
                        $keys[] = $key;
                        $vals[] = $value;
                    }

                    return str_replace($keys, $vals, $str);
                } else {
                    return $str;
                }
            }

        } else {
            return false;
        }
    }
}

/**
 * Include View File
 * @param $file string
 * @param $vars array
 * @param $cache boolean
 * @return void
 */
if( ! function_exists('view') ) {
    function view($file, $vars, $cache = false)
    {
        $titan = Loader::getInstance();
        $titan->plugin('template');

        echo $titan->template->render($file, $vars, $cache);
    }
}