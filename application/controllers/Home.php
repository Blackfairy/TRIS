<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
        $this->load->database();
        $this->load->library('session');
        // $this->load->library('stripe');
        /*cache control*/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        if (!$this->session->userdata('cart_items')) {
            $this->session->set_userdata('cart_items', array());
        }
    }

    public function index() {
        $this->home();
    }

    public function home() {
        $page_data['page_name'] = "home";
        $page_data['page_title'] = get_phrase('home');
        $this->load->view('frontend/'.get_frontend_settings('theme').'/index', $page_data);
    }

    public function shopping_cart() {
        if (!$this->session->userdata('cart_items')) {
            $this->session->set_userdata('cart_items', array());
        }
        $page_data['page_name'] = "shopping_cart";
        $page_data['page_title'] = get_phrase('shopping_cart');
        $this->load->view('frontend/'.get_frontend_settings('theme').'/index', $page_data);
    }
    public function manuscripts() {
        if (!$this->session->userdata('layout')) {
            $this->session->set_userdata('layout', 'list');
        }
        $layout = $this->session->userdata('layout');
        $selected_category_id = "all";
        $selected_price = "all";
        $selected_level = "all";
        $selected_language = "all";
        $selected_rating = "all";
        $search_string = "";
    
        // Get the category ids
        if (isset($_GET['category']) && !empty($_GET['category'] && $_GET['category'] != "all")) {
            $selected_category_id = $this->crud_model->get_category_id($_GET['category']);
        }
    
        // Get the selected price
        if (isset($_GET['price']) && !empty($_GET['price'])) {
            $selected_price = $_GET['price'];
        }
    
        
    
        // Get the selected rating
        if (isset($_GET['rating']) && !empty($_GET['rating'])) {
            $selected_rating = $_GET['rating'];
        }
    
        // Get the search string
        if (isset($_GET['query']) && !empty($_GET['query'])) {
            $search_string = $_GET['query'];
        }
    
        $this->db->select('*');
        $this->db->from('manuscript');
        $this->db->where('status', 'active');
    
        if ($selected_category_id != "all") {
            $this->db->where('category_id', $selected_category_id);
        }
    
        if ($selected_price != "all") {
            if ($selected_price == "free") {
                $this->db->where('price', 0);
            } else {
                $this->db->where('price >', 0);
            }
        }
    
    
        if ($selected_rating != "all") {
            $this->db->where('rating >=', $selected_rating);
        }
    
        if (!empty($search_string)) {
            $this->db->group_start();
            $this->db->like('title', $search_string);
            $this->db->or_like('description', $search_string);
            $this->db->or_like('meta_keywords', $search_string);
            $this->db->group_end();
        }
    
        $total_rows = $this->db->count_all_results('', FALSE);
        $config = pagintaion($total_rows, 6);
        $config['base_url'] = site_url('home/manuscripts/');
        $this->pagination->initialize($config);
        $page_data['manuscripts'] = $this->db->get('', $config['per_page'], $this->uri->segment(3))->result_array();
    
        $page_data['page_name'] = "manuscripts_page";
        $page_data['page_title'] = get_phrase('manuscripts');
        $page_data['layout'] = $layout;
        $page_data['selected_category_id'] = $selected_category_id;
        $page_data['selected_price'] = $selected_price;
        $page_data['selected_rating'] = $selected_rating;
        $page_data['search_string'] = $search_string;
        $this->load->view('frontend/' . get_frontend_settings('theme') . '/index', $page_data);
    }

    public function set_layout_to_session() {
        $layout = $this->input->post('layout');
        $this->session->set_userdata('layout', $layout);
    }
    public function manuscript($slug = "", $manuscript_id = "") {
        if ($this->session->userdata('user_login') != true && $this->session->userdata('admin_login') != true) {
            redirect(site_url('home/login'), 'refresh');
        }
        $this->access_denied_manuscripts($manuscript_id);
        $page_data['manuscript_id'] = $manuscript_id;
        $page_data['page_name'] = "manuscript_page";
        $page_data['page_title'] = get_phrase('manuscript');
        $this->load->view('frontend/'.get_frontend_settings('theme').'/index', $page_data);
    }
    public function researcher_page($researcher_id = "") {
        $page_data['page_name'] = "researcher_page";
        $page_data['page_title'] = get_phrase('researcher_page');
        $page_data['researcher_id'] = $researcher_id;
        $this->load->view('frontend/'.get_frontend_settings('theme').'/index', $page_data);
    }

    public function my_manuscripts() {
        if ($this->session->userdata('user_login') != true) {
            redirect(site_url('home'), 'refresh');
        }
        $page_data['page_name'] = "my_manuscripts";
        $page_data['page_title'] = get_phrase("my_manuscripts");
        $this->load->view('frontend/'.get_frontend_settings('theme').'/index', $page_data);
    }

    public function my_messages($param1 = "", $param2 = "") {
        if ($this->session->userdata('user_login') != true) {
            redirect(site_url('home'), 'refresh');
        }
        if ($param1 == 'read_message') {
            $page_data['message_thread_code'] = $param2;
        }
        elseif ($param1 == 'send_new') {
            $message_thread_code = $this->crud_model->send_new_private_message();
            $this->session->set_flashdata('flash_message', get_phrase('message_sent!'));
            redirect(site_url('home/my_messages/read_message/' . $message_thread_code), 'refresh');
        }
        elseif ($param1 == 'send_reply') {
            $this->crud_model->send_reply_message($param2); //$param2 = message_thread_code
            $this->session->set_flashdata('flash_message', get_phrase('message_sent!'));
            redirect(site_url('home/my_messages/read_message/' . $param2), 'refresh');
        }
        $page_data['page_name'] = "my_messages";
        $page_data['page_title'] = get_phrase('my_messages');
        $this->load->view('frontend/'.get_frontend_settings('theme').'/index', $page_data);
    }

    public function my_notifications() {
        $page_data['page_name'] = "my_notifications";
        $page_data['page_title'] = get_phrase('my_notifications');
        $this->load->view('frontend/'.get_frontend_settings('theme').'/index', $page_data);
    }

    public function my_wishlist() {
        if (!$this->session->userdata('cart_items')) {
            $this->session->set_userdata('cart_items', array());
        }
        $my_manuscripts = $this->crud_model->get_manuscripts_by_wishlists();
        $page_data['my_manuscripts'] = $my_manuscripts;
        $page_data['page_name'] = "my_wishlist";
        $page_data['page_title'] = get_phrase('my_wishlist');
        $this->load->view('frontend/'.get_frontend_settings('theme').'/index', $page_data);
    }

    public function purchase_history() {
        if ($this->session->userdata('user_login') != true) {
            redirect(site_url('home'), 'refresh');
        }

        $total_rows = $this->crud_model->purchase_history($this->session->userdata('user_id'))->num_rows();
        $config = array();
        $config = pagintaion($total_rows, 3);
        $config['base_url']  = site_url('home/purchase_history');
        $this->pagination->initialize($config);
        $page_data['per_page']   = $config['per_page'];
        $page_data['page_name']  = "purchase_history";
        $page_data['page_title'] = get_phrase('purchase_history');
        $this->load->view('frontend/'.get_frontend_settings('theme').'/index', $page_data);
    }

    public function profile($param1 = "") {
        if ($this->session->userdata('user_login') != true) {
            redirect(site_url('home'), 'refresh');
        }

        if ($param1 == 'user_profile') {
            $page_data['page_name'] = "user_profile";
            $page_data['page_title'] = get_phrase('user_profile');
        }elseif ($param1 == 'user_credentials') {
            $page_data['page_name'] = "user_credentials";
            $page_data['page_title'] = get_phrase('credentials');
        }elseif ($param1 == 'user_photo') {
            $page_data['page_name'] = "update_user_photo";
            $page_data['page_title'] = get_phrase('update_user_photo');
        }
        $page_data['user_details'] = $this->user_model->get_user($this->session->userdata('user_id'));
        $this->load->view('frontend/'.get_frontend_settings('theme').'/index', $page_data);
    }

    public function update_profile($param1 = "") {
        if ($param1 == 'update_basics') {
            $this->user_model->edit_user($this->session->userdata('user_id'));
        }elseif ($param1 == "update_credentials") {
            $this->user_model->update_account_settings($this->session->userdata('user_id'));
        }elseif ($param1 == "update_photo") {
            $this->user_model->upload_user_image($this->session->userdata('user_id'));
            $this->session->set_flashdata('flash_message', get_phrase('updated_successfully'));
        }
        redirect(site_url('home/profile/user_profile'), 'refresh');
    }

    public function handleWishList() {
        if ($this->session->userdata('user_login') != 1) {
            echo false;
        }else {
            if (isset($_POST['manuscript_id'])) {
                $manuscript_id = $this->input->post('manuscript_id');
                $this->crud_model->handleWishList($manuscript_id);
            }
            $this->load->view('frontend/'.get_frontend_settings('theme').'/wishlist_items');
        }
    }
    public function handleCartItems() {
        if (!$this->session->userdata('cart_items')) {
            $this->session->set_userdata('cart_items', array());
        }

        $manuscript_id = $this->input->post('manuscript_id');
        $previous_cart_items = $this->session->userdata('cart_items');
        if (in_array($manuscript_id, $previous_cart_items)) {
            $key = array_search($manuscript_id, $previous_cart_items);
            unset($previous_cart_items[$key]);
        }else {
            array_push($previous_cart_items, $manuscript_id);
        }

        $this->session->set_userdata('cart_items', $previous_cart_items);
        $this->load->view('frontend/'.get_frontend_settings('theme').'/cart_items');
    }

    public function handleCartItemForBuyNowButton() {
        if (!$this->session->userdata('cart_items')) {
            $this->session->set_userdata('cart_items', array());
        }

        $manuscript_id = $this->input->post('manuscript_id');
        $previous_cart_items = $this->session->userdata('cart_items');
        if (!in_array($manuscript_id, $previous_cart_items)) {
            array_push($previous_cart_items, $manuscript_id);
        }
        $this->session->set_userdata('cart_items', $previous_cart_items);
        $this->load->view('frontend/'.get_frontend_settings('theme').'/cart_items');
    }

    public function refreshWishList() {
        $this->load->view('frontend/'.get_frontend_settings('theme').'/wishlist_items');
    }

    public function refreshShoppingCart() {
        $this->load->view('frontend/'.get_frontend_settings('theme').'/shopping_cart_inner_view');
    }

    public function isLoggedIn() {
        if ($this->session->userdata('user_login') == 1)
        echo true;
        else
        echo false;
    }

    public function paypal_checkout() {
        if ($this->session->userdata('user_login') != 1)
        redirect('home', 'refresh');

        $total_price_of_checking_out  = $this->input->post('total_price_of_checking_out');
        $page_data['user_details']    = $this->user_model->get_user($this->session->userdata('user_id'))->row_array();
        $page_data['amount_to_pay']   = $total_price_of_checking_out;
        $this->load->view('frontend/'.get_frontend_settings('theme').'/paypal_checkout', $page_data);
    }

    public function stripe_checkout() {
        if ($this->session->userdata('user_login') != 1)
        redirect('home', 'refresh');

        $total_price_of_checking_out  = $this->input->post('total_price_of_checking_out');
        $page_data['user_details']    = $this->user_model->get_user($this->session->userdata('user_id'))->row_array();
        $page_data['amount_to_pay']   = $total_price_of_checking_out;
        $this->load->view('frontend/'.get_frontend_settings('theme').'/stripe_checkout', $page_data);
    }

    public function payment_success($method = "", $user_id = "", $amount_paid = "") {
        if ($method == 'stripe') {
            $token_id = $this->input->post('stripeToken');
            $stripe_keys = get_settings('stripe_keys');
            $values = json_decode($stripe_keys);
            if ($values[0]->testmode == 'on') {
                $public_key = $values[0]->public_key;
                $secret_key = $values[0]->secret_key;
            } else {
                $public_key = $values[0]->public_live_key;
                $secret_key = $values[0]->secret_live_key;
            }
            $this->payment_model->stripe_payment($token_id, $user_id, $amount_paid, $secret_key);
        }

        $this->crud_model->enrol_student($user_id);
        $this->crud_model->manuscript_purchase($user_id, $method, $amount_paid);
        $this->session->set_userdata('cart_items', array());
        $this->session->set_flashdata('flash_message', get_phrase('payment_successfully_done'));
        redirect('home', 'refresh');
    }

    public function lesson($slug = "", $manuscript_id = "", $lesson_id = "") {
        if ($this->session->userdata('user_login') != 1){
            if ($this->session->userdata('admin_login') != 1){
                redirect('home', 'refresh');
            }
        }

        $manuscript_details = $this->crud_model->get_manuscript_by_id($manuscript_id)->row_array();
        $sections = $this->crud_model->get_section('manuscript', $manuscript_id);
        if ($sections->num_rows() > 0) {
            $page_data['sections'] = $sections->result_array();
            if ($lesson_id == "") {
                $default_section = $sections->row_array();
                $page_data['section_id'] = $default_section['id'];
                $lessons = $this->crud_model->get_lessons('section', $default_section['id']);
                if ($lessons->num_rows() > 0) {
                    $default_lesson = $lessons->row_array();
                    $lesson_id = $default_lesson['id'];
                    $page_data['lesson_id']  = $default_lesson['id'];
                }else {
                    $page_data['page_name'] = 'empty';
                    $page_data['page_title'] = get_phrase('no_lesson_found');
                    $page_data['page_body'] = get_phrase('no_lesson_found');
                }
            }else {
                $page_data['lesson_id']  = $lesson_id;
                $section_id = $this->db->get_where('lesson', array('id' => $lesson_id))->row()->section_id;
                $page_data['section_id'] = $section_id;
            }

        }else {
            $page_data['sections'] = array();
            $page_data['page_name'] = 'empty';
            $page_data['page_title'] = get_phrase('no_section_found');
            $page_data['page_body'] = get_phrase('no_section_found');
        }

        // Check if the lesson contained manuscript is purchased by the user
        if (isset($page_data['lesson_id']) && $page_data['lesson_id'] > 0) {
            $lesson_details = $this->crud_model->get_lessons('lesson', $page_data['lesson_id'])->row_array();
            $lesson_id_wise_manuscript_details = $this->crud_model->get_manuscript_by_id($lesson_details['manuscript_id'])->row_array();
            if ($this->session->userdata('role_id') != 1 && $lesson_id_wise_manuscript_details['user_id'] != $this->session->userdata('user_id')) {
                if (!is_purchased($lesson_details['manuscript_id'])) {
                    redirect(site_url('home/manuscript/'.slugify($manuscript_details['title']).'/'.$manuscript_details['id']), 'refresh');
                }
            }
        }else {
            if (!is_purchased($manuscript_id)) {
                redirect(site_url('home/manuscript/'.slugify($manuscript_details['title']).'/'.$manuscript_details['id']), 'refresh');
            }
        }

        $page_data['manuscript_id']  = $manuscript_id;
        $page_data['page_name']  = 'lessons';
        $page_data['page_title'] = $manuscript_details['title'];
        $this->load->view('frontend/'.get_frontend_settings('theme').'/index', $page_data);
    }

    public function my_manuscripts_by_category() {
        $category_id = $this->input->post('category_id');
        $manuscript_details = $this->crud_model->get_my_manuscripts_by_category_id($category_id)->result_array();
        $page_data['my_manuscripts'] = $manuscript_details;
        $this->load->view('frontend/'.get_frontend_settings('theme').'/reload_my_manuscripts', $page_data);
    }

    public function search($search_string = "") {
        $selected_category_id = $this->input->get('category_id');
        $selected_sub_category_id = $this->input->get('sub_category_id');
        $selected_price = $this->input->get('price');
        $selected_rating = $this->input->get('rating');
    
        if (isset($_GET['query']) && !empty($_GET['query'])) {
            $search_string = $_GET['query'];
            $this->db->select('*');
            $this->db->from('manuscript');
            $this->db->where('status', 'active');
            $this->db->group_start();
            $this->db->like('title', $search_string);
            $this->db->or_like('description', $search_string);
            $this->db->or_like('meta_keywords', $search_string);
            $this->db->group_end();
    
            if (!empty($selected_category_id) && $selected_category_id != "all") {
                $this->db->where('category_id', $selected_category_id);
            }
    
            if (!empty($selected_sub_category_id) && $selected_sub_category_id != "all") {
                $this->db->where('sub_category_id', $selected_sub_category_id);
            }
    
            if (!empty($selected_price) && $selected_price != "all") {
                if ($selected_price == "free") {
                    $this->db->where('price', 0);
                } else {
                    $this->db->where('price >', 0);
                }
            }
    
            if (!empty($selected_rating) && $selected_rating != "all") {
                $this->db->where('rating >=', $selected_rating);
            }
    
            $page_data['manuscripts'] = $this->db->get()->result_array();
        } else {
            $this->session->set_flashdata('error_message', get_phrase('no_search_value_found'));
            redirect(site_url(), 'refresh');
        }
    
        if (!$this->session->userdata('layout')) {
            $this->session->set_userdata('layout', 'list');
        }
        $page_data['layout'] = $this->session->userdata('layout');
        $page_data['page_name'] = 'manuscripts_page';
        $page_data['search_string'] = $search_string;
        $page_data['page_title'] = get_phrase('search_results');
        $this->load->view('frontend/' . get_frontend_settings('theme') . '/index', $page_data);
    }
        public function my_manuscripts_by_search_string() {
            $search_string = $this->input->post('search_string');
            $manuscript_details = $this->crud_model->get_my_manuscripts_by_search_string($search_string)->result_array();
            $page_data['my_manuscripts'] = $manuscript_details;
            $this->load->view('frontend/'.get_frontend_settings('theme').'/reload_my_manuscripts', $page_data);
        }

    public function get_my_wishlists_by_search_string() {
        $search_string = $this->input->post('search_string');
        $manuscript_details = $this->crud_model->get_manuscripts_of_wishlists_by_search_string($search_string);
        $page_data['my_manuscripts'] = $manuscript_details;
        $this->load->view('frontend/'.get_frontend_settings('theme').'/reload_my_wishlists', $page_data);
    }

    public function reload_my_wishlists() {
        $my_manuscripts = $this->crud_model->get_manuscripts_by_wishlists();
        $page_data['my_manuscripts'] = $my_manuscripts;
        $this->load->view('frontend/'.get_frontend_settings('theme').'/reload_my_wishlists', $page_data);
    }

    public function get_manuscript_details() {
        $manuscript_id = $this->input->post('manuscript_id');
        $manuscript_details = $this->crud_model->get_manuscript_by_id($manuscript_id)->row_array();
        echo $manuscript_details['title'];
    }

    public function rate_manuscript() {
        $data['review'] = $this->input->post('review');
        $data['ratable_id'] = $this->input->post('manuscript_id');
        $data['ratable_type'] = 'manuscript';
        $data['rating'] = $this->input->post('starRating');
        $data['date_added'] = strtotime(date('D, d-M-Y'));
        $data['user_id'] = $this->session->userdata('user_id');
        $this->crud_model->rate($data);
    }

    public function about_us() {
        $page_data['page_name'] = 'about_us';
        $page_data['page_title'] = get_phrase('about_us');
        $this->load->view('frontend/'.get_frontend_settings('theme').'/index', $page_data);
    }

    public function terms_and_condition() {
        $page_data['page_name'] = 'terms_and_condition';
        $page_data['page_title'] = get_phrase('terms_and_condition');
        $this->load->view('frontend/'.get_frontend_settings('theme').'/index', $page_data);
    }

    public function privacy_policy() {
        $page_data['page_name'] = 'privacy_policy';
        $page_data['page_title'] = get_phrase('privacy_policy');
        $this->load->view('frontend/'.get_frontend_settings('theme').'/index', $page_data);
    }


    // Version 1.1
    public function dashboard($param1 = "") {
        if ($this->session->userdata('user_login') != 1){
            redirect('home', 'refresh');
        }

        if ($param1 == "") {
            $page_data['type'] = 'active';
        }else {
            $page_data['type'] = $param1;
        }

        $page_data['page_name']  = 'researcher_dashboard';
        $page_data['page_title'] = get_phrase('researcher_dashboard');
        $page_data['user_id']    = $this->session->userdata('user_id');
        $this->load->view('frontend/'.get_frontend_settings('theme').'/index', $page_data);
    }

    public function create_manuscript() {
        if ($this->session->userdata('user_login') != 1){
            redirect('home', 'refresh');
        }

        $page_data['page_name'] = 'create_manuscript';
        $page_data['page_title'] = get_phrase('create_manuscript');
        $this->load->view('frontend/'.get_frontend_settings('theme').'/index', $page_data);
    }

    public function edit_manuscript($param1 = "", $param2 = "") {
        if ($this->session->userdata('user_login') != 1){
            redirect('home', 'refresh');
        }

        if ($param2 == "") {
            $page_data['type']   = 'edit_manuscript';
        }else {
            $page_data['type']   = $param2;
        }
        $page_data['page_name']  = 'manage_manuscript_details';
        $page_data['manuscript_id']  = $param1;
        $page_data['page_title'] = get_phrase('edit_manuscript');
        $this->load->view('frontend/'.get_frontend_settings('theme').'/index', $page_data);
    }

    public function manuscript_action($param1 = "", $param2 = "") {
        if ($this->session->userdata('user_login') != 1){
            redirect('home', 'refresh');
        }

        if ($param1 == 'create') {
            if (isset($_POST['create_manuscript'])) {
                $this->crud_model->add_manuscript();
                redirect(site_url('home/create_manuscript'), 'refresh');
            }else {
                $this->crud_model->add_manuscript('save_to_draft');
                redirect(site_url('home/create_manuscript'), 'refresh');
            }
        }elseif ($param1 == 'edit') {
            if (isset($_POST['publish'])) {
                $this->crud_model->update_manuscript($param2, 'publish');
                redirect(site_url('home/dashboard'), 'refresh');
            }else {
                $this->crud_model->update_manuscript($param2, 'save_to_draft');
                redirect(site_url('home/dashboard'), 'refresh');
            }
        }
    }


    public function sections($action = "", $manuscript_id = "", $section_id = "") {
        if ($this->session->userdata('user_login') != 1){
            redirect('home', 'refresh');
        }

        if ($action == "add") {
            $this->crud_model->add_section($manuscript_id);

        }elseif ($action == "edit") {
            $this->crud_model->edit_section($section_id);

        }elseif ($action == "delete") {
            $this->crud_model->delete_section($manuscript_id, $section_id);
            $this->session->set_flashdata('flash_message', get_phrase('section_deleted'));
            redirect(site_url("home/edit_manuscript/$manuscript_id/manage_section"), 'refresh');

        }elseif ($action == "serialize_section") {
            $container = array();
            $serialization = json_decode($this->input->post('updatedSerialization'));
            foreach ($serialization as $key) {
                array_push($container, $key->id);
            }
            $json = json_encode($container);
            $this->crud_model->serialize_section($manuscript_id, $json);
        }
        $page_data['manuscript_id'] = $manuscript_id;
        $page_data['manuscript_details'] = $this->crud_model->get_manuscript_by_id($manuscript_id)->row_array();
        return $this->load->view('frontend/'.get_frontend_settings('theme').'/reload_section', $page_data);
    }

    public function manage_lessons($action = "", $manuscript_id = "", $lesson_id = "") {
        if ($this->session->userdata('user_login') != 1){
            redirect('home', 'refresh');
        }
        if ($action == 'add') {
            $this->crud_model->add_lesson();
            $this->session->set_flashdata('flash_message', get_phrase('lesson_added'));
        }
        elseif ($action == 'edit') {
            $this->crud_model->edit_lesson($lesson_id);
            $this->session->set_flashdata('flash_message', get_phrase('lesson_updated'));
        }
        elseif ($action == 'delete') {
            $this->crud_model->delete_lesson($lesson_id);
            $this->session->set_flashdata('flash_message', get_phrase('lesson_deleted'));
        }
        redirect('home/edit_manuscript/'.$manuscript_id.'/manage_lesson');
    }

    public function lesson_editing_form($lesson_id = "", $manuscript_id = "") {
        if ($this->session->userdata('user_login') != 1){
            redirect('home', 'refresh');
        }
        $page_data['type']      = 'manage_lesson';
        $page_data['manuscript_id'] = $manuscript_id;
        $page_data['lesson_id'] = $lesson_id;
        $page_data['page_name']  = 'lesson_edit';
        $page_data['page_title'] = get_phrase('update_lesson');
        $this->load->view('frontend/'.get_frontend_settings('theme').'/index', $page_data);
    }

    public function download($filename = "") {
        $tmp           = explode('.', $filename);
        $fileExtension = strtolower(end($tmp));
        $yourFile = base_url().'uploads/lesson_files/'.$filename;
        $file = @fopen($yourFile, "rb");

        header('Content-Description: File Transfer');
        header('Content-Type: text/plain');
        header('Content-Disposition: attachment; filename='.$filename);
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($yourFile));
        while (!feof($file)) {
            print(@fread($file, 1024 * 8));
            ob_flush();
            flush();
        }
    }

    // Version 1.3 codes
    public function get_enrolled_to_free_manuscript($manuscript_id) {
        if ($this->session->userdata('user_login') == 1) {
            $this->crud_model->enrol_to_free_manuscript($manuscript_id, $this->session->userdata('user_id'));
            redirect(site_url('home/my_manuscripts'), 'refresh');
        }else {
            redirect(site_url('home/login'), 'refresh');
        }
    }

    // Version 1.4 codes
    public function login() {
        if ($this->session->userdata('admin_login')) {
            redirect(site_url('admin'), 'refresh');
        }elseif ($this->session->userdata('user_login')) {
            redirect(site_url('user'), 'refresh');
        }
        $page_data['page_name'] = 'login';
        $page_data['page_title'] = get_phrase('login');
        $this->load->view('frontend/'.get_frontend_settings('theme').'/index', $page_data);
    }

    public function sign_up() {
        if ($this->session->userdata('admin_login')) {
            redirect(site_url('admin'), 'refresh');
        }elseif ($this->session->userdata('user_login')) {
            redirect(site_url('user'), 'refresh');
        }
        $page_data['page_name'] = 'sign_up';
        $page_data['page_title'] = get_phrase('sign_up');
        $this->load->view('frontend/'.get_frontend_settings('theme').'/index', $page_data);
    }

    public function submit_quiz() {
        $submitted_quiz_info = array();
        $container = array();
        $quiz_id = $this->input->post('lesson_id');
        $quiz_questions = $this->crud_model->get_quiz_questions($quiz_id)->result_array();
        $total_correct_answers = 0;
        foreach ($quiz_questions as $quiz_question) {
            $submitted_answer_status = 0;
            $correct_answers = json_decode($quiz_question['correct_answers']);
            $submitted_answers = array();
            foreach ($this->input->post($quiz_question['id']) as $each_submission) {
                if (isset($each_submission)) {
                    array_push($submitted_answers, $each_submission);
                }
            }
            sort($correct_answers);
            sort($submitted_answers);
            if ($correct_answers == $submitted_answers) {
                $submitted_answer_status = 1;
                $total_correct_answers++;
            }
            $container = array(
                "question_id" => $quiz_question['id'],
                'submitted_answer_status' => $submitted_answer_status,
                "submitted_answers" => json_encode($submitted_answers),
                "correct_answers"  => json_encode($correct_answers),
            );
            array_push($submitted_quiz_info, $container);
        }
        $page_data['submitted_quiz_info']   = $submitted_quiz_info;
        $page_data['total_correct_answers'] = $total_correct_answers;
        $page_data['total_questions'] = count($quiz_questions);
        $this->load->view('frontend/'.get_frontend_settings('theme').'/quiz_result', $page_data);
    }

    private function access_denied_manuscripts($manuscript_id){
        $manuscript_details = $this->crud_model->get_manuscript_by_id($manuscript_id)->row_array();
        if ($manuscript_details['status'] == 'draft' && $manuscript_details['user_id'] != $this->session->userdata('user_id')) {
            $this->session->set_flashdata('error_message', get_phrase('you_do_not_have_permission_to_access_this_manuscript'));
            redirect(site_url('home'), 'refresh');
        }elseif ($manuscript_details['status'] == 'pending') {
            if ($manuscript_details['user_id'] != $this->session->userdata('user_id') && $this->session->userdata('role_id') != 1) {
                $this->session->set_flashdata('error_message', get_phrase('you_do_not_have_permission_to_access_this_manuscript'));
                redirect(site_url('home'), 'refresh');
            }
        }
    }
}
