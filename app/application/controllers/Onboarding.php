<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Onboarding extends CI_Controller {

    public function welcome()
    {
        $userAccount = $this->User_model->getUserByCIS($_SERVER['REMOTE_USER']);
        if ($userAccount['has_onboarded'] == true) {
            // Already onboarded, redirect to homepage
            $this->load->helper('url');
            redirect('/home');
        }

        $data['active'] = 'welcome';
        $data['page_title'] = 'UCFinances - Welcome';
        $data['hide_links'] = true;
        $data['user'] = $userAccount;

        
        $this->load->view('header', $data);
        $this->load->view('onboarding_welcome', $data);
        $this->load->view('footer', $data);
    }

    public function submit()
    {

    }

}
