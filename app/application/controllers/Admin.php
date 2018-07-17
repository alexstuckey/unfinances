<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller
{

    public function emails()
    {
        $data['userAccount'] = $this->User_model->getUserByCIS($_SERVER['REMOTE_USER']);
        if ($data['userAccount']['is_admin'] == false) {
            show_error('You are not permitted to access this page.', 403, "403 Forbidden");
        } else {

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
    }

    public function emailsEdit()
    {
        $data['userAccount'] = $this->User_model->getUserByCIS($_SERVER['REMOTE_USER']);
        if ($data['userAccount']['is_admin'] == false) {
            show_error('You are not permitted to access this page.', 403, "403 Forbidden");
        } else {

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

    public function settings()
    {
        $data['userAccount'] = $this->User_model->getUserByCIS($_SERVER['REMOTE_USER']);
        if ($data['userAccount']['is_admin'] == false) {
            show_error('You are not permitted to access this page.', 403, "403 Forbidden");
        } else {

            $data['active'] = 'admin';
            $data['active_admin'] = 'settings';
            $data['page_title'] = 'Admin: Settings';

            $data['admins'] = $this->User_model->getAdmins();
            $data['treasurers'] = $this->User_model->getTreasurers();

            $this->load->library('form_validation');
            $this->load->library('session');
            $data['message'] = $this->session->flashdata('message');
            $data['error'] = $this->session->flashdata('error');

            $this->load->view('header', $data);

            $this->load->view('admin_sidebar', $data);
            $this->load->view('admin_page_settings', $data);
            $this->load->view('admin_sidebar_close', $data);

            $this->load->view('footer', $data);
        }
    }

    public function settingsAddAdminOrTreasurer($type)
    {
        // $type = 'admin' or 'treasurer'

        $data['userAccount'] = $this->User_model->getUserByCIS($_SERVER['REMOTE_USER']);
        if ($data['userAccount']['is_admin'] == false) {
            show_error('You are not permitted to access this page.', 403, "403 Forbidden");
        } else {

            $this->load->library('form_validation');
            $this->form_validation->set_rules('usernameAdd', 'New ' . $type . 'username', 'trim|required');
            $this->form_validation->set_error_delimiters('<p class="alert alert-danger"><strong>Error: </strong>', '</p>');
            $this->load->library('session');
            if ($this->form_validation->run() == FALSE) {
                $this->settings();
            } else {
                $this->load->model('User_model');

                $result = null;
                if ($type == 'admin') {
                    $result = $this->User_model->addAdmin($this->input->post('usernameAdd'));
                } else if ($type == 'treasurer') {
                    $result = $this->User_model->addTreasurer($this->input->post('usernameAdd'));
                } else {
                    $this->session->set_flashdata('error', 'No type provided.');
                }

                if ($result) {
                    $this->session->set_flashdata('message', 'Added new ' . $type . '!');
                } else {
                    $this->session->set_flashdata('error', 'Failed to add new ' . $type . '.');
                }
                redirect('/admin/settings');
            }
        }
    }

    public function cost_centres()
    {
        $data['userAccount'] = $this->User_model->getUserByCIS($_SERVER['REMOTE_USER']);
        if ($data['userAccount']['is_admin'] == false) {
            show_error('You are not permitted to access this page.', 403, "403 Forbidden");
        } else {

            $data['active'] = 'admin';
            $data['active_admin'] = 'cost_centres';
            $data['page_title'] = 'Admin: Cost Centres';

            $this->load->model('CostCentre_model');
            $data['cost_centres'] = $this->CostCentre_model->getAllCostCentres();

            $this->load->library('form_validation');
            $this->load->library('session');
            $data['message'] = $this->session->flashdata('message');
            $data['error'] = $this->session->flashdata('error');

            $this->load->view('header', $data);

            $this->load->view('admin_sidebar', $data);
            $this->load->view('admin_page_cost_centres', $data);
            $this->load->view('admin_sidebar_close', $data);

            $this->load->view('footer', $data);
        }
    }

    public function settingsAddCostCentre()
    {

        $data['userAccount'] = $this->User_model->getUserByCIS($_SERVER['REMOTE_USER']);
        if ($data['userAccount']['is_admin'] == false) {
            show_error('You are not permitted to access this page.', 403, "403 Forbidden");
        } else {

            $this->load->library('form_validation');
            $this->form_validation->set_rules('newCostCentreName', 'New Cost Centre Name', 'trim|required');
            $this->form_validation->set_error_delimiters('<p class="alert alert-danger"><strong>Error: </strong>', '</p>');
            $this->load->library('session');
            if ($this->form_validation->run() == FALSE) {
                $this->cost_centres();
            } else {
                $this->load->model('CostCentre_model');

                $result = null;
                $result = $this->CostCentre_model->createNewCostCentre($this->input->post('newCostCentreName'));

                if ($result) {
                    $this->session->set_flashdata('message', 'Created new cost centre!');
                } else {
                    $this->session->set_flashdata('error', 'Failed to create new cost centre.');
                }
                redirect('/admin/cost_centres');
            }
        }
    }

}
