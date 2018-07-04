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

        $this->load->model('Claim_model');
        $id_claim = $this->Claim_model->createClaim($_SERVER['REMOTE_USER']);

        $this->load->helper('url');
        redirect('/expenses/claim/' . $id_claim);
    }

    public function showClaim($id_claim)
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
        $this->load->model('Claim_model');
        $data['claim'] = $this->Claim_model->getClaimById($id_claim);
        // WORKAROUND: https://stackoverflow.com/a/6745634/298051
        // Due to embedding JSON in JSON, the slashes are double escaped.
        // Would use JSON_UNESCAPED_SLASHES option, but not available in targetted PHP 5.3
        // So instead a find and replace is needed.
        $data['claimJSON'] = json_encode($data['claim']);
        $data['claimJSON'] = str_replace('\\\\\\', '\\', $data['claimJSON']);
        
        $this->load->view('header', $data);

        $this->load->view('claim_new', $data);

        $this->load->view('footer', $data);
    }

    public function jsonGetClaim($id_claim)
    {
        $error = null;

        // auth


        $this->load->model('Claim_model');
        $data['claim'] = $this->Claim_model->getClaimById($id_claim);

        if (isset($data['claim'])) {
            $data['claimJSON'] = json_encode($data['claim']);
            $data['claimJSON'] = str_replace('\\\\\\', '\\', $data['claimJSON']);

            $this->output
                    ->set_status_header(201)
                    ->set_content_type('application/json')
                    ->set_output($data['claimJSON']);
        } else {
            // Couldn't find it
            $error = array("error" => "claim could not be found");
        }

        if (isset($error)) {
            $this->output
                    ->set_status_header(404)
                    ->set_content_type('application/json')
                    ->set_output(json_encode($error));
        }

        
    }

}
