<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Email_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

    function password_reset_email($new_password = '' , $email = '')
    {
        $query = $this->db->get_where('users' , array('email' => $email));
        if($query->num_rows() > 0)
        {
            $user = $query->row();
            $first_name = ucfirst($user->first_name);

            $email_msg = "<html><body style='font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; display: flex; justify-content: center; align-items: center; height: 100vh;'>";
            $email_msg .= "<div style='width: 100%; background-color: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center;'>";
            $email_msg .= "<b>Dear " . $first_name . ",</b>";
            $email_msg .= "<p>Your password has been changed.</p>";
            $email_msg .= "<p>Your new password is: <b>".$new_password."</b></p>";
            $email_msg .= "<p>If you did not request this change, please contact our support team immediately at <a href='mailto:support@tris.com'>support@tris.com</a>.</p>";
            $email_msg .= "</div></body></html>";

            $email_sub = "Password reset request";
            $email_to = $email;
            $this->send_smtp_mail($email_msg , $email_sub , $email_to);
            return true;
        }
        else
        {
            return false;
        }
    }


	public function send_email_verification_mail($first_name = "", $last_name = "", $to = "", $verification_code = "") {
		$redirect_url = site_url('login/verify_email_address/'.$verification_code);
		$subject      = "Activate Your Account - Verify Your Email Address";
	
		// HTML message content with full centering
		$email_msg = "<html><body style='font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; display: flex; justify-content: center; align-items: center; height: 100vh;'>";
		$email_msg .= "<div style='width: 100%; background-color: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center;'>";
	
		// Personalized greeting using first name and last name
		$email_msg .= "<b>Dear " . ucfirst($first_name) . " " . ucfirst($last_name) . ",</b>";
		$email_msg .= "<p>Welcome to the Technological Research Information System (TRIS)!</p>";
		$email_msg .= "<p>Please confirm your email address to activate your account.</p>";
	
		// Centered confirmation button
		$email_msg .= "<p><a href='".$redirect_url."' style='display: inline-block; padding: 10px 20px; background-color: #28a745; color: white; text-align: center; text-decoration: none; border-radius: 5px; font-size: 16px;'>Confirm Email Address</a></p>";
	
		// Additional details (email and support)
		$email_msg .= "<p>Your login email address: <b>".$to."</b></p>";
		$email_msg .= "<p>If you have trouble activating your account, please reach out to our support team at <a href='mailto:support@tris.com'>support@tris.com</a> for assistance.</p>";
		$email_msg .= "<p>By confirming your email address, you also complete your subscription to marketing emails.</p>";
		$email_msg .= "</div></body></html>";
	
		// Send the email using the existing method
		$this->send_smtp_mail($email_msg, $subject, $to);
	}
	
	

    public function send_mail_on_manuscript_status_changing($manuscript_id = "", $mail_subject = "", $mail_body = "") {
        $researcher_id		 = 0;
        $manuscript_details    = $this->crud_model->get_manuscript_by_id($manuscript_id)->row_array();
        if ($manuscript_details['user_id'] != "") {
            $researcher_id = $manuscript_details['user_id'];
        }else {
            $researcher_id = $this->session->userdata('user_id');
        }
        $instuctor_details = $this->user_model->get_all_user($researcher_id)->row_array();
        $email_from = get_settings('system_email');

        // HTML message content with full centering
        $email_msg = "<html><body style='font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; display: flex; justify-content: center; align-items: center; height: 100vh;'>";
        $email_msg .= "<div style='width: 100%; background-color: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center;'>";

        // Personalized greeting using first name
        $email_msg .= "<b>Dear " . ucfirst($instuctor_details['first_name']) . ",</b>";
        $email_msg .= "<p>" . $mail_body . "</p>";
        $email_msg .= "</div></body></html>";

        $this->send_smtp_mail($email_msg, $mail_subject, $instuctor_details['email'], $email_from);
    }

	public function send_smtp_mail($msg=NULL, $sub=NULL, $to=NULL, $from=NULL) {
		//Load email library
		$this->load->library('email');

		if($from == NULL)
			$from		=	$this->db->get_where('settings' , array('key' => 'system_email'))->row()->value;

		//SMTP & mail configuration
		$config = array(
			'protocol'  => get_settings('protocol'),
			'smtp_host' => get_settings('smtp_host'),
			'smtp_port' => get_settings('smtp_port'),
			'smtp_user' => get_settings('smtp_user'),
			'smtp_pass' => get_settings('smtp_pass'),
			'mailtype'  => 'html',
			'charset'   => 'utf-8'
		);
		$this->email->initialize($config);
		$this->email->set_mailtype("html");
		$this->email->set_newline("\r\n");

		$htmlContent = $msg;

		$this->email->to($to);
		$this->email->from($from, get_settings('system_name'));
		$this->email->subject($sub);
		$this->email->message($htmlContent);

		//Send email
		$this->email->send();
	}
}
