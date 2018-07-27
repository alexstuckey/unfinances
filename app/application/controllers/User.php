<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    public function getUser($id_cis)
    {
        $data['userAccount'] = $this->User_model->getUserByCIS($_SERVER['REMOTE_USER']);
        if ($data['userAccount']['is_admin'] == true) {
            $requestedUser = $this->User_model->getUserByCIS($id_cis);
        
            $this->output
                    ->set_status_header(201)
                    ->set_content_type('application/json')
                    ->set_output(json_encode($requestedUser));
        } else {
            $this->output
                    ->set_status_header(403)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(array( 'error' => 'Not admin, access denied.')));
        }
    }

    public function settings()
    {
        $this->load->library('form_validation');
        $this->load->library('session');

        $data['userAccount'] = $this->User_model->getUserByCIS($_SERVER['REMOTE_USER']);
        if ($data['userAccount']['has_onboarded'] == false) {
            redirect('/onboarding/welcome');
        }

        $data['active'] = 'settings';
        $data['page_title'] = 'UCFinances - Settings';
        $data['javascript_inputmask'] = true;
        $data['message'] = $this->session->flashdata('message');
        $data['error'] = $this->session->flashdata('error');

        $this->load->view('header', $data);
        $this->load->view('user_settings', $data);
        $this->load->view('footer', $data);
    }

    public function submitSettings()
    {
        $this->load->library('form_validation');
        $this->load->library('session');

        $userAccount = $this->User_model->getUserByCIS($_SERVER['REMOTE_USER']);
        $data['userAccount'] = $this->User_model->getUserByCIS($_SERVER['REMOTE_USER']);
        if ($data['userAccount']['has_onboarded'] == false) {
            redirect('/onboarding/welcome');
        }

        $this->form_validation->set_rules('settings_input_dob', 'Date of Birth', array(
            'required',
            'regex_match[/^(\d{4})-([0]\d|1[0-2])-([0-2]\d|3[01])$/]'
        ));
        $this->form_validation->set_rules('settings_input_account_number', 'Account Number', array(
            'required',
            'regex_match[/^(\d{8})$/]'
        ));
        $this->form_validation->set_rules('settings_input_sort_code', 'Sort Code', array(
            'required',
            'regex_match[/^(\d{6})$/]'
        ));
        $this->form_validation->set_error_delimiters('<p class="alert alert-danger"><strong>Error: </strong>', '</p>');

        if ($this->form_validation->run() == FALSE) {
            $this->settings();
        } else {
            // Update DB
            $resp = $this->User_model->updateAccountDetails(
                $data['userAccount']['username'],
                $this->input->post('settings_input_dob'),
                $this->input->post('settings_input_account_number'),
                $this->input->post('settings_input_sort_code')
            );
            $this->session->set_flashdata('message', 'Account details updated!');

            redirect('/settings');
        }
    }

}
