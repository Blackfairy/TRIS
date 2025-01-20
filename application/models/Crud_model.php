<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crud_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
        /*cache control*/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
    }
    private function log_audit_trail($user_id, $action, $table_name, $record_id, $details = '') {
        // Fetch the user's email address based on the user_id
        $user = $this->db->get_where('users', array('id' => $user_id))->row();
        $user_email = $user ? $user->email : 'Unknown';

        $data = array(
            'user_email' => $user_email,
            'action' => $action,
            'table_name' => $table_name,
            'record_id' => $record_id,
            'details' => $details,
            'timestamp' => date('Y-m-d H:i:s')
        );
        $this->db->insert('audit_trail', $data);
    }
    public function get_categories($param1 = "") {
        if ($param1 != "") {
            $this->db->where('id', $param1);
        }
        $this->db->where('parent', 0);
        return $this->db->get('category');
    }

    public function get_category_details_by_id($id) {
        return $this->db->get_where('category', array('id' => $id));
    }

    public function get_category_id($slug = "") {
        $category_details = $this->db->get_where('category', array('slug' => $slug))->row_array();
        return $category_details['id'];
    }

    public function add_category() {
        $data['code']   = html_escape($this->input->post('code'));
        $data['name']   = html_escape($this->input->post('name'));
        $data['parent'] = html_escape($this->input->post('parent'));
        $data['slug']   = slugify(html_escape($this->input->post('name')));
        if ($this->input->post('parent') == 0) {
            // Font awesome class adding
            if ($_POST['font_awesome_class'] != "") {
                $data['font_awesome_class'] = html_escape($this->input->post('font_awesome_class'));
            }else {
                $data['font_awesome_class'] = 'fas fa-chess';
            }

            // category thumbnail adding
            if (!file_exists('uploads/thumbnails/category_thumbnails')) {
                mkdir('uploads/thumbnails/category_thumbnails', 0777, true);
            }
            if ($_FILES['category_thumbnail']['name'] == "") {
                $data['thumbnail'] = 'category-thumbnail.png';
            }else {
                $data['thumbnail'] = md5(rand(10000000, 20000000)).'.jpg';
                move_uploaded_file($_FILES['category_thumbnail']['tmp_name'], 'uploads/thumbnails/category_thumbnails/'.$data['thumbnail']);
            }
        }
        $data['date_added'] = strtotime(date('D, d-M-Y'));
        $this->db->insert('category', $data);
        $this->log_audit_trail($this->session->userdata('user_id'), 'add', 'category', $category_id, 'Category added');
    }

    public function edit_category($param1) {
        $data['name']   = html_escape($this->input->post('name'));
        $data['parent'] = html_escape($this->input->post('parent'));
        $data['slug']   = slugify(html_escape($this->input->post('name')));
        if ($this->input->post('parent') == 0) {
            // Font awesome class adding
            if ($_POST['font_awesome_class'] != "") {
                $data['font_awesome_class'] = html_escape($this->input->post('font_awesome_class'));
            }else {
                $data['font_awesome_class'] = 'fas fa-chess';
            }
            // category thumbnail adding
            if (!file_exists('uploads/category_thumbnails')) {
                mkdir('uploads/category_thumbnails', 0777, true);
            }
            if ($_FILES['category_thumbnail']['name'] != "") {
                $data['thumbnail'] = md5(rand(10000000, 20000000)).'.jpg';
                move_uploaded_file($_FILES['category_thumbnail']['tmp_name'], 'uploads/thumbnails/category_thumbnails/'.$data['thumbnail']);
            }
        }
        $data['last_modified'] = strtotime(date('D, d-M-Y'));
        $this->db->where('id', $param1);
        $this->db->update('category', $data);
        $this->log_audit_trail($this->session->userdata('user_id'), 'update', 'category', $category_id, 'Category updated');
    }

    public function delete_category($category_id) {
        $this->db->where('id', $category_id);
        $this->db->delete('category');
        $this->log_audit_trail($this->session->userdata('user_id'), 'delete', 'category', $category_id, 'Category deleted');
    }

    public function get_sub_categories($parent_id = "") {
        return $this->db->get_where('category', array('parent' => $parent_id))->result_array();
    }

    public function enrol_history($manuscript_id = "") {
        if ($manuscript_id > 0) {
            return $this->db->get_where('enrol', array('manuscript_id' => $manuscript_id));
        }else {
            return $this->db->get('enrol');
        }
    }

    public function enrol_history_by_user_id($user_id = "") {
        return $this->db->get_where('enrol', array('user_id' => $user_id));
    }

    public function all_enrolled_student() {
        $this->db->select('user_id');
        $this->db->distinct('user_id');
        return $this->db->get('enrol');
    }

    public function enrol_history_by_date_range($timestamp_start = "", $timestamp_end = "") {
        $this->db->order_by('date_added' , 'desc');
        $this->db->where('date_added >=' , $timestamp_start);
        $this->db->where('date_added <=' , $timestamp_end);
        return $this->db->get('enrol');
    }

    public function get_revenue_by_user_type($timestamp_start = "", $timestamp_end = "", $revenue_type = "") {
        $manuscript_ids = array();
        $manuscripts    = array();
        $admin_details = $this->user_model->get_admin_details()->row_array();
        if ($revenue_type == 'admin_revenue') {
            //$this->db->where('user_id', $admin_details['id']);
        }elseif ($revenue_type == 'researcher_revenue') {
            $this->db->where('user_id !=', $admin_details['id']);
            $this->db->select('id');
            $manuscripts = $this->db->get('manuscript')->result_array();
            foreach ($manuscripts as $manuscript) {
                if (!in_array($manuscript['id'], $manuscript_ids)) {
                    array_push( $manuscript_ids, $manuscript['id'] );
                }
            }
            if (sizeof($manuscript_ids)) {
                $this->db->where_in('manuscript_id', $manuscript_ids);
            }else {
                return array();
            }
        }

        $this->db->order_by('date_added' , 'desc');
        $this->db->where('date_added >=' , $timestamp_start);
        $this->db->where('date_added <=' , $timestamp_end);
        return $this->db->get('payment')->result_array();
    }

    public function get_researcher_revenue($timestamp_start = "", $timestamp_end = "") {
        $manuscript_ids = array();
        $manuscripts    = array();

        $this->db->where('user_id', $this->session->userdata('user_id'));
        $this->db->select('id');
        $manuscripts = $this->db->get('manuscript')->result_array();
        foreach ($manuscripts as $manuscript) {
            if (!in_array($manuscript['id'], $manuscript_ids)) {
                array_push( $manuscript_ids, $manuscript['id'] );
            }
        }
        if (sizeof($manuscript_ids)) {
            $this->db->where_in('manuscript_id', $manuscript_ids);
        }else {
            return array();
        }

        $this->db->order_by('date_added' , 'desc');
        $this->db->where('date_added >=' , $timestamp_start);
        $this->db->where('date_added <=' , $timestamp_end);
        return $this->db->get('payment')->result_array();
    }

    public function delete_payment_history($param1) {
        $this->db->where('id', $param1);
        $this->db->delete('payment');
    }
    public function delete_enrol_history($param1) {
        $this->db->where('id', $param1);
        $this->db->delete('enrol');
    }

    public function purchase_history($user_id) {
        if ($user_id > 0) {
            return $this->db->get_where('payment', array('user_id'=> $user_id));
        }else {
            return $this->db->get('payment');
        }
    }

    public function get_payment_details_by_id($payment_id = "") {
        return $this->db->get_where('payment', array('id' => $payment_id))->row_array();
    }

    public function update_researcher_payment_status($payment_id = "") {
        $updater = array(
            'researcher_payment_status' => 1
        );
        $this->db->where('id', $payment_id);
        $this->db->update('payment', $updater);
    }

    public function update_system_settings() {
        $data['value'] = html_escape($this->input->post('system_name'));
        $this->db->where('key', 'system_name');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('system_title'));
        $this->db->where('key', 'system_title');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('author'));
        $this->db->where('key', 'author');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('slogan'));
        $this->db->where('key', 'slogan');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('language'));
        $this->db->where('key', 'language');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('text_align'));
        $this->db->where('key', 'text_align');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('system_email'));
        $this->db->where('key', 'system_email');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('address'));
        $this->db->where('key', 'address');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('phone'));
        $this->db->where('key', 'phone');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('youtube_api_key'));
        $this->db->where('key', 'youtube_api_key');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('vimeo_api_key'));
        $this->db->where('key', 'vimeo_api_key');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('purchase_code'));
        $this->db->where('key', 'purchase_code');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('footer_text'));
        $this->db->where('key', 'footer_text');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('footer_link'));
        $this->db->where('key', 'footer_link');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('website_keywords'));
        $this->db->where('key', 'website_keywords');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('website_description'));
        $this->db->where('key', 'website_description');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('student_email_verification'));
        $this->db->where('key', 'student_email_verification');
        $this->db->update('settings', $data);
        // Log the update action
        $this->log_audit_trail($this->session->userdata('user_id'), 'update', 'settings', 0, 'System settings updated');
    }

    public function update_smtp_settings() {
        $data['value'] = html_escape($this->input->post('protocol'));
        $this->db->where('key', 'protocol');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('smtp_host'));
        $this->db->where('key', 'smtp_host');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('smtp_port'));
        $this->db->where('key', 'smtp_port');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('smtp_user'));
        $this->db->where('key', 'smtp_user');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('smtp_pass'));
        $this->db->where('key', 'smtp_pass');
        $this->db->update('settings', $data);
        // Log the update action
        $this->log_audit_trail($this->session->userdata('user_id'), 'update', 'settings', 0, 'SMTP settings updated');
    }

    public function update_paypal_settings() {
        // update paypal keys
        $paypal_info = array();
        $paypal['active'] = $this->input->post('paypal_active');
        $paypal['mode'] = $this->input->post('paypal_mode');
        $paypal['sandbox_client_id'] = $this->input->post('sandbox_client_id');
        $paypal['production_client_id'] = $this->input->post('production_client_id');

        array_push($paypal_info, $paypal);

        $data['value']    =   json_encode($paypal_info);
        $this->db->where('key', 'paypal');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('paypal_currency'));
        $this->db->where('key', 'paypal_currency');
        $this->db->update('settings', $data);
        // Log the update action
        $this->log_audit_trail($this->session->userdata('user_id'), 'update', 'settings', 0, 'Paypal settings updated');
    }

    public function update_stripe_settings() {
        // update stripe keys
        $stripe_info = array();

        $stripe['active'] = $this->input->post('stripe_active');
        $stripe['testmode'] = $this->input->post('testmode');
        $stripe['public_key'] = $this->input->post('public_key');
        $stripe['secret_key'] = $this->input->post('secret_key');
        $stripe['public_live_key'] = $this->input->post('public_live_key');
        $stripe['secret_live_key'] = $this->input->post('secret_live_key');

        array_push($stripe_info, $stripe);

        $data['value']    =   json_encode($stripe_info);
        $this->db->where('key', 'stripe_keys');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('stripe_currency'));
        $this->db->where('key', 'stripe_currency');
        $this->db->update('settings', $data);
        // Log the update action
        $this->log_audit_trail($this->session->userdata('user_id'), 'update', 'settings', 0, 'Stripe settings updated');
    }

    public function update_system_currency() {
        $data['value'] = html_escape($this->input->post('system_currency'));
        $this->db->where('key', 'system_currency');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('currency_position'));
        $this->db->where('key', 'currency_position');
        $this->db->update('settings', $data);
        // Log the update action
        $this->log_audit_trail($this->session->userdata('user_id'), 'update', 'settings', 0, 'Currency settings updated');
    }

    public function update_researcher_settings() {
        $data['value'] = html_escape($this->input->post('allow_researcher'));
        $this->db->where('key', 'allow_researcher');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('researcher_revenue'));
        $this->db->where('key', 'researcher_revenue');
        $this->db->update('settings', $data);
    // Log the update action
    $this->log_audit_trail($this->session->userdata('user_id'), 'update', 'settings', 0, 'Researcher settings updated');
    }

    public function get_lessons($type = "", $id = "") {
        $this->db->order_by("order", "asc");
        if($type == "manuscript"){
            return $this->db->get_where('lesson', array('manuscript_id' => $id));
        }
        elseif ($type == "section") {
            return $this->db->get_where('lesson', array('section_id' => $id));
        }
        elseif ($type == "lesson") {
            return $this->db->get_where('lesson', array('id' => $id));
        }
        else {
            return $this->db->get('lesson');
        }
    }

    public function add_manuscript($param1 = "") {
        $outcomes = $this->trim_and_return_json($this->input->post('outcomes'));
        $requirements = $this->trim_and_return_json($this->input->post('requirements'));
        $authors = $this->trim_and_return_json($this->input->post('authors'));
    
        $data['title'] = html_escape($this->input->post('title'));
        $data['short_description'] = $this->input->post('short_description');
        $data['description'] = $this->input->post('description');
        $data['outcomes'] = $outcomes;
        $data['requirements'] = $requirements;
        $data['authors'] = $authors;
        $data['company_name'] = html_escape($this->input->post('company_name'));
        $data['company_short_description'] = $this->input->post('company_short_description');
        $data['date_accomplished'] = $this->input->post('date_accomplished');
        $data['language'] = $this->input->post('language_made_in');
        $data['sub_category_id'] = $this->input->post('sub_category_id');
    
        $category_details = $this->get_category_details_by_id($this->input->post('sub_category_id'))->row_array();
        $data['category_id'] = $category_details['parent'];
    
        $data['price'] = $this->input->post('price');
        $data['discount_flag'] = $this->input->post('discount_flag');
        $data['discounted_price'] = $this->input->post('discounted_price');
        $data['level'] = $this->input->post('level');
        $data['is_free_manuscript'] = $this->input->post('is_free_manuscript');
        $data['video_url'] = html_escape($this->input->post('manuscript_overview_url'));
    
        if ($this->input->post('manuscript_overview_url') != "") {
            $data['manuscript_overview_provider'] = html_escape($this->input->post('manuscript_overview_provider'));
        } else {
            $data['manuscript_overview_provider'] = "";
        }
    
        $data['date_added'] = strtotime(date('D, d-M-Y'));
        $data['section'] = json_encode(array());
        $data['is_top_manuscript'] = $this->input->post('is_top_manuscript');
        $data['user_id'] = $this->session->userdata('user_id');
        $data['meta_description'] = $this->input->post('meta_description');
        $data['meta_keywords'] = $this->input->post('meta_keywords');
    
        $admin_details = $this->user_model->get_admin_details()->row_array();
        $data['is_admin'] = ($admin_details['id'] == $data['user_id']) ? 1 : 0;
    
        if ($param1 == "save_to_draft") {
            $data['status'] = 'draft';
        } else {
            $data['status'] = 'pending';
        }
    
        $this->db->insert('manuscript', $data);
        $manuscript_id = $this->db->insert_id();
    
        // Handle the thumbnail upload
        $thumbnail_file_name = $manuscript_id . '-' . time() . '.jpg';
        if (!file_exists('uploads/thumbnails/manuscript_thumbnails')) {
            mkdir('uploads/thumbnails/manuscript_thumbnails', 0777, true);
        }
    
        if ($_FILES['manuscript_thumbnail']['name'] != "") {
            move_uploaded_file($_FILES['manuscript_thumbnail']['tmp_name'], 'uploads/thumbnails/manuscript_thumbnails/' . $thumbnail_file_name);
        }
    
        $data['thumbnail'] = $thumbnail_file_name;
    
        if ($data['status'] == 'approved') {
            $this->session->set_flashdata('flash_message', get_phrase('manuscript_added_successfully'));
        } elseif ($data['status'] == 'pending') {
            $this->session->set_flashdata('flash_message', get_phrase('manuscript_added_successfully') . '. ' . get_phrase('please_wait_untill_Admin_approves_it'));
        } elseif ($data['status'] == 'draft') {
            $this->session->set_flashdata('flash_message', get_phrase('your_manuscript_has_been_added_to_draft'));
        }
        $this->log_audit_trail($this->session->userdata('user_id'), 'add', 'manuscripts', $manuscript_id, 'Manuscript added');
    }
    
    function trim_and_return_json($untrimmed_array) {
        $trimmed_array = array();
        if(sizeof($untrimmed_array) > 0){
            foreach ($untrimmed_array as $row) {
                if ($row != "") {
                    array_push($trimmed_array, $row);
                }
            }
        }
        return json_encode($trimmed_array);
    }

    public function update_manuscript($manuscript_id, $type = "") {
        $outcomes = $this->trim_and_return_json($this->input->post('outcomes'));
        $requirements = $this->trim_and_return_json($this->input->post('requirements'));
        $authors = $this->trim_and_return_json($this->input->post('authors'));
        
        $data['title'] = html_escape($this->input->post('title'));
        $data['short_description'] = $this->input->post('short_description');
        $data['description'] = $this->input->post('description');
        $data['outcomes'] = $outcomes;
        $data['requirements'] = $requirements;
        $data['authors'] = $authors;
        $data['company_name'] = html_escape($this->input->post('company_name'));
        $data['company_short_description'] = $this->input->post('company_short_description');
        $data['date_accomplished'] = $this->input->post('date_accomplished');
        $data['language'] = $this->input->post('language_made_in');
        $data['sub_category_id'] = $this->input->post('sub_category_id');
        
        // Get category details
        $category_details = $this->get_category_details_by_id($this->input->post('sub_category_id'))->row_array();
        $data['category_id'] = $category_details['parent'];
        
        $data['price'] = $this->input->post('price');
        $data['discount_flag'] = $this->input->post('discount_flag');
        $data['discounted_price'] = $this->input->post('discounted_price');
        $data['level'] = $this->input->post('level');
        $data['is_free_manuscript'] = $this->input->post('is_free_manuscript');
        $data['video_url'] = html_escape($this->input->post('manuscript_overview_url'));
        
        if ($this->input->post('manuscript_overview_url') != "") {
            $data['manuscript_overview_provider'] = html_escape($this->input->post('manuscript_overview_provider'));
        } else {
            $data['manuscript_overview_provider'] = "";
        }
    
        $data['meta_description'] = $this->input->post('meta_description');
        $data['meta_keywords'] = $this->input->post('meta_keywords');
        $data['last_modified'] = strtotime(date('D, d-M-Y'));
        
        if ($this->input->post('is_top_manuscript') != 1) {
            $data['is_top_manuscript'] = 0;
        } else {
            $data['is_top_manuscript'] = 1;
        }
    
        // Handle manuscript status
        if ($type == "save_to_draft") {
            $data['status'] = 'draft';
        } else {
            $data['status'] = 'pending';
        }
    
        // Update manuscript record
        $this->db->where('id', $manuscript_id);
        $this->db->update('manuscript', $data);
    
        // Handle thumbnail upload
        if ($_FILES['manuscript_thumbnail']['name'] != "") {
            $thumbnail_file_name = $manuscript_id . '-' . time() . '.jpg';
            if (!file_exists('uploads/thumbnails/manuscript_thumbnails')) {
                mkdir('uploads/thumbnails/manuscript_thumbnails', 0777, true);
            }
            move_uploaded_file($_FILES['manuscript_thumbnail']['tmp_name'], 'uploads/thumbnails/manuscript_thumbnails/' . $thumbnail_file_name);
            $data['thumbnail'] = $thumbnail_file_name;
    
            // Update thumbnail in the database
            $this->db->where('id', $manuscript_id);
            $this->db->update('manuscript', ['thumbnail' => $thumbnail_file_name]);
        }
    
        // Set flash message based on the manuscript status
        if ($data['status'] == 'approved') {
            $this->session->set_flashdata('flash_message', get_phrase('manuscript_updated_successfully'));
        } elseif ($data['status'] == 'pending') {
            $this->session->set_flashdata('flash_message', get_phrase('manuscript_updated_successfully') . '. ' . get_phrase('please_wait_untill_Admin_approves_it'));
        } elseif ($data['status'] == 'draft') {
            $this->session->set_flashdata('flash_message', get_phrase('your_manuscript_has_been_added_to_draft'));
        }

        $this->log_audit_trail($this->session->userdata('user_id'), 'update', 'manuscripts', $manuscript_id, 'Manuscript updated');
    }
    

    public function change_manuscript_status($status = "", $manuscript_id = "") {
        $updater = array(
            'status' => $status
        );
        $this->db->where('id', $manuscript_id);
        $this->db->update('manuscript', $updater);
        $this->log_audit_trail($this->session->userdata('user_id'), 'update', 'manuscripts', $manuscript_id, 'Manuscript status changed to ' . $status);
    }

    public function get_manuscript_thumbnail_url($manuscript_id) {

        if (file_exists('uploads/thumbnails/manuscript_thumbnails/'.$manuscript_id.'.jpg'))
        return base_url().'uploads/thumbnails/manuscript_thumbnails/'.$manuscript_id.'.jpg';
        else
        return base_url().'uploads/thumbnails/manuscript_thumbnails/manuscript-thumbnail.png';
    }
    public function get_lesson_thumbnail_url($lesson_id) {

        if (file_exists('uploads/thumbnails/lesson_thumbnails/'.$lesson_id.'.jpg'))
        return base_url().'uploads/thumbnails/lesson_thumbnails/'.$lesson_id.'.jpg';
        else
        return base_url().'uploads/thumbnails/thumbnail.png';
    }

    public function get_my_manuscripts_by_category_id($category_id) {
        $this->db->select('manuscript_id');
        $manuscript_lists_by_enrol = $this->db->get_where('enrol', array('user_id' => $this->session->userdata('user_id')))->result_array();
        $manuscript_ids = array();
        foreach ($manuscript_lists_by_enrol as $row) {
            if (!in_array($row['manuscript_id'], $manuscript_ids)) {
                array_push($manuscript_ids, $row['manuscript_id']);
            }
        }
        $this->db->where_in('id', $manuscript_ids);
        $this->db->where('category_id', $category_id);
        return $this->db->get('manuscript');
    }

    public function get_my_manuscripts_by_search_string($search_string) {
        $this->db->select('manuscript_id');
        $manuscript_lists_by_enrol = $this->db->get_where('enrol', array('user_id' => $this->session->userdata('user_id')))->result_array();
        $manuscript_ids = array();
        foreach ($manuscript_lists_by_enrol as $row) {
            if (!in_array($row['manuscript_id'], $manuscript_ids)) {
                array_push($manuscript_ids, $row['manuscript_id']);
            }
        }
        $this->db->where_in('id', $manuscript_ids);
        $this->db->like('title', $search_string);
        return $this->db->get('manuscript');
    }

    public function get_manuscripts_by_search_string($search_string) {
        $this->db->like('title', $search_string);
        $this->db->or_like('meta_keywords', $search_string);
        $this->db->where('status', 'active');
        return $this->db->get('manuscript');
    }


    public function get_manuscript_by_id($manuscript_id = "") {
        return $this->db->get_where('manuscript', array('id' => $manuscript_id));
    }

    public function delete_manuscript($manuscript_id) {
        $this->db->where('id', $manuscript_id);
        $this->db->delete('manuscript');
        $this->log_audit_trail($this->session->userdata('user_id'), 'delete', 'manuscripts', $manuscript_id, 'Manuscript deleted');
    }

    public function get_top_manuscripts() {
        return $this->db->get_where('manuscript', array('is_top_manuscript' => 1, 'status' => 'active'));
    }

    public function get_default_category_id() {
        $categories = $this->get_categories()->result_array();
        foreach ($categories as $category) {
            return $category['id'];
        }
    }

    public function get_manuscripts_by_user_id($param1 = "") {
        $manuscripts['draft'] = $this->db->get_where('manuscript', array('user_id' => $param1, 'status' => 'draft'));
        $manuscripts['pending'] = $this->db->get_where('manuscript', array('user_id' => $param1, 'status' => 'pending'));
        $manuscripts['active'] = $this->db->get_where('manuscript', array('user_id' => $param1, 'status' => 'active'));
        return $manuscripts;
    }

    public function get_status_wise_manuscripts($status = "") {
        if ($status != "") {
            $manuscripts = $this->db->get_where('manuscript', array('status' => $status));
        }else {
            $manuscripts['draft'] = $this->db->get_where('manuscript', array('status' => 'draft'));
            $manuscripts['pending'] = $this->db->get_where('manuscript', array('status' => 'pending'));
            $manuscripts['active'] = $this->db->get_where('manuscript', array('status' => 'active'));
        }
        return $manuscripts;
    }

    public function get_status_wise_manuscripts_for_researcher($status = "") {
        if ($status != "") {
            $this->db->where('status', $status);
            $this->db->where('user_id', $this->session->userdata('user_id'));
            $manuscripts = $this->db->get('manuscript');
        }else {
            $this->db->where('status', 'draft');
            $this->db->where('user_id', $this->session->userdata('user_id'));
            $manuscripts['draft'] = $this->db->get('manuscript');

            $this->db->where('user_id', $this->session->userdata('user_id'));
            $this->db->where('status', 'draft');
            $manuscripts['pending'] = $this->db->get('manuscript');

            $this->db->where('status', 'draft');
            $this->db->where('user_id', $this->session->userdata('user_id'));
            $manuscripts['active'] = $this->db->get_where('manuscript');
        }
        return $manuscripts;
    }

    public function get_default_sub_category_id($default_cateegory_id) {
        $sub_categories = $this->get_sub_categories($default_cateegory_id);
        foreach ($sub_categories as $sub_category) {
            return $sub_category['id'];
        }
    }

    public function get_researcher_wise_manuscripts($researcher_id = "", $return_as = "") {
        $manuscripts = $this->db->get_where('manuscript', array('user_id' => $researcher_id));
        if ($return_as == 'simple_array') {
            $array = array();
            foreach ($manuscripts->result_array() as $manuscript) {
                if (!in_array($manuscript['id'], $array)) {
                    array_push($array, $manuscript['id']);
                }
            }
            return $array;
        }else {
            return $manuscripts;
        }
    }

    public function get_researcher_wise_payment_history($researcher_id = "") {
        $manuscripts = $this->get_researcher_wise_manuscripts($researcher_id, 'simple_array');
        if (sizeof($manuscripts) > 0) {
            $this->db->where_in('manuscript_id', $manuscripts);
            return $this->db->get('payment')->result_array();
        }else {
            return array();
        }
    }

    public function add_section($manuscript_id) {
        $data['title'] = html_escape($this->input->post('title'));
        $data['manuscript_id'] = $manuscript_id;
        $this->db->insert('section', $data);
        $section_id = $this->db->insert_id();

        $manuscript_details = $this->get_manuscript_by_id($manuscript_id)->row_array();
        $previous_sections = json_decode($manuscript_details['section']);

        if (sizeof($previous_sections) > 0) {
            array_push($previous_sections, $section_id);
            $updater['section'] = json_encode($previous_sections);
            $this->db->where('id', $manuscript_id);
            $this->db->update('manuscript', $updater);
        }else {
            $previous_sections = array();
            array_push($previous_sections, $section_id);
            $updater['section'] = json_encode($previous_sections);
            $this->db->where('id', $manuscript_id);
            $this->db->update('manuscript', $updater);
        }
        // Log the add action
        $this->log_audit_trail($this->session->userdata('user_id'), 'add', 'section', $section_id, 'Section added');
    }

    public function edit_section($section_id) {
        $data['title'] = $this->input->post('title');
        $this->db->where('id', $section_id);
        $this->db->update('section', $data);
        // Log the add action
        $this->log_audit_trail($this->session->userdata('user_id'), 'edit', 'section', $section_id, 'Section updated');
    }

    public function delete_section($manuscript_id, $section_id) {
        $this->db->where('id', $section_id);
        $this->db->delete('section');

        $manuscript_details = $this->get_manuscript_by_id($manuscript_id)->row_array();
        $previous_sections = json_decode($manuscript_details['section']);

        if (sizeof($previous_sections) > 0) {
            $new_section = array();
            for ($i = 0; $i < sizeof($previous_sections); $i++) {
                if ($previous_sections[$i] != $section_id) {
                    array_push($new_section, $previous_sections[$i]);
                }
            }
            $updater['section'] = json_encode($new_section);
            $this->db->where('id', $manuscript_id);
            $this->db->update('manuscript', $updater);
        }
        // Log the add action
        $this->log_audit_trail($this->session->userdata('user_id'), 'delete', 'section', $section_id, 'Section deleted');
    }

    public function get_section($type_by, $id){
        $this->db->order_by("order", "asc");
        if ($type_by == 'manuscript') {
            return $this->db->get_where('section', array('manuscript_id' => $id));
        }elseif ($type_by == 'section') {
            return $this->db->get_where('section', array('id' => $id));
        }
    }

    public function serialize_section($manuscript_id, $serialization) {
        $updater = array(
            'section' => $serialization
        );
        $this->db->where('id', $manuscript_id);
        $this->db->update('manuscript', $updater);
    }

    public function add_lesson() {
        $data['manuscript_id'] = html_escape($this->input->post('manuscript_id'));
        $data['title'] = html_escape($this->input->post('title'));
        $data['section_id'] = html_escape($this->input->post('section_id'));

        $lesson_type_array = explode('-', $this->input->post('lesson_type'));
        $lesson_type = $lesson_type_array[0];

        $data['attachment_type'] = $lesson_type_array[1];
        $data['lesson_type'] = $lesson_type;

        if($lesson_type == 'video') {
            $lesson_provider = $this->input->post('lesson_provider');
            if ($lesson_provider == 'youtube' || $lesson_provider == 'vimeo') {
                if ($this->input->post('video_url') == "" || $this->input->post('duration') == "") {
                    $this->session->set_flashdata('error_message',get_phrase('invalid_lesson_url_and_duration'));
                    redirect(site_url(strtolower($this->session->userdata('role')).'/manuscript_form/manuscript_edit/'.$data['manuscript_id']), 'refresh');
                }
                $data['video_url'] = html_escape($this->input->post('video_url'));

                $duration_formatter = explode(':', $this->input->post('duration'));
                $hour = sprintf('%02d', $duration_formatter[0]);
                $min = sprintf('%02d', $duration_formatter[1]);
                $sec = sprintf('%02d', $duration_formatter[2]);
                $data['duration'] = $hour.':'.$min.':'.$sec;

                $video_details = $this->video_model->getVideoDetails($data['video_url']);
                $data['video_type'] = $video_details['provider'];
            }elseif ($lesson_provider == 'html5') {
                if ($this->input->post('html5_video_url') == "" || $this->input->post('html5_duration') == "") {
                    $this->session->set_flashdata('error_message',get_phrase('invalid_lesson_url_and_duration'));
                    redirect(site_url(strtolower($this->session->userdata('role')).'/manuscript_form/manuscript_edit/'.$data['manuscript_id']), 'refresh');
                }
                $data['video_url'] = html_escape($this->input->post('html5_video_url'));
                $duration_formatter = explode(':', $this->input->post('html5_duration'));
                $hour = sprintf('%02d', $duration_formatter[0]);
                $min = sprintf('%02d', $duration_formatter[1]);
                $sec = sprintf('%02d', $duration_formatter[2]);
                $data['duration'] = $hour.':'.$min.':'.$sec;
                $data['video_type'] = 'html5';
            }else {
                $this->session->set_flashdata('error_message',get_phrase('invalid_lesson_provider'));
                redirect(site_url(strtolower($this->session->userdata('role')).'/manuscript_form/manuscript_edit/'.$data['manuscript_id']), 'refresh');
            }
        }else {
            if ($_FILES['attachment']['name'] == "") {
                $this->session->set_flashdata('error_message',get_phrase('invalid_attachment'));
                redirect(site_url(strtolower($this->session->userdata('role')).'/manuscript_form/manuscript_edit/'.$data['manuscript_id']), 'refresh');
            }else {
                $fileName           = $_FILES['attachment']['name'];
                $tmp                = explode('.', $fileName);
                $fileExtension      = end($tmp);
                $uploadable_file    =  md5(uniqid(rand(), true)).'.'.$fileExtension;
                $data['attachment'] = $uploadable_file;

                if (!file_exists('uploads/lesson_files')) {
                    mkdir('uploads/lesson_files', 0777, true);
                }
                move_uploaded_file($_FILES['attachment']['tmp_name'], 'uploads/lesson_files/'.$uploadable_file);
            }
        }

        $data['date_added'] = strtotime(date('D, d-M-Y'));
        $data['summary'] = $this->input->post('summary');

        $this->db->insert('lesson', $data);
        $inserted_id = $this->db->insert_id();

        if ($_FILES['thumbnail']['name'] != "") {
            if (!file_exists('uploads/thumbnails/lesson_thumbnails')) {
                mkdir('uploads/thumbnails/lesson_thumbnails', 0777, true);
            }
            move_uploaded_file($_FILES['thumbnail']['tmp_name'], 'uploads/thumbnails/lesson_thumbnails/'.$inserted_id.'.jpg');
        }
    }

    public function edit_lesson($lesson_id) {

        $previous_data = $this->db->get_where('lesson', array('id' => $lesson_id))->row_array();

        $data['manuscript_id'] = html_escape($this->input->post('manuscript_id'));
        $data['title'] = html_escape($this->input->post('title'));
        $data['section_id'] = html_escape($this->input->post('section_id'));

        $lesson_type_array = explode('-', $this->input->post('lesson_type'));
        $lesson_type = $lesson_type_array[0];

        $data['attachment_type'] = $lesson_type_array[1];
        $data['lesson_type'] = $lesson_type;

        if($lesson_type == 'video') {
            $lesson_provider = $this->input->post('lesson_provider');
            if ($lesson_provider == 'youtube' || $lesson_provider == 'vimeo') {
                if ($this->input->post('video_url') == "" || $this->input->post('duration') == "") {
                    $this->session->set_flashdata('error_message',get_phrase('invalid_lesson_url_and_duration'));
                    redirect(site_url(strtolower($this->session->userdata('role')).'/manuscript_form/manuscript_edit/'.$data['manuscript_id']), 'refresh');
                }
                $data['video_url'] = html_escape($this->input->post('video_url'));

                $duration_formatter = explode(':', $this->input->post('duration'));
                $hour = sprintf('%02d', $duration_formatter[0]);
                $min = sprintf('%02d', $duration_formatter[1]);
                $sec = sprintf('%02d', $duration_formatter[2]);
                $data['duration'] = $hour.':'.$min.':'.$sec;

                $video_details = $this->video_model->getVideoDetails($data['video_url']);
                $data['video_type'] = $video_details['provider'];
            }elseif ($lesson_provider == 'html5') {
                if ($this->input->post('html5_video_url') == "" || $this->input->post('html5_duration') == "") {
                    $this->session->set_flashdata('error_message',get_phrase('invalid_lesson_url_and_duration'));
                    redirect(site_url(strtolower($this->session->userdata('role')).'/manuscript_form/manuscript_edit/'.$data['manuscript_id']), 'refresh');
                }
                $data['video_url'] = html_escape($this->input->post('html5_video_url'));

                $duration_formatter = explode(':', $this->input->post('html5_duration'));
                $hour = sprintf('%02d', $duration_formatter[0]);
                $min = sprintf('%02d', $duration_formatter[1]);
                $sec = sprintf('%02d', $duration_formatter[2]);
                $data['duration'] = $hour.':'.$min.':'.$sec;
                $data['video_type'] = 'html5';

                if ($_FILES['thumbnail']['name'] != "") {
                    if (!file_exists('uploads/thumbnails/lesson_thumbnails')) {
                        mkdir('uploads/thumbnails/lesson_thumbnails', 0777, true);
                    }
                    move_uploaded_file($_FILES['thumbnail']['tmp_name'], 'uploads/thumbnails/lesson_thumbnails/'.$lesson_id.'.jpg');
                }
            }else {
                $this->session->set_flashdata('error_message',get_phrase('invalid_lesson_provider'));
                redirect(site_url(strtolower($this->session->userdata('role')).'/manuscript_form/manuscript_edit/'.$data['manuscript_id']), 'refresh');
            }
            $data['attachment'] = "";
        }else {
            if ($_FILES['attachment']['name'] != "") {
                // unlinking previous attachments
                if ($previous_data['attachment'] != "") {
                    unlink('uploads/lesson_files/'.$previous_data['attachment']);
                }

                $fileName           = $_FILES['attachment']['name'];
                $tmp                = explode('.', $fileName);
                $fileExtension      = end($tmp);
                $uploadable_file    =  md5(uniqid(rand(), true)).'.'.$fileExtension;
                $data['attachment'] = $uploadable_file;
                $data['video_type'] = "";
                $data['duration'] = "";
                $data['video_url'] = "";
                if (!file_exists('uploads/lesson_files')) {
                    mkdir('uploads/lesson_files', 0777, true);
                }
                move_uploaded_file($_FILES['attachment']['tmp_name'], 'uploads/lesson_files/'.$uploadable_file);
            }
        }

        $data['last_modified'] = strtotime(date('D, d-M-Y'));
        $data['summary'] = $this->input->post('summary');

        $this->db->where('id', $lesson_id);
        $this->db->update('lesson', $data);
    }
    public function delete_lesson($lesson_id) {
        $this->db->where('id', $lesson_id);
        $this->db->delete('lesson');
    }

    public function update_frontend_settings() {
        $data['value'] = html_escape($this->input->post('banner_title'));
        $this->db->where('key', 'banner_title');
        $this->db->update('frontend_settings', $data);

        $data['value'] = html_escape($this->input->post('banner_sub_title'));
        $this->db->where('key', 'banner_sub_title');
        $this->db->update('frontend_settings', $data);


        $data['value'] = $this->input->post('about_us');
        $this->db->where('key', 'about_us');
        $this->db->update('frontend_settings', $data);

        $data['value'] = $this->input->post('terms_and_condition');
        $this->db->where('key', 'terms_and_condition');
        $this->db->update('frontend_settings', $data);

        $data['value'] = $this->input->post('privacy_policy');
        $this->db->where('key', 'privacy_policy');
        $this->db->update('frontend_settings', $data);
        // Log the update action
        $this->log_audit_trail($this->session->userdata('user_id'), 'update', 'frontend_settings', 0, 'Frontend settings updated');
    }

    public function update_dark_logo() {
        if (move_uploaded_file($_FILES['dark_logo']['tmp_name'], 'uploads/system/logo-dark.png')) {
            $this->log_audit_trail($this->session->userdata('user_id'), 'update', 'system', 0, 'Dark logo updated');
        }
    }

    public function update_light_logo() {
        if (move_uploaded_file($_FILES['light_logo']['tmp_name'], 'uploads/system/logo-light.png')) {
            $this->log_audit_trail($this->session->userdata('user_id'), 'update', 'system', 0, 'Light logo updated');
        }
    }

    public function update_frontend_banner() {
        if (move_uploaded_file($_FILES['banner_image']['tmp_name'], 'uploads/system/home-banner.jpg')) {
            $this->log_audit_trail($this->session->userdata('user_id'), 'update', 'system', 0, 'Frontend banner updated');
        }
    }

    public function update_small_logo() {
        if (move_uploaded_file($_FILES['small_logo']['tmp_name'], 'uploads/system/logo-light-sm.png')) {
            $this->log_audit_trail($this->session->userdata('user_id'), 'update', 'system', 0, 'Small logo updated');
        }
    }

    public function update_favicon() {
        if (move_uploaded_file($_FILES['favicon']['tmp_name'], 'uploads/system/favicon.png')) {
            $this->log_audit_trail($this->session->userdata('user_id'), 'update', 'system', 0, 'Favicon updated');
        }
    }

    public function handleWishList($manuscript_id) {
        $wishlists = array();
        $user_details = $this->user_model->get_user($this->session->userdata('user_id'))->row_array();
        if ($user_details['wishlist'] == "") {
            array_push($wishlists, $manuscript_id);
        }else {
            $wishlists = json_decode($user_details['wishlist']);
            if (in_array($manuscript_id, $wishlists)) {
                $container = array();
                foreach ($wishlists as $key) {
                    if ($key != $manuscript_id) {
                        array_push($container, $key);
                    }
                }
                $wishlists = $container;
                // $key = array_search($manuscript_id, $wishlists);
                // unset($wishlists[$key]);
            }else {
                array_push($wishlists, $manuscript_id);
            }
        }

        $updater['wishlist'] = json_encode($wishlists);
        $this->db->where('id', $this->session->userdata('user_id'));
        $this->db->update('users', $updater);
    }

    public function is_added_to_wishlist($manuscript_id = "") {
        if ($this->session->userdata('user_login') == 1) {
            $wishlists = array();
            $user_details = $this->user_model->get_user($this->session->userdata('user_id'))->row_array();
            $wishlists = json_decode($user_details['wishlist']);
            if (in_array($manuscript_id, $wishlists)) {
                return true;
            }else {
                return false;
            }
        }else {
            return false;
        }
    }

    public function getWishLists() {
        $user_details = $this->user_model->get_user($this->session->userdata('user_id'))->row_array();
        return json_decode($user_details['wishlist']);
    }

    public function get_latest_10_manuscript() {
        $this->db->order_by("id", "desc");
        $this->db->limit('10');
        $this->db->where('status', 'active');
        return $this->db->get('manuscript')->result_array();
    }

    public function enrol_student($user_id){
        $purchased_manuscripts = $this->session->userdata('cart_items');
        foreach ($purchased_manuscripts as $purchased_manuscript) {
            $data['user_id'] = $user_id;
            $data['manuscript_id'] = $purchased_manuscript;
            $data['date_added'] = strtotime(date('D, d-M-Y'));
            $this->db->insert('enrol', $data);
        }
    }
    public function enrol_a_student_manually() {
        $data['manuscript_id'] = $this->input->post('manuscript_id');
        $data['user_id']   = $this->input->post('user_id');
        if ($this->db->get_where('enrol', $data)->num_rows() > 0) {
            $this->session->set_flashdata('error_message', get_phrase('student_has_already_been_enrolled_to_this_manuscript'));
        }else {
            $data['date_added'] = strtotime(date('D, d-M-Y'));
            $this->db->insert('enrol', $data);
            $this->session->set_flashdata('flash_message', get_phrase('student_has_been_enrolled_to_that_manuscript'));
        }
    }

    public function enrol_to_free_manuscript($manuscript_id = "", $user_id = "") {
        $manuscript_details = $this->get_manuscript_by_id($manuscript_id)->row_array();
        if ($manuscript_details['is_free_manuscript'] == 1) {
            $data['manuscript_id'] = $manuscript_id;
            $data['user_id']   = $user_id;
            if ($this->db->get_where('enrol', $data)->num_rows() > 0) {
                $this->session->set_flashdata('error_message', get_phrase('student_has_already_been_enrolled_to_this_manuscript'));
            }else {
                $data['date_added'] = strtotime(date('D, d-M-Y'));
                $this->db->insert('enrol', $data);
                $this->session->set_flashdata('flash_message', get_phrase('successfully_enrolled'));
            }
        }else {
            $this->session->set_flashdata('error_message', get_phrase('this_manuscript_is_not_free_at_all'));
            redirect(site_url('home/manuscript/'.slugify($manuscript_details['title']).'/'.$manuscript_id), 'refresh');
        }

    }
    public function manuscript_purchase($user_id, $method, $amount_paid) {
        $purchased_manuscripts = $this->session->userdata('cart_items');
        foreach ($purchased_manuscripts as $purchased_manuscript) {
            $data['user_id'] = $user_id;
            $data['payment_type'] = $method;
            $data['manuscript_id'] = $purchased_manuscript;
            $manuscript_details = $this->get_manuscript_by_id($purchased_manuscript)->row_array();
            if ($manuscript_details['discount_flag'] == 1) {
                $data['amount'] = $manuscript_details['discounted_price'];
            }else {
                $data['amount'] = $manuscript_details['price'];
            }
            if (get_user_role('role_id', $manuscript_details['user_id']) == 1) {
                $data['admin_revenue'] = $data['amount'];
                $data['researcher_revenue'] = 0;
                $data['researcher_payment_status'] = 1;
            }else {
                if (get_settings('allow_researcher') == 1) {
                    $researcher_revenue_percentage = get_settings('researcher_revenue');
                    $data['researcher_revenue'] = ceil(($data['amount'] * $researcher_revenue_percentage) / 100);
                    $data['admin_revenue'] = $data['amount'] - $data['researcher_revenue'];
                }else {
                    $data['researcher_revenue'] = 0;
                    $data['admin_revenue'] = $data['amount'];
                }
                $data['researcher_payment_status'] = 0;
            }
            $data['date_added'] = strtotime(date('D, d-M-Y'));
            $this->db->insert('payment', $data);
        }
    }

    public function get_default_lesson($section_id) {
        $this->db->order_by('order',"asc");
        $this->db->limit(1);
        $this->db->where('section_id', $section_id);
        return $this->db->get('lesson');
    }

    public function get_manuscripts_by_wishlists() {
        $wishlists = $this->getWishLists();
        if (sizeof($wishlists) > 0) {
            $this->db->where_in('id', $wishlists);
            return $this->db->get('manuscript')->result_array();
        }else {
            return array();
        }

    }


    public function get_manuscripts_of_wishlists_by_search_string($search_string) {
        $wishlists = $this->getWishLists();
        if (sizeof($wishlists) > 0) {
            $this->db->where_in('id', $wishlists);
            $this->db->like('title', $search_string);
            return $this->db->get('manuscript')->result_array();
        }else {
            return array();
        }
    }

    public function get_total_duration_of_lesson_by_manuscript_id($manuscript_id) {
        $total_duration = 0;
        $lessons = $this->crud_model->get_lessons('manuscript', $manuscript_id)->result_array();
        foreach ($lessons as $lesson) {
            if ($lesson['lesson_type'] != "other") {
                $time_array = explode(':', $lesson['duration']);
                $hour_to_seconds = $time_array[0] * 60 * 60;
                $minute_to_seconds = $time_array[1] * 60;
                $seconds = $time_array[2];
                $total_duration += $hour_to_seconds + $minute_to_seconds + $seconds;
            }
        }
        return gmdate("H:i:s", $total_duration).' '.get_phrase('hours');
    }

    public function get_total_duration_of_lesson_by_section_id($section_id) {
        $total_duration = 0;
        $lessons = $this->crud_model->get_lessons('section', $section_id)->result_array();
        foreach ($lessons as $lesson) {
            if ($lesson['lesson_type'] != 'other') {
                $time_array = explode(':', $lesson['duration']);
                $hour_to_seconds = $time_array[0] * 60 * 60;
                $minute_to_seconds = $time_array[1] * 60;
                $seconds = $time_array[2];
                $total_duration += $hour_to_seconds + $minute_to_seconds + $seconds;
            }
        }
        return gmdate("H:i:s", $total_duration).' '.get_phrase('hours');
    }

    public function rate($data) {
        if ($this->db->get_where('rating', array('user_id' => $data['user_id'], 'ratable_id' => $data['ratable_id'], 'ratable_type' => $data['ratable_type']))->num_rows() == 0) {
            $this->db->insert('rating', $data);
        }else {
            $checker = array('user_id' => $data['user_id'], 'ratable_id' => $data['ratable_id'], 'ratable_type' => $data['ratable_type']);
            $this->db->where($checker);
            $this->db->update('rating', $data);
        }
    }

    public function get_user_specific_rating($ratable_type = "", $ratable_id = "") {
        return $this->db->get_where('rating', array('ratable_type' => $ratable_type, 'user_id' => $this->session->userdata('user_id'), 'ratable_id' => $ratable_id))->row_array();
    }

    public function get_ratings($ratable_type = "", $ratable_id = "", $is_sum = false) {
        if ($is_sum) {
            $this->db->select_sum('rating');
            return $this->db->get_where('rating', array('ratable_type' => $ratable_type, 'ratable_id' => $ratable_id));

        }else {
            return $this->db->get_where('rating', array('ratable_type' => $ratable_type, 'ratable_id' => $ratable_id));
        }
    }
    public function get_researcher_wise_manuscript_ratings($researcher_id = "", $ratable_type = "", $is_sum = false) {
        $manuscript_ids = $this->get_researcher_wise_manuscripts($researcher_id, 'simple_array');
        if ($is_sum) {
            $this->db->where('ratable_type', $ratable_type);
            $this->db->where_in('ratable_id', $manuscript_ids);
            $this->db->select_sum('rating');
            return $this->db->get('rating');

        }else {
            $this->db->where('ratable_type', $ratable_type);
            $this->db->where_in('ratable_id', $manuscript_ids);
            return $this->db->get('rating');
        }
    }
    public function get_percentage_of_specific_rating($rating = "", $ratable_type = "", $ratable_id = "") {
        $number_of_user_rated = $this->db->get_where('rating', array(
            'ratable_type' => $ratable_type,
            'ratable_id'   => $ratable_id
        ))->num_rows();

        $number_of_user_rated_the_specific_rating = $this->db->get_where( 'rating', array(
            'ratable_type' => $ratable_type,
            'ratable_id'   => $ratable_id,
            'rating'       => $rating
        ))->num_rows();

        //return $number_of_user_rated.' '.$number_of_user_rated_the_specific_rating;
        if ($number_of_user_rated_the_specific_rating > 0) {
            $percentage = ($number_of_user_rated_the_specific_rating / $number_of_user_rated) * 100;
        }else {
            $percentage = 0;
        }
        return floor($percentage);
    }

    ////////private message//////
    function send_new_private_message() {
        $message    = $this->input->post('message');
        $timestamp  = strtotime(date("Y-m-d H:i:s"));

        $receiver   = $this->input->post('receiver');
        $sender     = $this->session->userdata('user_id');

        //check if the thread between those 2 users exists, if not create new thread
        $num1 = $this->db->get_where('message_thread', array('sender' => $sender, 'receiver' => $receiver))->num_rows();
        $num2 = $this->db->get_where('message_thread', array('sender' => $receiver, 'receiver' => $sender))->num_rows();
        if ($num1 == 0 && $num2 == 0) {
            $message_thread_code                        = substr(md5(rand(100000000, 20000000000)), 0, 15);
            $data_message_thread['message_thread_code'] = $message_thread_code;
            $data_message_thread['sender']              = $sender;
            $data_message_thread['receiver']            = $receiver;
            $this->db->insert('message_thread', $data_message_thread);
        }
        if ($num1 > 0)
        $message_thread_code = $this->db->get_where('message_thread', array('sender' => $sender, 'receiver' => $receiver))->row()->message_thread_code;
        if ($num2 > 0)
        $message_thread_code = $this->db->get_where('message_thread', array('sender' => $receiver, 'receiver' => $sender))->row()->message_thread_code;


        $data_message['message_thread_code']    = $message_thread_code;
        $data_message['message']                = $message;
        $data_message['sender']                 = $sender;
        $data_message['timestamp']              = $timestamp;
        $this->db->insert('message', $data_message);

        return $message_thread_code;
    }

    function send_reply_message($message_thread_code) {
        $message    = html_escape($this->input->post('message'));
        $timestamp  = strtotime(date("Y-m-d H:i:s"));
        $sender     = $this->session->userdata('user_id');

        $data_message['message_thread_code']    = $message_thread_code;
        $data_message['message']                = $message;
        $data_message['sender']                 = $sender;
        $data_message['timestamp']              = $timestamp;
        $this->db->insert('message', $data_message);
    }

    function mark_thread_messages_read($message_thread_code) {
        // mark read only the oponnent messages of this thread, not currently logged in user's sent messages
        $current_user = $this->session->userdata('user_id');
        $this->db->where('sender !=', $current_user);
        $this->db->where('message_thread_code', $message_thread_code);
        $this->db->update('message', array('read_status' => 1));
    }

    function count_unread_message_of_thread($message_thread_code) {
        $unread_message_counter = 0;
        $current_user = $this->session->userdata('user_id');
        $messages = $this->db->get_where('message', array('message_thread_code' => $message_thread_code))->result_array();
        foreach ($messages as $row) {
            if ($row['sender'] != $current_user && $row['read_status'] == '0')
            $unread_message_counter++;
        }
        return $unread_message_counter;
    }

    public function get_last_message_by_message_thread_code($message_thread_code) {
        $this->db->order_by('message_id','desc');
        $this->db->limit(1);
        $this->db->where(array('message_thread_code' => $message_thread_code));
        return $this->db->get('message');
    }

    function curl_request($code = '') {

        $product_code = $code;

        $personal_token = "FkA9UyDiQT0YiKwYLK3ghyFNRVV9SeUn";
        $url = "https://api.envato.com/v3/market/author/sale?code=".$product_code;
        $curl = curl_init($url);

        //setting the header for the rest of the api
        $bearer   = 'bearer ' . $personal_token;
        $header   = array();
        $header[] = 'Content-length: 0';
        $header[] = 'Content-type: application/json; charset=utf-8';
        $header[] = 'Authorization: ' . $bearer;

        $verify_url = 'https://api.envato.com/v1/market/private/user/verify-purchase:'.$product_code.'.json';
            $ch_verify = curl_init( $verify_url . '?code=' . $product_code );

            curl_setopt( $ch_verify, CURLOPT_HTTPHEADER, $header );
            curl_setopt( $ch_verify, CURLOPT_SSL_VERIFYPEER, false );
            curl_setopt( $ch_verify, CURLOPT_RETURNTRANSFER, 1 );
            curl_setopt( $ch_verify, CURLOPT_CONNECTTIMEOUT, 5 );
            curl_setopt( $ch_verify, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');

            $cinit_verify_data = curl_exec( $ch_verify );
            curl_close( $ch_verify );

            $response = json_decode($cinit_verify_data, true);

            if (count($response['verify-purchase']) > 0) {
                return true;
            } else {
                return false;
            }
        }


        // version 1.3
        function get_currencies() {
            return $this->db->get('currency')->result_array();
        }

        function get_paypal_supported_currencies() {
            $this->db->where('paypal_supported', 1);
            return $this->db->get('currency')->result_array();
        }

        function get_stripe_supported_currencies() {
            $this->db->where('stripe_supported', 1);
            return $this->db->get('currency')->result_array();
        }

        // version 1.4
        function filter_manuscript($selected_category_id = "", $selected_price = "", $selected_level = "", $selected_language = "", $selected_rating = "", $search_string = "") {
            $manuscript_ids = array();
    
            if ($selected_category_id != "all") {
                $this->db->where('sub_category_id', $selected_category_id);
            }
    
            if ($selected_price != "all") {
                if ($selected_price == "paid") {
                    $this->db->where('is_free_manuscript', 0);
                } elseif ($selected_price == "free") {
                    $this->db->where('is_free_manuscript', 1);
                }
            }
    
            if ($selected_level != "all") {
                $this->db->where('level', $selected_level);
            }
    
            if ($selected_language != "all") {
                $this->db->where('language', $selected_language);
            }
    
            if (!empty($search_string)) {
                $this->db->group_start();
                $this->db->like('title', $search_string);
                $this->db->or_like('description', $search_string);
                $this->db->or_like('meta_keywords', $search_string);
                $this->db->group_end();
            }
    
            $this->db->where('status', 'active');
            $manuscripts = $this->db->get('manuscript')->result_array();
    
            foreach ($manuscripts as $manuscript) {
                if ($selected_rating != "all") {
                    $total_rating = $this->get_ratings('manuscript', $manuscript['id'], true)->row()->rating;
                    $number_of_ratings = $this->get_ratings('manuscript', $manuscript['id'])->num_rows();
                    if ($number_of_ratings > 0) {
                        $average_ceil_rating = ceil($total_rating / $number_of_ratings);
                        if ($average_ceil_rating == $selected_rating) {
                            array_push($manuscript_ids, $manuscript['id']);
                        }
                    }
                } else {
                    array_push($manuscript_ids, $manuscript['id']);
                }
            }
    
            if (count($manuscript_ids) > 0) {
                $this->db->where_in('id', $manuscript_ids);
                return $this->db->get('manuscript')->result_array();
            } else {
                return array();
            }
        }

        public function get_manuscripts($category_id = "", $sub_category_id = "", $researcher_id = 0) {
            if ($category_id > 0 && $sub_category_id > 0 && $researcher_id > 0) {
                return $this->db->get_where('manuscript', array('category_id' => $category_id, 'sub_category_id' => $sub_category_id, 'user_id' => $researcher_id));
            }elseif ($category_id > 0 && $sub_category_id > 0 && $researcher_id == 0) {
                return $this->db->get_where('manuscript', array('category_id' => $category_id, 'sub_category_id' => $sub_category_id));
            }else {
                return $this->db->get('manuscript');
            }
        }

        public function filter_manuscript_for_backend($category_id, $researcher_id, $price, $status) {
            if ($category_id != "all") {
                $this->db->where('sub_category_id', $category_id);
            }

            if ($price != "all") {
                if ($price == "paid") {
                    $this->db->where('is_free_manuscript', null);
                }elseif ($price == "free") {
                    $this->db->where('is_free_manuscript', 1);
                }
            }

            if ($researcher_id != "all") {
                $this->db->where('user_id', $researcher_id);
            }

            if ($status != "all") {
                $this->db->where('status', $status);
            }
            return $this->db->get('manuscript')->result_array();
        }

        public function sort_section($section_json) {
            $sections = json_decode($section_json);
            foreach ($sections as $key => $value) {
                $updater = array(
                    'order' => $key + 1
                );
                $this->db->where('id', $value);
                $this->db->update('section', $updater);
            }
        }

        public function sort_lesson($lesson_json) {
            $lessons = json_decode($lesson_json);
            foreach ($lessons as $key => $value) {
                $updater = array(
                    'order' => $key + 1
                );
                $this->db->where('id', $value);
                $this->db->update('lesson', $updater);
            }
        }
        public function sort_question($question_json) {
            $questions = json_decode($question_json);
            foreach ($questions as $key => $value) {
                $updater = array(
                    'order' => $key + 1
                );
                $this->db->where('id', $value);
                $this->db->update('question', $updater);
            }
        }

        public function get_free_and_paid_manuscripts($price_status = "", $researcher_id = "") {
            $this->db->where('status', 'active');
            if ($price_status == 'free') {
                $this->db->where('is_free_manuscript', 1);
            }else {
                $this->db->where('is_free_manuscript', null);
            }

            if ($researcher_id > 0) {
                $this->db->where('user_id', $researcher_id);
            }
            return $this->db->get('manuscript');
        }

        // Adding quiz functionalities
        public function add_quiz($manuscript_id = "") {
            $data['manuscript_id'] = $manuscript_id;
            $data['title'] = html_escape($this->input->post('title'));
            $data['section_id'] = html_escape($this->input->post('section_id'));

            $data['lesson_type'] = 'quiz';
            $data['duration'] = '00:00:00';
            $data['date_added'] = strtotime(date('D, d-M-Y'));
            $data['summary'] = html_escape($this->input->post('summary'));
            $this->db->insert('lesson', $data);
        }

        // updating quiz functionalities
        public function edit_quiz($lesson_id = "") {
            $data['title'] = html_escape($this->input->post('title'));
            $data['section_id'] = html_escape($this->input->post('section_id'));
            $data['last_modified'] = strtotime(date('D, d-M-Y'));
            $data['summary'] = html_escape($this->input->post('summary'));
            $this->db->where('id', $lesson_id);
            $this->db->update('lesson', $data);
        }

        // Get quiz questions
        public function get_quiz_questions($quiz_id) {
            $this->db->order_by("order", "asc");
            $this->db->where('quiz_id', $quiz_id);
            return $this->db->get('question');
        }

        public function get_quiz_question_by_id($question_id) {
            $this->db->order_by("order", "asc");
            $this->db->where('id', $question_id);
            return $this->db->get('question');
        }

        // Add Quiz Questions
        public function add_quiz_questions($quiz_id) {
            $question_type = $this->input->post('question_type');
            if ($question_type == 'mcq') {
                $response = $this->add_multiple_choice_question($quiz_id);
                return $response;
            }
        }

        public function update_quiz_questions($question_id) {
            $question_type = $this->input->post('question_type');
            if ($question_type == 'mcq') {
                $response = $this->update_multiple_choice_question($question_id);
                return $response;
            }
        }
        // multiple_choice_question crud functions
        function add_multiple_choice_question($quiz_id){
            if (sizeof($this->input->post('options')) != $this->input->post('number_of_options')) {
                return false;
            }
            foreach ($this->input->post('options') as $option) {
                if ($option == "") {
                    return false;
                }
            }
            if (sizeof($this->input->post('correct_answers')) == 0) {
                $correct_answers = [""];
            }
            else{
                $correct_answers = $this->input->post('correct_answers');
            }
            $data['quiz_id']            = $quiz_id;
            $data['title']              = html_escape($this->input->post('title'));
            $data['number_of_options']  = html_escape($this->input->post('number_of_options'));
            $data['type']               = 'multiple_choice';
            $data['options']            = json_encode($this->input->post('options'));
            $data['correct_answers']    = json_encode($correct_answers);
            $this->db->insert('question', $data);
            return true;
        }
        // update multiple choice question
        function update_multiple_choice_question($question_id){
            if (sizeof($this->input->post('options')) != $this->input->post('number_of_options')) {
                return false;
            }
            foreach ($this->input->post('options') as $option) {
                if ($option == "") {
                    return false;
                }
            }

            if (sizeof($this->input->post('correct_answers')) == 0) {
                $correct_answers = [""];
            }
            else{
                $correct_answers = $this->input->post('correct_answers');
            }

            $data['title']              = html_escape($this->input->post('title'));
            $data['number_of_options']  = html_escape($this->input->post('number_of_options'));
            $data['type']               = 'multiple_choice';
            $data['options']            = json_encode($this->input->post('options'));
            $data['correct_answers']    = json_encode($correct_answers);
            $this->db->where('id', $question_id);
            $this->db->update('question', $data);
            return true;
        }

        function delete_quiz_question($question_id) {
            $this->db->where('id', $question_id);
            $this->db->delete('question');
            return true;
        }

        }
