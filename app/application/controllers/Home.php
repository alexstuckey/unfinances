<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    public function show404()
    {
        show_404();
    }

    public function homepage()
    {
        $data['userAccount'] = $this->User_model->getUserByCIS($_SERVER['REMOTE_USER']);
        if ($data['userAccount']['doesUserExist'] == false) {
            $this->load->helper('url');
            redirect('/onboarding/welcome');
        }

        $data['active'] = 'home';
        $data['page_title'] = 'UCFinances - Home';
        
        $this->load->view('header', $data);

        $this->load->view('homepage', $data);

        $this->load->view('footer', $data);
    }

}
