<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by Stefan Ho.
 * User: Stefan <xiugang.he@chukou1.com>
 * Date: 2017-10-06 15:43
 */

/**
 * 代码调试
 */
function p() {
    $argc = func_get_args();

    echo '<pre>';
    foreach($argc as $var) {
        print_r($var);
        echo '<br/>';
    }

    echo '</pre>';
    exit;

    return;
}

/**
 * 代码调试
 */
function pr() {
    $argc = func_get_args();
    echo '<pre>';
    foreach($argc as $var) {
        print_r($var);
        echo '<br/>';
    }
    echo '</pre>';
}

/**
 * 返回当前控制器
 * @return CI_Controller|object
 */
function ci() {
    return get_instance();
}

/**
 * 是否在开发环境
 * @return bool
 */
function in_development() {
    return ENVIRONMENT === 'development';
}

/**
 * @param $arr
 * @param $key
 * @param string $default
 * @return mixed|string
 */
function array_value($arr, $key, $default = '') {
    $keys = explode('.', $key);
    $data = $arr;
    foreach($keys as $one_key) {
        if((is_array($data) || $data instanceof ArrayAccess) && isset($data[$one_key])) {
            $data = $data[$one_key];
        } else {
            return $default;
        }
    }

    return $data;
}

/**
 * ajax json输出
 * @param mixed $data 待输出的数据
 * @param int $code 状态或错误码。0为正常或成功，其余有问题
 * @param string $msg 错误等信息
 */
function json_output($data = null, $code = 0, $msg = '') {
    header('Content-Type: text/html;charset=UTF-8');
    echo json_encode(array('code' => $code, 'msg' => $msg, 'data' => $data));
    die;
}
