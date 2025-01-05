<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Updater extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');

        // Cache control
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
    }

    // Default function, redirects to login page if no admin logged in yet
    public function index() {
        if ($this->session->userdata('admin_login') != 1) {
            redirect(site_url('home/login'), 'refresh');
        } else {
            redirect(site_url('admin/dashboard'), 'refresh');
        }
    }

    // Update product
    public function update($task = '', $purchase_code = '') {
        if ($this->session->userdata('admin_login') != 1) {
            redirect(base_url(), 'refresh');
        }

        // Create update directory
        $dir = 'update';
        if (!is_dir($dir)) mkdir($dir, 0777, true);

        $zipped_file_name = $_FILES["file_name"]["name"];
        $path = 'update/' . $zipped_file_name;

        if (move_uploaded_file($_FILES["file_name"]["tmp_name"], $path)) {
            $zip = new ZipArchive;
            if ($zip->open($path) === TRUE) {
                $zip->extractTo('update');
                $zip->close();
                unlink($path);
            }

            $unzipped_file_name = substr($zipped_file_name, 0, -4);
            $str = file_get_contents('./update/' . $unzipped_file_name . '/update_config.json');
            $json = json_decode($str, true);

            // Run PHP modifications
            require './update/' . $unzipped_file_name . '/update_script.php';

            // Create new directories
            if (!empty($json['directory'])) {
                foreach ($json['directory'] as $directory) {
                    if (!is_dir($directory['name'])) mkdir($directory['name'], 0777, true);
                }
            }

            // Create/Replace new files
            if (!empty($json['files'])) {
                foreach ($json['files'] as $file) {
                    copy($file['root_directory'], $file['update_directory']);
                }
            }

            $this->session->set_flashdata('flash_message', 'Product updated successfully');
            redirect(site_url('admin/system_settings'));
        }
    }
    public function send_smtp_mail($msg=NULL, $sub=NULL, $to=NULL, $from=NULL) {
        // Load email library
        $this->load->library('email');
    
        if($from == NULL)
            $from = $this->db->get_where('settings', array('key' => 'system_email'))->row()->value;
    
        // SMTP & mail configuration
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
    
        $this->email->to($to);
        $this->email->from($from, get_settings('system_name'));
        $this->email->subject($sub);
        $this->email->message($msg);
        
    
        // Send email
        return $this->email->send();
    }
    
    private function send_backup_email($backup_content) {
        // Load Zip library
        $this->load->library('zip');
        
        // Create a temporary file to store the backup content
        $temp_file = tempnam(sys_get_temp_dir(), 'backup_');
        file_put_contents($temp_file, $backup_content);
        
        // Add the backup content file to the zip archive
        $this->zip->read_file($temp_file);
        
        // Create a zip archive
        $zip_file = tempnam(sys_get_temp_dir(), 'backup_') . '.zip';
        $this->zip->archive($zip_file);
        
        // Remove the temporary backup content file
        unlink($temp_file);
        
        $to = 'amanterenz2@gmail.com'; // Replace with recipient email
        $subject = 'Database Backup';
        $message = 'Attached is the database backup file.';
        
        // Email headers
        $boundary = md5(time());
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: multipart/mixed; boundary=\"" . $boundary . "\"\r\n";
        $headers .= "This is a multi-part message in MIME format.\r\n";
        $headers .= "--" . $boundary . "\r\n";
        $headers .= "Content-Type: text/plain; charset=utf-8\r\n";
        $headers .= "Content-Transfer-Encoding: 7bit\r\n";
        $headers .= "\r\n";
        $headers .= $message . "\r\n";
        $headers .= "--" . $boundary . "\r\n";
        $headers .= "Content-Type: application/zip; name=\"" . basename($zip_file) . "\"\r\n";
        $headers .= "Content-Transfer-Encoding: base64\r\n";
        $headers .= "Content-Disposition: attachment; filename=\"" . basename($zip_file) . "\"\r\n";
        $headers .= "\r\n";
        
        // Read the zip file content and encode it in base64
        $attachment_content = chunk_split(base64_encode(file_get_contents($zip_file)));
        
        // Add the base64-encoded zip file content to the email
        $email_body = $headers . $attachment_content . "\r\n";
        $email_body .= "--" . $boundary . "--";
        
        // Call the send_smtp_mail function
        $mail_sent = $this->send_smtp_mail($email_body, $subject, $to);
        
        // Check if email was sent successfully
        if ($mail_sent) {
            $this->session->set_flashdata('flash_message', 'Database backup sent via email successfully.');
        } else {
            $this->session->set_flashdata('flash_message', 'Error sending database backup via email.');
        }
        
        // Remove the temporary zip file
        unlink($zip_file);
        
        redirect(site_url('admin/system_settings'));
    }
    
    
    
    public function backup_database() {
        // Database connection details
        $host = 'localhost';
        $username = 'root';
        $password = '';
        $database_name = 'dblms';
    
        // Create a new instance of the DatabaseOperations class
        $dbOperations = new DatabaseOperations($host, $username, $password, $database_name);
    
        // Call the backupDatabase() method and get the backup content
        $backup_content = $dbOperations->backupDatabase();
    
        // Check if backup content was generated
        if ($backup_content) {
            // Send the backup via email
            $this->send_backup_email($backup_content);
        } else {
            $this->session->set_flashdata('flash_message', 'Error creating backup. No data found.');
            redirect(site_url('admin/system_settings'));
        }
    }
    
    // Restore database
    public function restore_database() {
        // Database connection details
        $host = 'localhost';
        $username = 'root';
        $password = '';
        $database_name = 'dblms';

        // Check if a file has been uploaded
        if (isset($_FILES['sql_file']) && !empty($_FILES['sql_file']['tmp_name'])) {
            // Create a new instance of the DatabaseOperations class
            $dbOperations = new DatabaseOperations($host, $username, $password, $database_name);

            // Call the restoreDatabase() method
            $dbOperations->restoreDatabase($_FILES['sql_file']);
        } else {
            $this->session->set_flashdata('flash_message', 'No file uploaded.');
            redirect(site_url('admin/system_settings'));
        }
    }
}

class DatabaseOperations {
    private $host;
    private $username;
    private $password;
    private $database_name;
    private $conn;

    public function __construct($host, $username, $password, $database_name) {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->database_name = $database_name;
        $this->conn = new mysqli($host, $username, $password, $database_name);
        $this->conn->set_charset("utf8");

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function backupDatabase() {
        $tables = array();
        $sql = "SHOW TABLES";
        $result = $this->conn->query($sql);
    
        while ($row = $result->fetch_row()) {
            $tables[] = $row[0];
        }
    
        $sqlScript = "";
        foreach ($tables as $table) {
            $query = "SHOW CREATE TABLE $table";
            $result = $this->conn->query($query);
            $row = $result->fetch_row();
    
            $sqlScript .= "\n\n" . $row[1] . ";\n\n";
    
            $query = "SELECT * FROM $table";
            $result = $this->conn->query($query);
    
            $columnCount = $result->field_count;
            while ($row = $result->fetch_row()) {
                $sqlScript .= "INSERT INTO $table VALUES(";
                for ($j = 0; $j < $columnCount; $j++) {
                    $row[$j] = $this->conn->real_escape_string($row[$j]);
                    $sqlScript .= isset($row[$j]) ? '"' . $row[$j] . '"' : '""';
                    $sqlScript .= $j < ($columnCount - 1) ? ',' : '';
                }
                $sqlScript .= ");\n";
            }
            $sqlScript .= "\n";
        }
    
        if (!empty($sqlScript)) {
            return $sqlScript;
        } else {
            return false;
        }
    }
    
    public function restoreDatabase($file) {
        $filePath = $file['tmp_name'];
        $fileName = $file['name'];
        $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);

        if ($fileExt !== 'sql') {
            $this->session->set_flashdata('flash_message', 'Invalid file type. Only .sql files are allowed.');
            redirect(site_url('admin/system_settings'));
            exit();
        }

        $sqlScript = file_get_contents($filePath);

        // Disable foreign key checks
        $this->conn->query('SET foreign_key_checks = 0');

        // Drop existing tables
        $tables = $this->conn->query("SHOW TABLES");
        while ($row = $tables->fetch_row()) {
            $this->conn->query("DROP TABLE IF EXISTS $row[0]");
        }

        // Enable foreign key checks
        $this->conn->query('SET foreign_key_checks = 1');

        // Execute queries to restore database
        if ($this->conn->multi_query($sqlScript)) {
            while ($this->conn->more_results() && $this->conn->next_result()) {
                $result = $this->conn->store_result();
                if ($result) $result->free();
            }
            $this->session->set_flashdata('flash_message', "Database restored successfully from: $fileName");
        } else {
            $this->session->set_flashdata('flash_message', 'Error restoring database: ' . $this->conn->error);
        }

        redirect(site_url('admin/system_settings'));
        exit();
    }
}
