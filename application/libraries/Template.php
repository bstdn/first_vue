<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by Stefan Ho.
 * User: Stefan <xiugang.he@chukou1.com>
 * Date: 2017-10-08 16:30
 */
class Template {

    private static $asset;

    /**
     * @param $file_path
     * @param string $source
     */
    public static function add_css($file_path, $source = 'local') {
        if($source == 'remote') {
            $url = $file_path;
        } else {
            $url = base_url() . $file_path;
        }

        self::$asset['header']['css'][] = $url;
    }

    /**
     * 引入css文件
     */
    public static function load_css() {
        if(!isset(self::$asset['header']['css']) || count(self::$asset['header']['css']) < 1) {
            return;
        }
        foreach(self::$asset['header']['css'] as $css) {
            echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"{$css}\" />" . PHP_EOL;
        }
    }

    /**
     * @param $file_path
     * @param string $location
     * @param string $source
     */
    public static function add_js($file_path, $location = 'header', $source = 'local') {
        if($source == 'remote') {
            $url = $file_path;
        } else {
            $url = base_url() . $file_path;
        }

        self::$asset[$location]['js'][] = $url;
    }

    /**
     * 引入js文件
     * @param string $location
     */
    public static function load_js($location = 'header') {
        if(!isset(self::$asset[$location]['js']) || count(self::$asset[$location]['js']) < 1) {
            return;
        }
        foreach(self::$asset[$location]['js'] as $js) {
            echo "<script src=\"{$js}\"></script>" . PHP_EOL;
        }
    }
}
