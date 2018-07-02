<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    public function show404()
    {
        show_404();
    }

    public function homepage()
    {
        // $this->load->model('User_model');
        // if (!$this->User_model->doesUserExist($_SERVER['REMOTE_USER'])) {
        //     $this->load->helper('url');
        //     redirect('/onboard/welcome');
        // } else if ($this->User_model->isAdmin($_SERVER['REMOTE_USER'])) {
        //     $data['is_admin'] = TRUE;
        // }

        $data['active'] = 'home';
        $data['page_title'] = 'UCFinances - Home';
        
        $this->load->view('header', $data);

        $this->load->view('homepage', $data);

        $this->load->view('footer', $data);
    }

}
