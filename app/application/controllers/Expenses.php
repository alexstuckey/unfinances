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
        // Remove deleted claims
        $data['claims'] = array_filter($data['claims'], function($claim) {
            if ($claim['status'] == ClaimStatus::statusStringToInt('Deleted')) {
                return false;
            } else {
                return true;
            }
        });

        $this->load->view('header', $data);

        $this->load->view('expenses_table', $data);

        $this->load->view('footer', $data);
    }

    public function review()
    {
        $data['userAccount'] = $this->User_model->getUserByCIS($_SERVER['REMOTE_USER']);
        if (!$data['userAccount']['is_treasurer']
         && !$data['userAccount']['is_CostCentreManager']) {
            show_error('You are not permitted to access this page.', 403, '403 Forbidden');
        }

        $data['active'] = 'expenses_review';
        $data['subtitle'] = 'Expenses Review';
        $data['page_title'] = 'UCFinances - ' . $data['subtitle'];
        $data['page_lead_text'] = 'View all expense claims waiting for your review.';
        $data['page_show_claimant_column'] = true;

        $this->load->model('Claim_model');
        $data['claims'] = $this->Claim_model->getClaimsForReviewByUser($data['userAccount']['username']);

        $this->load->view('header', $data);

        $this->load->view('expenses_table', $data);

        $this->load->view('footer', $data);
    }

    public function all()
    {
        $data['userAccount'] = $this->User_model->getUserByCIS($_SERVER['REMOTE_USER']);
        if (!$data['userAccount']['is_treasurer']) {
            show_error('You are not permitted to access this page.', 403, '403 Forbidden');
        }

        $data['active'] = 'expenses_all';
        $data['subtitle'] = 'All Expenses';
        $data['page_title'] = 'UCFinances - ' . $data['subtitle'];
        $data['page_lead_text'] = 'View all expense claims.';
        $data['page_show_claimant_column'] = true;

        $this->load->model('Claim_model');
        $data['claims'] = $this->Claim_model->getAllClaims();

        $this->load->view('header', $data);

        $this->load->view('expenses_table', $data);

        $this->load->view('footer', $data);
    }

}
