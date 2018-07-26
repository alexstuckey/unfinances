<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    public function settings()
    {
        $this->load->library('form_validation');

        $data['userAccount'] = $this->User_model->getUserByCIS($_SERVER['REMOTE_USER']);
        if ($data['userAccount']['has_onboarded'] == false) {
            redirect('/onboarding/welcome');
        }

        $data['active'] = 'settings';
        $data['page_title'] = 'UCFinances - Settings';
        $data['javascript_inputmask'] = true;

        $this->load->view('header', $data);
        $this->load->view('user_settings', $data);
        $this->load->view('footer', $data);
    }

}
