<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by Stefan Ho.
 * User: Stefan <xiugang.he@chukou1.com>
 * Date: 2017-10-06 15:38
 */

/**
 * Class People_model
 * @method MY_Model where($key, $value = null, $escape = null)
 * @method MY_Model count_all_results
 * @method MY_Model get_last_query
 * @method MY_Model like($field, $match = '', $side = 'both', $escape = null)
 * @method MY_Model or_like($field, $match = '', $side = 'both', $escape = null)
 * @method MY_Model to_query($limit = null, $page = null, $key = null)
 * @method MY_Model order_by($orderby, $direction = '', $escape = null)
 */
class People_model extends MY_Model {

    protected $_table_name  = 'people';
    protected $_primary_key = 'id';

    function __construct() {
        parent::__construct();
    }

    public function get_by_id($id) {
        return $this->where($this->_primary_key, $id)->get();
    }

    public function get_all_people($input = array()) {
        $result = array(
            'count' => 0,
            'data'  => array(),
        );
        $result['count'] = $this->_build_count($input);
        if($result['count']) {
            $result['data'] = $this->_build_query($input);
        }

        return $result;
    }

    protected function _build_query($input) {
        $this->_build_base($input);

        if($by = array_value($input, 'by')) {
            $sort = array_value($input, 'sort', 'ASC');
            $this->order_by($by . ' ' . $sort);
        }

        return $this->to_query($input['page_size'], $input['page']);
    }

    protected function _build_count($input = array()) {
        $this->_build_base($input);

        return $this->count_all_results();
    }

    protected function _build_base($input = array()) {
        if($first_name_like = array_value($input, 'first_name_like')) {
            $this->like('first_name', $first_name_like);
        }
        if($last_name_like = array_value($input, 'last_name_like')) {
            $this->or_like('last_name', $last_name_like);
        }
    }
}
