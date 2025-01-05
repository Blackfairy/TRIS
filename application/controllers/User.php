<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->library('session');
        /*cache control*/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');

        if (get_settings('allow_researcher') != 1){
            redirect(site_url('home'), 'refresh');
        }
    }

    public function index() {
        if ($this->session->userdata('user_login') == true) {
            $this->manuscripts();
        }else {
            redirect(site_url('home/login'), 'refresh');
        }
    }

    public function manuscripts() {
        if ($this->session->userdata('user_login') != true) {
            redirect(site_url('home/login'), 'refresh');
        }
        $page_data['selected_category_id']   = isset($_GET['category_id']) ? $_GET['category_id'] : "all";
        $page_data['selected_researcher_id'] = $this->session->userdata('user_id');
        $page_data['selected_price']         = isset($_GET['price']) ? $_GET['price'] : "all";
        $page_data['selected_status']        = isset($_GET['status']) ? $_GET['status'] : "all";
        $page_data['manuscripts']                = $this->crud_model->filter_manuscript_for_backend($page_data['selected_category_id'], $page_data['selected_researcher_id'], $page_data['selected_price'], $page_data['selected_status']);
        $page_data['page_name']              = 'manuscripts';
        $page_data['categories']             = $this->crud_model->get_categories();
        $page_data['page_title']             = get_phrase('active_manuscripts');
        $this->load->view('backend/index', $page_data);
    }

    public function manuscript_actions($param1 = "", $param2 = "") {
        if ($this->session->userdata('user_login') != true) {
            redirect(site_url('home/login'), 'refresh');
        }

        if ($param1 == "add") {
            $this->crud_model->add_manuscript();
            redirect(site_url('user/manuscripts'), 'refresh');

        }
        elseif ($param1 == "edit") {
            $this->is_the_manuscript_belongs_to_current_researcher($param2);
            $this->crud_model->update_manuscript($param2);
            redirect(site_url('user/manuscripts'), 'refresh');

        }
        elseif ($param1 == 'delete') {
            $this->is_the_manuscript_belongs_to_current_researcher($param2);
            $this->crud_model->delete_manuscript($param2);
            redirect(site_url('user/manuscripts'), 'refresh');
        }
        elseif ($param1 == 'draft') {
            $this->is_the_manuscript_belongs_to_current_researcher($param2);
            $this->crud_model->change_manuscript_status('draft', $param2);
            redirect(site_url('user/manuscripts'), 'refresh');
        }
        elseif ($param1 == 'publish') {
            $this->is_the_manuscript_belongs_to_current_researcher($param2);
            $this->crud_model->change_manuscript_status('pending', $param2);
            redirect(site_url('user/manuscripts'), 'refresh');
        }
    }

    public function manuscript_form($param1 = "", $param2 = "") {

        if ($this->session->userdata('user_login') != true) {
            redirect(site_url('home/login'), 'refresh');
        }

        if ($param1 == 'add_manuscript') {
            $page_data['languages']	= $this->get_all_languages();
            $page_data['categories'] = $this->crud_model->get_categories();
            $page_data['page_name'] = 'manuscript_add';
            $page_data['page_title'] = get_phrase('add_manuscript');
            $this->load->view('backend/index', $page_data);

        }elseif ($param1 == 'manuscript_edit') {
            $this->is_the_manuscript_belongs_to_current_researcher($param2);
            $page_data['page_name'] = 'manuscript_edit';
            $page_data['manuscript_id'] =  $param2;
            $page_data['page_title'] = get_phrase('edit_manuscript');
            $page_data['languages']	= $this->get_all_languages();
            $page_data['categories'] = $this->crud_model->get_categories();
            $this->load->view('backend/index', $page_data);
        }
    }

    public function payment_settings($param1 = "") {
        if ($this->session->userdata('user_login') != true) {
            redirect(site_url('home/login'), 'refresh');
        }

        if ($param1 == 'paypal_settings') {
            $this->user_model->update_researcher_paypal_settings($this->session->userdata('user_id'));
            redirect(site_url('user/payment_settings'), 'refresh');
        }
        if ($param1 == 'stripe_settings') {
            $this->user_model->update_researcher_stripe_settings($this->session->userdata('user_id'));
            redirect(site_url('user/payment_settings'), 'refresh');
        }

        $page_data['page_name'] = 'payment_settings';
        $page_data['page_title'] = get_phrase('payment_settings');
        $this->load->view('backend/index', $page_data);
    }

    public function researcher_revenue($param1 = "") {
        if ($this->session->userdata('user_login') != true) {
            redirect(site_url('home/login'), 'refresh');
        }

        if ($param1 != "") {
            $date_range                   = $this->input->post('date_range');
            $date_range                   = explode(" - ", $date_range);
            $page_data['timestamp_start'] = strtotime($date_range[0]);
            $page_data['timestamp_end']   = strtotime($date_range[1]);
        }else {
            $page_data['timestamp_start'] = strtotime('-29 days', time());
            $page_data['timestamp_end']   = strtotime(date("m/d/Y"));
        }
        $page_data['payment_history'] = $this->crud_model->get_researcher_revenue($page_data['timestamp_start'], $page_data['timestamp_end']);
        $page_data['page_name'] = 'researcher_revenue';
        $page_data['page_title'] = get_phrase('researcher_revenue');
        $this->load->view('backend/index', $page_data);
    }

    function get_all_languages() {
        $language_files = array();
        $all_files = $this->get_list_of_language_files();
        foreach ($all_files as $file) {
            $info = pathinfo($file);
            if( isset($info['extension']) && strtolower($info['extension']) == 'json') {
                $file_name = explode('.json', $info['basename']);
                array_push($language_files, $file_name[0]);
            }
        }
        return $language_files;
    }

    function get_list_of_language_files($dir = APPPATH.'/language', &$results = array()) {
        $files = scandir($dir);
        foreach($files as $key => $value){
            $path = realpath($dir.DIRECTORY_SEPARATOR.$value);
            if(!is_dir($path)) {
                $results[] = $path;
            } else if($value != "." && $value != "..") {
                $this->get_list_of_directories_and_files($path, $results);
                $results[] = $path;
            }
        }
        return $results;
    }

    function get_list_of_directories_and_files($dir = APPPATH, &$results = array()) {
        $files = scandir($dir);
        foreach($files as $key => $value){
            $path = realpath($dir.DIRECTORY_SEPARATOR.$value);
            if(!is_dir($path)) {
                $results[] = $path;
            } else if($value != "." && $value != "..") {
                $this->get_list_of_directories_and_files($path, $results);
                $results[] = $path;
            }
        }
        return $results;
    }

    public function preview($manuscript_id = '') {
        if ($this->session->userdata('user_login') != 1)
        redirect(site_url('home/login'), 'refresh');

        $this->is_the_manuscript_belongs_to_current_researcher($manuscript_id);
        if ($manuscript_id > 0) {
            $manuscripts = $this->crud_model->get_manuscript_by_id($manuscript_id);
            if ($manuscripts->num_rows() > 0) {
                $manuscript_details = $manuscripts->row_array();
                redirect(site_url('home/lesson/'.slugify($manuscript_details['title']).'/'.$manuscript_details['id']), 'refresh');
            }
        }
        redirect(site_url('user/manuscripts'), 'refresh');
    }

    public function sections($param1 = "", $param2 = "", $param3 = "") {
        if ($this->session->userdata('user_login') != true) {
            redirect(site_url('home/login'), 'refresh');
        }

        if ($param2 == 'add') {
            $this->crud_model->add_section($param1);
            $this->session->set_flashdata('flash_message', get_phrase('section_has_been_added_successfully'));
        }
        elseif ($param2 == 'edit') {
            $this->crud_model->edit_section($param3);
            $this->session->set_flashdata('flash_message', get_phrase('section_has_been_updated_successfully'));
        }
        elseif ($param2 == 'delete') {
            $this->crud_model->delete_section($param1, $param3);
            $this->session->set_flashdata('flash_message', get_phrase('section_has_been_deleted_successfully'));
        }
        redirect(site_url('user/manuscript_form/manuscript_edit/'.$param1));
    }

    public function lessons($manuscript_id = "", $param1 = "", $param2 = "") {
        if ($this->session->userdata('user_login') != true) {
            redirect(site_url('home/login'), 'refresh');
        }
        if ($param1 == 'add') {
            $this->crud_model->add_lesson();
            $this->session->set_flashdata('flash_message', get_phrase('lesson_has_been_added_successfully'));
            redirect('user/manuscript_form/manuscript_edit/'.$manuscript_id);
        }
        elseif ($param1 == 'edit') {
            $this->crud_model->edit_lesson($param2);
            $this->session->set_flashdata('flash_message', get_phrase('lesson_has_been_updated_successfully'));
            redirect('user/manuscript_form/manuscript_edit/'.$manuscript_id);
        }
        elseif ($param1 == 'delete') {
            $this->crud_model->delete_lesson($param2);
            $this->session->set_flashdata('flash_message', get_phrase('lesson_has_been_deleted_successfully'));
            redirect('user/manuscript_form/manuscript_edit/'.$manuscript_id);
        }
        elseif ($param1 == 'filter') {
            redirect('user/lessons/'.$this->input->post('manuscript_id'));
        }
        $page_data['page_name'] = 'lessons';
        $page_data['lessons'] = $this->crud_model->get_lessons('manuscript', $manuscript_id);
        $page_data['manuscript_id'] = $manuscript_id;
        $page_data['page_title'] = get_phrase('lessons');
        $this->load->view('backend/index', $page_data);
    }

    // This function checks if this manuscript belongs to current logged in researcher
    function is_the_manuscript_belongs_to_current_researcher($manuscript_id) {
        $manuscript_details = $this->crud_model->get_manuscript_by_id($manuscript_id)->row_array();
        if ($manuscript_details['user_id'] != $this->session->userdata('user_id')) {
            $this->session->set_flashdata('error_message', get_phrase('you_do_not_have_right_to_access_this_manuscript'));
            redirect(site_url('user/manuscripts'), 'refresh');
        }
    }

    // Manage Quizes
    public function quizes($manuscript_id = "", $action = "", $quiz_id = "") {
        if ($this->session->userdata('user_login') != true) {
            redirect(site_url('home/login'), 'refresh');
        }

        if ($action == 'add') {
            $this->crud_model->add_quiz($manuscript_id);
            $this->session->set_flashdata('flash_message', get_phrase('quiz_has_been_added_successfully'));
        }
        elseif ($action == 'edit') {
            $this->crud_model->edit_quiz($quiz_id);
            $this->session->set_flashdata('flash_message', get_phrase('quiz_has_been_updated_successfully'));
        }
        elseif ($action == 'delete') {
            $this->crud_model->delete_section($manuscript_id, $quiz_id);
            $this->session->set_flashdata('flash_message', get_phrase('quiz_has_been_deleted_successfully'));
        }
        redirect(site_url('user/manuscript_form/manuscript_edit/'.$manuscript_id));
    }

    // Manage Quize Questions
    public function quiz_questions($quiz_id = "", $action = "", $question_id = "") {
        if ($this->session->userdata('user_login') != true) {
            redirect(site_url('home/login'), 'refresh');
        }
        $quiz_details = $this->crud_model->get_lessons('lesson', $quiz_id)->row_array();

        if ($action == 'add') {
            $response = $this->crud_model->add_quiz_questions($quiz_id);
            echo $response;
        }

        elseif ($action == 'edit') {
            $response = $this->crud_model->update_quiz_questions($question_id);
            echo $response;
        }

        elseif ($action == 'delete') {
            $response = $this->crud_model->delete_quiz_question($question_id);
            $this->session->set_flashdata('flash_message', get_phrase('question_has_been_deleted'));
            redirect(site_url('user/manuscript_form/manuscript_edit/'.$quiz_details['manuscript_id']));
        }
    }

    function manage_profile() {
        redirect(site_url('home/profile/user_profile'), 'refresh');
    }

    function invoice($payment_id = "") {
        if ($this->session->userdata('user_login') != true) {
            redirect(site_url('home/login'), 'refresh');
        }
        $page_data['page_name'] = 'invoice';
        $page_data['payment_details'] = $this->crud_model->get_payment_details_by_id($payment_id);
        $page_data['page_title'] = get_phrase('invoice');
        $this->load->view('backend/index', $page_data);
    }
    // Ajax Portion
    public function ajax_get_video_details() {
        $video_details = $this->video_model->getVideoDetails($_POST['video_url']);
        echo $video_details['duration'];
    }
    public function lesson($manuscript_slug, $manuscript_id) {
        $data['manuscript_id'] = $manuscript_id; // Pass this to the view
        // Fetch other necessary data and load the view
    }

    // this function is responsible for managing multiple choice question
    function manage_multiple_choices_options() {
        $page_data['number_of_options'] = $this->input->post('number_of_options');
        $this->load->view('backend/user/manage_multiple_choices_options', $page_data);
    }

    public function ajax_sort_section() {
        $section_json = $this->input->post('itemJSON');
        $this->crud_model->sort_section($section_json);
    }
    public function ajax_sort_lesson() {
        $lesson_json = $this->input->post('itemJSON');
        $this->crud_model->sort_lesson($lesson_json);
    }
    public function ajax_sort_question() {
        $question_json = $this->input->post('itemJSON');
        $this->crud_model->sort_question($question_json);
    }
}
