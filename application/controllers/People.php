<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by Stefan Ho.
 * User: Stefan <xiugang.he@chukou1.com>
 * Date: 2017-10-16 13:53
 */
class People extends MY_Controller {

    function __construct() {
        parent::__construct();

        $this->_layout = 'main';
    }

    public function index() {
        $this->view();
    }
}
