<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by Stefan Ho.
 * User: Stefan <xiugang.he@chukou1.com>
 * Date: 2017-10-16 13:53
 */
class People extends MY_Controller {

    private $_people_model_name = 'People_model';
    /** @var People_model $_people_model */
    private $_people_model = '';

    function __construct() {
        parent::__construct();
        $this->load->model($this->_people_model_name);
        $model_name = $this->_people_model_name;
        $this->_people_model = $this->$model_name;

        $this->_layout = 'main';
    }

    public function index() {
        $this->view();
    }

    public function add() {
        $this->view();
    }

    public function get_all() {
        $this->load->library('pagination');

        $search = $this->input->get('q');
        $by = 'id';
        $sort = 'DESC';
        $page = $this->input->get('page');

        $input = array(
            'first_name_like' => $search,
            'last_name_like'  => $search,
            'by'              => $by,
            'sort'            => $sort,
            'page'            => max($page, 1),
            'page_size'       => 10,
        );

        $result = $this->_people_model->get_all_people($input);

        $config = array(
            'total_rows' => $result['count'],
        );
        $this->pagination->initialize($config);

        $pager = $this->pagination->get_links();

        $output = array(
            'data'  => $result['data'],
            'pages' => $pager,
        );

        json_output($output);
    }

    public function get() {
        $id = $this->input->get('id');

        $output = $this->_people_model->get_by_id($id);

        json_output($output);
    }

    public function insert() {
        $input = $this->input->post();
        $data = array(
            'first_name' => $input['firstname'],
            'last_name'  => $input['lastname'],
            'gender'     => $input['gender'],
            'email'      => $input['email'],
        );
        $result = $this->_people_model->insert($data);

        json_output(null, $result ? 0 : 1);
    }

    public function update($id) {
        $input = $this->input->post();
        $data = array(
            'first_name' => $input['firstname'],
            'last_name'  => $input['lastname'],
            'gender'     => $input['gender'],
            'email'      => $input['email'],
        );
        $result = $this->_people_model->update($data, array('id' => $id));

        json_output(null, $result !== false ? 0 : 1);
    }

    public function delete() {
        $id = $this->input->post('id');
        if($id) {
            $result = $this->_people_model->delete(array('id' => $id));
            if($result) {
                json_output(null, 0, 'Success Delete People');
            } else {
                json_output(null, 1, 'Failed Delete People');
            }
        } else {
            json_output(null, 1, 'Parameters error');
        }
    }
}
