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

        $this->load->view('admin_sidebar', $data);
        $this->load->view('admin_page_emails', $data);
        $this->load->view('admin_sidebar_close', $data);

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

    public function settings()
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
        $data['active_admin'] = 'settings';
        $data['page_title'] = 'Admin: Settings';

        $data['admins'] = $this->User_model->getAdmins();

        $this->load->library('form_validation');
        $this->load->library('session');
        $data['message'] = $this->session->flashdata('message');

        $this->load->view('header', $data);

        $this->load->view('admin_sidebar', $data);
        $this->load->view('admin_page_settings', $data);
        $this->load->view('admin_sidebar_close', $data);

        $this->load->view('footer', $data);
    }

    public function settingsAddAdmin()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('adminUsernameAdd', 'Admin username', 'trim|required');
        $this->form_validation->set_error_delimiters('<p class="alert alert-danger"><strong>Error: </strong>', '</p>');
        $this->load->library('session');
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            $this->settings();
        } else {
            $this->load->model('User_model');
            if ($this->User_model->addAdmin($this->input->post('adminUsernameAdd'))) {
                $this->session->set_flashdata('message', 'Admin added!');
            } else {
                $this->session->set_flashdata('error', 'Admin adding failed!');
            }
            $this->settings();
        }
    }

}
