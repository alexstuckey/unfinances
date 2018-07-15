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
        $userAccount = $this->User_model->getUserByCIS($_SERVER['REMOTE_USER']);
        if ($userAccount['doesUserExist'] == false) {
            $this->load->helper('url');
            redirect('/onboard/welcome');
        }

        $error = null;
        $data = array();


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

                $this->load->model('CostCentre_model');
                $data['cost_centres'] = $this->CostCentre_model->getAllCostCentres();

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

    // Looks up to see if a cost centre exists
    public function cost_centre_check($str)
    {
        $this->load->model('CostCentre_model');
        $allCostCentres = $this->CostCentre_model->getAllCostCentres();
        foreach ($allCostCentres as $row) {
            if ($row['cost_centre'] == $str) return true;
        }
        $this->form_validation->set_message('cost_centre_check', 'The {field} must be a valid cost centre.');
        return false;
    }

    public function saveClaimByJSON($id_claim)
    {
        $userAccount = $this->User_model->getUserByCIS($_SERVER['REMOTE_USER']);
        if ($userAccount['doesUserExist'] == false) {
            $this->load->helper('url');
            redirect('/onboard/welcome');
        }

        $error = array(
            'error' => false,
            'message' => '',
            'error_code' => ''
        );
        $data = array();

        $this->load->model('Claim_model');
        $data['claim'] = $this->Claim_model->getClaimById($id_claim);

        if (isset($data['claim'])) {
            $data['claimJSON'] = json_encode($data['claim']);
            $data['claimJSON'] = str_replace('\\\\\\', '\\', $data['claimJSON']);

        } else {
            // Couldn't find it
            $error['error'] = true;
            $error['error_code'] = 404;
            $error['message'] = "claim could not be found";
        }

        // get input
        $this->load->library('form_validation');
        $this->form_validation->set_rules('description', 'Description', 'trim|max_length[255]');
        $this->form_validation->set_rules('cost_centre', 'Cost Centre', 'max_length[255]|callback_cost_centre_check');
        $this->form_validation->set_rules('expenditure_items', 'Expenditure Items', '');

        if ($this->form_validation->run() == FALSE) {
            $error['error'] = $this->form_validation->error_array();
        } else {
            $this->load->model('Claim_model');
            // check if claim is allowed to be updated
            $updateDBAttempt = $this->Claim_model->updateClaimAsUser(
                                    $_SERVER['REMOTE_USER'],
                                    $id_claim, $this->input->post('description'),
                                    $this->input->post('cost_centre'),
                                    $this->input->post('expenditure_items')
                                );
            if ($updateDBAttempt['success'] == false) {
                $error['error'] = true;
                $error['error_code'] = 403;
                $error['message'] = $updateDBAttempt['message'];
            }
        }


        if (!$error['error']) {
            $this->output
                ->set_status_header(201)
                ->set_content_type('application/json')
                ->set_output(json_encode(array('success')));

        } else {
            $this->output
                    ->set_status_header($error['error_code'])
                    ->set_content_type('application/json')
                    ->set_output(json_encode($error['message']));
        }
    }

}
