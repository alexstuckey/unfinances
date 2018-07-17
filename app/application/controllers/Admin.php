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

    public function addCostCentre()
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

    // Looks up to see if a cost centre exists
    public function cost_centre_check($str)
    {
        $this->load->model('CostCentre_model');
        $allCostCentres = $this->CostCentre_model->getAllCostCentres();
        foreach ($allCostCentres as $row) {
            if ($row['cost_centre'] == $str) return true;
        }
        $this->form_validation->set_message('cost_centre_check', 'The {field} must be a valid cost centre.');
        return false;
    }

    // Returns true if the username exists on the CIS database, and the user has registered for the app (local)
    public function username_check($str)
    {
        $checkUser = $this->User_model->getUserByCIS($str);
        if (!empty($checkUser)) {
            if ($checkUser['doesUserExist']) {
                return true;
            }
        }

        $this->form_validation->set_message('username_check', 'The {field} must be a valid account.');
        return false;
    }

    public function changeCostCentreManager()
    {

        $data['userAccount'] = $this->User_model->getUserByCIS($_SERVER['REMOTE_USER']);
        if ($data['userAccount']['is_admin'] == false) {
            show_error('You are not permitted to access this page.', 403, "403 Forbidden");
        } else {

            $this->load->library('form_validation');
            $this->form_validation->set_rules('changeCostCentre', 'Cost Centre', 'max_length[255]|callback_cost_centre_check');
            $this->form_validation->set_rules('changeCostCentreManager', 'Manager username', 'trim|required|callback_username_check');
            $this->form_validation->set_error_delimiters('<p class="alert alert-danger"><strong>Error: </strong>', '</p>');
            $this->load->library('session');
            if ($this->form_validation->run() == FALSE) {
                $this->cost_centres();
            } else {
                $this->load->model('CostCentre_model');

                $result = null;
                $result = $this->CostCentre_model->changeManager(
                    $this->input->post('changeCostCentre'),
                    $this->input->post('changeCostCentreManager')
                );

                if ($result) {
                    $this->session->set_flashdata('message', 'Changed cost centre manager!');
                } else {
                    $this->session->set_flashdata('error', 'Failed to change cost centre manager.');
                }
                redirect('/admin/cost_centres');
            }
        }
    }

}
