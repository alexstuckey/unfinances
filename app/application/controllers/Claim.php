<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Claim extends CI_Controller {

    public function newClaim()
    {
        // $this->load->model('User_model');
        // if (!$this->User_model->doesUserExist($_SERVER['REMOTE_USER'])) {
        //     $this->load->helper('url');
        //     redirect('/onboard/welcome');
        // } else if ($this->User_model->isAdmin($_SERVER['REMOTE_USER'])) {
        //     $data['is_admin'] = TRUE;
        // }

        $data['active'] = 'expenses';
        $data['page_title'] = 'UCFinances - New claim';
        
        $this->load->view('header', $data);

        $this->load->view('claim_new', $data);

        $this->load->view('footer', $data);
    }

}
