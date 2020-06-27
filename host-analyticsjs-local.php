<?php
/**
 * @formatter:off
 * Plugin Name: CAOS
 * Plugin URI: https://daan.dev/wordpress-plugins/caos/
 * Description: Completely optimize Google Analytics for your Wordpress Website - host analytics.js/gtag.js/ga.js locally, bypass Ad Blockers in Stealth Mode, capture outbound links, place tracking code in footer, and much more!
 * Version: 3.6.0
 * Author: Daan van den Bergh
 * Author URI: https://daan.dev
 * License: GPL2v2 or later
 * Text Domain: host-analyticsjs-local
 * @formatter:on
 */

defined('ABSPATH') || exit;

/**
 * Define Constants
 */
define('CAOS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CAOS_PLUGIN_FILE', __FILE__);
define('CAOS_STATIC_VERSION', '3.4.1');

/**
 * Takes care of loading classes on demand.
 *
 * @param $class
 *
 * @return mixed|void
 */
function caos_autoload($class)
{
    $path = explode('_', $class);

    if ($path[0] != 'CAOS') {
        return;
    }

    return include CAOS_PLUGIN_DIR . 'includes/' . woosh_load_class($class);
}

/**
 * For speed optimization, only load this function if another WoOSH! plugin isn't already loading it.
 */
if (!function_exists('woosh_load_class')) {
    function woosh_load_class($class)
    {
        $path     = explode('_', $class);
        $filename = '';

        if (count($path) == 1) {
            $filename = 'class-' . strtolower(str_replace('_', '-', $class)) . '.php';
        } elseif (count($path) == 2) {
            array_shift($path);
            $filename = 'class-' . strtolower($path[0]) . '.php';
        } else {
            array_shift($path);
            end($path);
            $i = 0;

            while ($i < key($path)) {
                $filename .= strtolower($path[$i]) . '/';
                $i++;
            }

            $pieces = preg_split('/(?=[A-Z])/', lcfirst($path[$i]));

            $filename .= 'class-' . strtolower(implode('-', $pieces)) . '.php';
        }

        return $filename;
    }
}

spl_autoload_register('caos_autoload');

/**
 * All systems GO!!!
 *
 * @return CAOS
 */
function caos_init()
{
    static $caos = null;

    if ($caos === null) {
        $caos = new CAOS();
    }

    return $caos;
}

caos_init();
