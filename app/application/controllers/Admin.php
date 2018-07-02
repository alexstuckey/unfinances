<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller
{

    public function emails()
    {
        $this->load->model('User_model');
        // if (!$this->User_model->doesUserExist($_SERVER['REMOTE_USER'])) {
        //     $this->load->helper('url');
        //     redirect('/onboard/welcome');
        // } else if ($this->User_model->isAdmin($_SERVER['REMOTE_USER'])) {
        //     $data['is_admin'] = TRUE;
        // } else {
        //     $this->load->helper('url');
        //     redirect('/home');
        // }

        $data['active'] = 'admin';
        $data['active_admin'] = 'email_templates';
        $data['page_title'] = 'Admin: Email Templates';

        $this->load->model('Email_model');
        $data['email_templates'] = $this->Email_model->getAllEmailTemplates();

        $this->load->library('form_validation');
        $this->load->library('session');
        $data['message'] = $this->session->flashdata('message');

        $this->load->view('header', $data);
        /* place content body chunks within content_open and content_close */
        $this->load->view('content_open', $data);

        $this->load->view('admin_sidebar', $data);
        $this->load->view('admin_3_emails', $data);

        $this->load->view('content_close', $data);
        $this->load->view('footer', $data);

    }

    public function emailsEdit()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('email_subject', 'Email Subject', 'trim|required|max_length[255]');
        $this->form_validation->set_rules('email_body', 'Email Body', 'trim|required');
        $this->form_validation->set_error_delimiters('<p class="alert alert-danger"><strong>Error: </strong>', '</p>');

        $this->load->library('session');
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());

            $this->emails();
        } else {
            $this->load->model('Email_model');

            $this->Email_model->updateEmailTemplate(
                $this->input->post('email_name'),
                $this->input->post('email_subject'),
                $this->input->post('email_body')
            );

            $this->session->set_flashdata('message', 'Email updated!');

            $this->emails();

        }
    }

}
