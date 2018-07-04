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

    public function showClaim($id_claim, $format)
    {
        $error = null;
        $data = array();

        // $this->load->model('User_model');
        // if (!$this->User_model->doesUserExist($_SERVER['REMOTE_USER'])) {
        //     $this->load->helper('url');
        //     redirect('/onboard/welcome');
        // } else if ($this->User_model->isAdmin($_SERVER['REMOTE_USER'])) {
        //     $data['is_admin'] = TRUE;
        // }


        $this->load->model('Claim_model');
        $data['claim'] = $this->Claim_model->getClaimById($id_claim);

        if (isset($data['claim'])) {
            $data['claimJSON'] = json_encode($data['claim']);
            $data['claimJSON'] = str_replace('\\\\\\', '\\', $data['claimJSON']);

        } else {
            // Couldn't find it
            $error = array("error" => "claim could not be found");
        }



        // RENDER
        if ($format == 'web') {

            if (!isset($error)) {
                $data['active'] = 'expenses';
                $data['page_title'] = 'UCFinances - New claim';

                $this->load->view('header', $data);
                $this->load->view('claim_new', $data);
                $this->load->view('footer', $data);
            } else {
                show_404();
            }

        } else if ($format == 'json') {

            if (!isset($error)) {
                $this->output
                    ->set_status_header(201)
                    ->set_content_type('application/json')
                    ->set_output($data['claimJSON']);

            } else {
                $this->output
                        ->set_status_header(404)
                        ->set_content_type('application/json')
                        ->set_output(json_encode($error));
            }
        }

        
    }

}
