<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Onboarding extends CI_Controller {

    public function welcome()
    {
        $this->load->helper('url');
        $this->load->library('form_validation');

        $userAccount = $this->User_model->getUserByCIS($_SERVER['REMOTE_USER']);
        if ($userAccount['has_onboarded'] == true) {
            // Already onboarded, redirect to homepage
            redirect('/home');
        }

        $data['active'] = 'welcome';
        $data['page_title'] = 'UCFinances - Welcome';
        $data['hide_links'] = true;
        $data['user'] = $userAccount;
        $data['javascript_inputmask'] = true;

        $this->User_model->createUser($_SERVER['REMOTE_USER']);

        $this->load->view('header', $data);
        $this->load->view('onboarding_welcome', $data);
        $this->load->view('footer', $data);
    }

    public function submit()
    {
        $this->load->helper('url');
        $this->load->library('form_validation');

        $userAccount = $this->User_model->getUserByCIS($_SERVER['REMOTE_USER']);
        if ($userAccount['has_onboarded'] == true) {
            // Already onboarded, redirect to homepage
            redirect('/home');
        }

        $this->form_validation->set_rules('onboarding_input_dob', 'Date of Birth', array(
            'required',
            'regex_match[/^(\d{4})-([0]\d|1[0-2])-([0-2]\d|3[01])$/]'
        ));
        // $this->form_validation->set_rules('onboarding_input_account_number', 'Account Number', array(
        //     'required',
        //     'regex_match[/^(\d{8})$/]'
        // ));
        // $this->form_validation->set_rules('onboarding_input_sort_code', 'Sort Code', array(
        //     'required',
        //     'regex_match[/^(\d{6})$/]'
        // ));

        if ($this->form_validation->run() == FALSE) {
            $this->welcome();
        } else {
            // Update DB
            $resp = $this->User_model->completeOnboardingWithDetails(
                $_SERVER['REMOTE_USER'],
                $this->input->post('onboarding_input_dob')
            );

            redirect('/home');
        }
    }

}
