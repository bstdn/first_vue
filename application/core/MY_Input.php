<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by Stefan Ho.
 * User: Stefan <xiugang.he@chukou1.com>
 * Date: 2017-10-13 10:20
 */
class MY_Input extends CI_Input {

    public function is_post() {
        return $this->method() === 'post';
    }

    public function is_get() {
        return $this->method() === 'get';
    }
}
