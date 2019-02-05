<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Email_model extends CI_Model {


    //Loads the database using the ../config/database.php file
    public function __construct()	{
        $this->load->database();
    }

    public function getAllEmailTemplates()
    {
        $query = $this->db->get('email_templates');

        return $query->result_array();
    }

    public function updateEmailTemplate($email_name, $email_subject, $email_body)
    {
        $this->db->where('email_name', $email_name);
        $this->db->set('email_subject', $email_subject);
        $this->db->set('email_body', $email_body);
        $this->db->update('email_templates');
    }

    public function getEmailByName($email_name)
    {
        $this->db->where('email_name', $email_name);
        $query = $this->db->get('email_templates');

        return $query->row_array();
    }

    public function sendEmail($email_name, $to, $substitutions, $attachmentBuffer = null, $attachmentName = "", $cc = array())
    {
        $this->load->library('email');
        $this->load->library('parser');

        $email = $this->Email_model->getEmailByName($email_name);
        $email_template_body = $email['email_body'];
        $email_template_subject = $email['email_subject'];

        $email_body = $this->parser->parse_string($email_template_body, $substitutions, TRUE);
        $email_subject = $this->parser->parse_string($email_template_subject, $substitutions, TRUE);

        $this->email->from($this->config->item('email_from'), 'UCFinances');
        $this->email->to($to);

        if (count($cc) > 0) {
            $this->email->cc($cc);
        }

        $this->email->subject($email_subject);
        $this->email->message($email_body);

        if ($attachmentBuffer != null) {
            $this->email->attach($attachmentBuffer, 'attachment', $attachmentName, 'application/pdf');
        }

        $this->email->send();
        $this->email->clear();
    }


}
