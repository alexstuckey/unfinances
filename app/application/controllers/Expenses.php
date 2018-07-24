<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Expenses extends CI_Controller {

    public function my()
    {
        $data['userAccount'] = $this->User_model->getUserByCIS($_SERVER['REMOTE_USER']);
        if ($data['userAccount']['has_onboarded'] == false) {
            redirect('/onboarding/welcome');
        }

        $data['active'] = 'expenses';
        $data['subtitle'] = 'My Expenses';
        $data['page_title'] = 'UCFinances - ' . $data['subtitle'];
        $data['page_lead_text'] = 'View all your expense claims.';
        $data['page_show_claimant_column'] = false;

        $this->load->model('Claim_model');
        $data['claims'] = $this->Claim_model->getClaimsForUser($data['userAccount']['username']);

        $this->load->view('header', $data);

        $this->load->view('expenses_table', $data);

        $this->load->view('footer', $data);
    }

}
