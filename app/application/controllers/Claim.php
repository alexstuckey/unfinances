<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Claim extends CI_Controller {

    public function newClaim()
    {
        $data['userAccount'] = $this->User_model->getUserByCIS($_SERVER['REMOTE_USER']);
        if ($data['userAccount']['has_onboarded'] == false) {
            redirect('/onboarding/welcome');
        }

        $this->load->model('Claim_model');
        $id_claim = $this->Claim_model->createClaim($data['userAccount']['username']);

        $this->load->model('Activity_model');
        $this->Activity_model->createOnClaimID($id_claim, $data['userAccount']['username']);

        $this->load->helper('url');
        redirect('/expenses/claim/' . $id_claim);
    }

    public function showClaim($id_claim, $format)
    {
        $data['userAccount'] = $this->User_model->getUserByCIS($_SERVER['REMOTE_USER']);
        if ($data['userAccount']['has_onboarded'] == false) {
            redirect('/onboarding/welcome');
        }

        $error = array(
            'error' => false,
            'message' => '',
            'error_code' => ''
        );

        $this->load->model('Claim_model');
        $data['claim'] = $this->Claim_model->getClaimById($id_claim);

        if (isset($data['claim'])) {
            if (
                $data['claim']['claimant_id'] == $data['userAccount']['username']
             || $data['userAccount']['is_admin']
             || $data['userAccount']['is_treasurer']
             || array_reduce($data['userAccount']['managerOfCostCentres'], function ($carry, $item) use ($data) { return ($carry || ($item['cost_centre'] == $data['claim']['cost_centre'])); }, false)
                ) {
                $data['claimJSON'] = json_encode($data['claim']);
                $data['claimJSON'] = str_replace('\\\\\\', '\\', $data['claimJSON']);
            } else {
                // Not that user's claim
                $error = array(
                    'error' => true,
                    'message' => 'You are not permitted to access this claim.',
                    'error_code' => 403
                );
            }

        } else {
            // Couldn't find it
            $error = array(
                'error' => true,
                'message' => 'claim could not be found',
                'error_code' => 404
            );
        }



        // RENDER
        if ($format == 'web') {

            if (!$error['error']) {
                $data['active'] = 'expenses';
                $data['page_title'] = 'UCFinances - New claim';
                $data['javascript_jsgrid'] = true;
                $data['javascript_uppy'] = true;

                $this->load->model('CostCentre_model');
                $data['cost_centres'] = $this->CostCentre_model->getAllCostCentres();

                $this->load->view('header', $data);
                $this->load->view('show_claim', $data);
                $this->load->view('footer', $data);
            } else {
                if ($error['error_code'] == 404) {
                    show_404();
                } else {
                    show_error($error['message'], $error['error_code'], "403 Forbidden");
                }
            }

        } else if ($format == 'json') {

            if (!$error['error']) {
                $this->output
                    ->set_status_header(201)
                    ->set_content_type('application/json')
                    ->set_output($data['claimJSON']);

            } else {
                $this->output
                        ->set_status_header($error['error_code'])
                        ->set_content_type('application/json')
                        ->set_output(json_encode($error['message']));
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
        $error = array(
            'error' => false,
            'message' => '',
            'error_code' => ''
        );
        $data = array();

        $data['userAccount'] = $this->User_model->getUserByCIS($_SERVER['REMOTE_USER']);
        if ($data['userAccount']['has_onboarded'] == false) {
            $error = array(
                'error' => false,
                'message' => 'You have not yet registered.',
                'error_code' => 403
            );
        }


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

    public function submitClaimByJSON($id_claim)
    {
        // This will take a DRAFT or BOUNCED claim and set it to REVIEW, then notify the required people.
        // Curently REVIEW means CostCentreReview

        $error = array(
            'error' => false,
            'message' => '',
            'error_code' => ''
        );
        $data = array();

        $data['userAccount'] = $this->User_model->getUserByCIS($_SERVER['REMOTE_USER']);
        if ($data['userAccount']['has_onboarded'] == false) {
            $error = array(
                'error' => false,
                'message' => 'You have not yet registered.',
                'error_code' => 403
            );
        } else {

            $this->load->model('Claim_model');
            $data['claim'] = $this->Claim_model->getClaimById($id_claim);

            if (isset($data['claim'])) {

                // Check claim is of the correct status to be edited
                if ($data['claim']['isEditable']) {

                    // Check permissions
                    if ($data['claim']['claimant_id'] == $data['userAccount']['username']) {

                        // check if claim is allowed to be updated
                        $updateDBAttempt = $this->Claim_model->changeClaimStatus(
                                                $id_claim,
                                                ClaimStatus::statusStringToInt('CostCentreReview')
                                            );
                        if ($updateDBAttempt) {
                            $this->load->model('Activity_model');
                            $this->Activity_model->changeStatusOnClaimID(
                                $id_claim,
                                $data['userAccount']['username'],
                                $data['claim']['status'],
                                ClaimStatus::statusStringToInt('CostCentreReview')
                            );

                            /// notify claim centre manager

                        } else {
                            $error = array(
                                'error' => true,
                                'message' => 'The claim failed to be updated.',
                                'error_code' => 400
                            );
                        }
                    } else {
                        $error = array(
                            'error' => true,
                            'message' => 'You are not the owner of this claim.',
                            'error_code' => 403
                        );
                    }

                } else {
                    $error = array(
                        'error' => true,
                        'message' => 'Claim is not editable',
                        'error_code' => 403
                    );
                }

            } else {
                // Couldn't find it
                $error = array(
                    'error' => true,
                    'message' => 'Claim does not exist.',
                    'error_code' => 404
                );
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

    public function commentClaimByJSON($id_claim)
    {
        $error = array(
            'error' => false,
            'message' => '',
            'error_code' => ''
        );
        $data = array();

        $data['userAccount'] = $this->User_model->getUserByCIS($_SERVER['REMOTE_USER']);
        if ($data['userAccount']['has_onboarded'] == false) {
            $error = array(
                'error' => false,
                'message' => 'You have not yet registered.',
                'error_code' => 403
            );
        }


        $this->load->model('Claim_model');
        $data['claim'] = $this->Claim_model->getClaimById($id_claim);

        if (isset($data['claim'])) {
        } else {
            // Couldn't find it
            $error = array(
                'error' => true,
                'message' => 'Claim does not exist',
                'error_code' => 404
            );
        }

        // get input
        $this->load->library('form_validation');
        $this->form_validation->set_rules('comment_field', 'Comment Field', 'trim|max_length[255]');

        if ($this->form_validation->run() == FALSE) {
            $error['error'] = $this->form_validation->error_array();
        } else {
            $this->load->model('Activity_model');
            // check if claim is allowed to be updated
            $updateDBAttempt = $this->Activity_model->commentOnClaimID(
                                    $id_claim,
                                    $data['userAccount']['username'],
                                    $this->input->post('comment_field')
                                );
            if ($updateDBAttempt['success'] == false) {
                $error = array(
                    'error' => true,
                    'message' => $updateDBAttempt['message'],
                    'error_code' => 403
                );
                 if ($updateDBAttempt['message'] == 'This claim does not exist.') {
                    $error['error_code'] = 404;
                 }
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

    public function reviewClaimByJSON($id_claim, $review_type, $review_decision)
    {
        // This will take a claims in REVIEWand set it to either Bounced, Rejected, or TreasurerReview, then notify the required people.
        // Curently REVIEW means CostCentreReview or TreasurerReview

        $error = array(
            'error' => false,
            'message' => '',
            'error_code' => ''
        );
        $data = array();

        $data['userAccount'] = $this->User_model->getUserByCIS($_SERVER['REMOTE_USER']);
        if ($data['userAccount']['has_onboarded'] == false) {
            $error = array(
                'error' => true,
                'message' => 'You have not yet registered.',
                'error_code' => 403
            );
        } else {

            if (
                ($review_type == 'cost_centre' && ($review_decision == 'bounce' || $review_decision == 'approve'))
             || ($review_type == 'treasurer'   && ($review_decision == 'bounce' || $review_decision == 'approve' || $review_decision == 'reject' || $review_decision == 'pay'))
            ) {

                $this->load->model('Claim_model');
                $data['claim'] = $this->Claim_model->getClaimById($id_claim);

                if (isset($data['claim'])) {

                    // Check claim is of the correct status to be edited
                    $is_authorised = false;
                    $new_status = null;
                    if (
                        $review_type == 'cost_centre'
                     && array_reduce($data['userAccount']['managerOfCostCentres'], function ($carry, $item) use ($data) { return ($carry || ($item['cost_centre'] == $data['claim']['cost_centre'])); }, false)
                     && $data['claim']['status'] == ClaimStatus::statusStringToInt('CostCentreReview')
                        ) {

                        $is_authorised = true;
                        if ($review_decision == 'bounce') {
                            $new_status = ClaimStatus::statusStringToInt('Bounced');
                        } else if ($review_decision == 'approve') {
                            $new_status = ClaimStatus::statusStringToInt('TreasurerReview');
                        }
                        

                    } else if (
                        $review_type == 'treasurer'
                     && $data['userAccount']['is_treasurer']
                     && $data['claim']['status'] == ClaimStatus::statusStringToInt('TreasurerReview')
                        ) {

                        $is_authorised = true;
                        if ($review_decision == 'bounce') {
                            $new_status = ClaimStatus::statusStringToInt('Bounced');
                        } else if ($review_decision == 'approve') {
                            $new_status = ClaimStatus::statusStringToInt('Approved');
                        } else if ($review_decision == 'reject') {
                            $new_status = ClaimStatus::statusStringToInt('Rejected');
                        } else if ($review_decision == 'pay') {
                            $new_status = ClaimStatus::statusStringToInt('Paid');
                        }
                    } else if (
                        $review_type == 'treasurer'
                     && $data['userAccount']['is_treasurer']
                     && $data['claim']['status'] == ClaimStatus::statusStringToInt('Approved')
                        ) {

                        $is_authorised = true;
                        if ($review_decision == 'pay') {
                            $new_status = ClaimStatus::statusStringToInt('Paid');
                        }
                    }


                    if ($is_authorised) {

                        // check if claim is allowed to be updated
                        $updateDBAttempt = $this->Claim_model->changeClaimStatus(
                                                $id_claim,
                                                $new_status
                                            );
                        if ($updateDBAttempt) {
                            $this->load->model('Activity_model');
                            $this->Activity_model->changeStatusOnClaimID(
                                $id_claim,
                                $data['userAccount']['username'],
                                $data['claim']['status'],
                                $new_status
                            );

                            /// notify claim centre manager

                        } else {
                            $error = array(
                                'error' => true,
                                'message' => 'The claim failed to be updated.',
                                'error_code' => 400
                            );
                        }

                    } else {
                        $error = array(
                            'error' => true,
                            'message' => 'User not authorised to review this claim.',
                            'error_code' => 403
                        );
                    }

                } else {
                    // Couldn't find it
                    $error = array(
                        'error' => true,
                        'message' => 'Claim does not exist.',
                        'error_code' => 404
                    );
                }
            } else {
                $error = array(
                    'error' => true,
                    'message' => 'Invalid review type or decision.',
                    'error_code' => 400
                );
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
