<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
        $this->load->helper('captcha');
        $this->load->library('session');
        $this->load->database();
        /*cache control*/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
    }

    public function index() {
        $this->generate_captcha();
        $data['captcha_images'] = $this->session->userdata('captcha_image');
        $this->load->view('login', $data);

        if ($this->session->userdata('admin_login')) {
            redirect(site_url('admin'), 'refresh');
        }elseif ($this->session->userdata('user_login')) {
            redirect(site_url('user'), 'refresh');
        }else {
            redirect(site_url('home/login'), 'refresh');
        }
    }
    public function generate_captcha() {
        $vals = array(
            'img_path'   => FCPATH . 'captcha-images/',
            'img_url'    => base_url() . 'captcha-images/',
            'img_width'  => 153.31,
            'img_height' => 44.27,
            'expiration' => 600,
        );

        $cap = create_captcha($vals);

        if ($cap) {
            $this->session->set_userdata('captcha_word', $cap['word']);
            $this->session->set_userdata('captcha_image', $cap['image']);
        }
    }

    public function refresh_captcha() {
        $this->generate_captcha();
        echo $this->session->userdata('captcha_image');
    }
    public function check_captcha_expiration() {
        $captcha_time = $this->session->userdata('captcha_time');
        $expiration_time = 600; // 10 minutes
        if (time() - $captcha_time > $expiration_time) {
            // If the CAPTCHA has expired, delete the image
            $captcha_image = $this->session->userdata('captcha_image');
            if ($captcha_image && file_exists(FCPATH . 'captcha-images/' . $captcha_image)) {
                unlink(FCPATH . 'captcha-images/' . $captcha_image); // Delete expired CAPTCHA image
            }
    
            // Clear CAPTCHA-related session data
            $this->session->unset_userdata('captcha_word');
            $this->session->unset_userdata('captcha_image');
            $this->session->unset_userdata('captcha_time');
        }
    }
    

    public function validate_login($from = "") {
        $email = $this->input->post('email');
        $password = $this->input->post('password');
        $captcha_input = $this->input->post('captcha');
        $captcha_word = $this->session->userdata('captcha_word');
    
        // Check CAPTCHA
        if (strcasecmp($captcha_input, $captcha_word) == 0) {
            $this->session->set_flashdata('error_message', get_phrase('invalid_captcha'));
            redirect(site_url('home/login'), 'refresh');
        }
    
        // Proceed with login validation if CAPTCHA is correct
        $credential = array('email' => $email, 'password' => sha1($password), 'status' => 1);
    
        $query = $this->db->get_where('users', $credential);
        if ($query->num_rows() > 0) {
            $row = $query->row();
    
            $this->session->set_userdata('user_id', $row->id);
            $this->session->set_userdata('role_id', $row->role_id);
            $this->session->set_userdata('role', get_user_role('user_role', $row->id));
            $this->session->set_userdata('name', $row->first_name . ' ' . $row->last_name);
            $this->session->set_flashdata('flash_message', get_phrase('welcome') . ' ' . $row->first_name . ' ' . $row->last_name);
    
            if ($row->role_id == 1) {
                $this->session->set_userdata('admin_login', '1');
                redirect(site_url('admin/dashboard'), 'refresh');
            } elseif ($row->role_id == 2) {
                $this->session->set_userdata('user_login', '1');
                redirect(site_url('home'), 'refresh');
            }
        } else {
            $this->session->set_flashdata('error_message', get_phrase('invalid_login_credentials'));
            redirect(site_url('home/login'), 'refresh');
        }
    }
    

    public function register() {
        $data['first_name'] = html_escape($this->input->post('first_name'));
        $data['last_name']  = html_escape($this->input->post('last_name'));
        $data['email']  = html_escape($this->input->post('email'));
        $data['password']  = sha1($this->input->post('password'));

        $verification_code =  md5(rand(100000000, 200000000));
        $data['verification_code'] = $verification_code;

        if (get_settings('student_email_verification') == 'enable') {
            $data['status'] = 0;
        }else {
            $data['status'] = 1;
        }

        $data['wishlist'] = json_encode(array());
        $data['watch_history'] = json_encode(array());
        
        $social_links = array(
            'facebook' => "",
            'twitter'  => "",
            'linkedin' => ""
        );
        $data['social_links'] = json_encode($social_links);
        $data['role_id']  = 2;

        // Add paypal keys
        $paypal_info = array();
        $paypal['production_client_id'] = "";
        array_push($paypal_info, $paypal);
        $data['paypal_keys'] = json_encode($paypal_info);
        // Add Stripe keys
        $stripe_info = array();
        $stripe_keys = array(
            'public_live_key' => "",
            'secret_live_key' => ""
        );
        array_push($stripe_info, $stripe_keys);
        $data['stripe_keys'] = json_encode($stripe_info);

        $validity = $this->user_model->check_duplication('on_create', $data['email']);
        if ($validity) {
            $user_id = $this->user_model->register_user($data);
            /*$this->session->set_userdata('user_login', '1');
            $this->session->set_userdata('user_id', $user_id);
            $this->session->set_userdata('role_id', 2);
            $this->session->set_userdata('role', get_user_role('user_role', 2));
            $this->session->set_userdata('name', $data['first_name'].' '.$data['last_name']);*/

            if (get_settings('student_email_verification') == 'enable') {
                $this->email_model->send_email_verification_mail($data['first_name'], $data['last_name'], $data['email'], $verification_code);

                $this->session->set_flashdata('flash_message', get_phrase('your_registration_has_been_successfully_done').'. '.get_phrase('please_check_your_mail_inbox_to_verify_your_email_address').'.');
            }else {
                $this->session->set_flashdata('flash_message', get_phrase('your_registration_has_been_successfully_done'));
            }

        }else {
            $this->session->set_flashdata('error_message', get_phrase('email_duplication'));
        }
        redirect(site_url('home'), 'refresh');
    }

    public function logout($from = "") {
        //destroy sessions of specific userdata. We've done this for not removing the cart session
        $this->session_destroy();
        redirect(site_url('home/login'), 'refresh');
    }

    public function session_destroy() {
        $this->session->unset_userdata('user_id');
        $this->session->unset_userdata('role_id');
        $this->session->unset_userdata('role');
        $this->session->unset_userdata('name');
        if ($this->session->userdata('admin_login') == 1) {
            $this->session->unset_userdata('admin_login');
        }else {
            $this->session->unset_userdata('user_login');
        }
    }

    function forgot_password($from = "") {
        $email = $this->input->post('email');
        //resetting user password here
        $new_password = substr( md5( rand(100000000,20000000000) ) , 0,7);

        // Checking credential for admin
        $query = $this->db->get_where('users' , array('email' => $email));
        if ($query->num_rows() > 0)
        {
            $this->db->where('email' , $email);
            $this->db->update('users' , array('password' => sha1($new_password)));
            // send new password to user email
            $this->email_model->password_reset_email($new_password, $email);
            $this->session->set_flashdata('flash_message', get_phrase('please_check_your_email_for_new_password'));
            if ($from == 'backend') {
                redirect(site_url('home/login'), 'refresh');
            }else {
                redirect(site_url('home'), 'refresh');
            }
        }else {
            $this->session->set_flashdata('error_message', get_phrase('password_reset_failed'));
            if ($from == 'backend') {
                redirect(site_url('home/login'), 'refresh');
            }else {
                redirect(site_url('home'), 'refresh');
            }
        }
    }

    public function verify_email_address($verification_code = "") {
        $user_details = $this->db->get_where('users', array('verification_code' => $verification_code));
        if($user_details->num_rows() == 0) {
            $this->session->set_flashdata('error_message', get_phrase('email_duplication'));
        }else {
            $user_details = $user_details->row_array();
            $updater = array(
                'status' => 1
            );
            $this->db->where('id', $user_details['id']);
            $this->db->update('users', $updater);
            $this->session->set_flashdata('flash_message', get_phrase('congratulations').'!'.get_phrase('your_email_address_has_been_successfully_verified').'.');
        }
        redirect(site_url('home'), 'refresh');
    }
}
