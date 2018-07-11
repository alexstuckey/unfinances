<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Expenses extends CI_Controller {

    public function my()
    {
        // $this->load->model('User_model');
        // if (!$this->User_model->doesUserExist($_SERVER['REMOTE_USER'])) {
        //     $this->load->helper('url');
        //     redirect('/onboard/welcome');
        // } else if ($this->User_model->isAdmin($_SERVER['REMOTE_USER'])) {
        //     $data['is_admin'] = TRUE;
        // }

        $data['active'] = 'expenses';
        $data['page_title'] = 'UCFinances - My Expenses';

        $this->load->model('Claim_model');
        $data['claims'] = $this->Claim_model->getAllClaims();
        
        $this->load->view('header', $data);

        $this->load->view('my_expenses', $data);

        $this->load->view('footer', $data);
    }

}
