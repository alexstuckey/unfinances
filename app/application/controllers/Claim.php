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
                $data['page_title'] = 'UCFinances - Claim ' . $data['claim']['id_claim'];
                $data['javascript_jsgrid'] = true;
                $data['javascript_uppy'] = true;
                $data['javascript_inputmask'] = true;

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

        if (!isset($data['claim'])) {
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

                            // Notify cost centre manager
                                // Find Cost Centre Manager
                            $this->load->model('CostCentre_model');
                            $cost_centre_manager = $this->CostCentre_model->getManagerForCostCentre($data['claim']['cost_centre']);

                            $this->load->model('Email_model');
                            $this->Email_model->sendEmail(
                                '1_CostCentreManager_Review',
                                $cost_centre_manager['email'],
                                array(
                                    'cost_centre_manager_name' => $cost_centre_manager['fullname'],
                                    'cost_centre' => $data['claim']['cost_centre'],
                                    'claimant_name' => $data['claim']['claimant_name'],
                                    'id_claim' => $data['claim']['id_claim'],
                                    'claim_description' => $data['claim']['description'],
                                    'attachments_count' => count($data['claim']['attachments']),
                                    'expenditure_items' => json_decode(str_replace('\\', '', $data['claim']['expenditure_items']), true),
                                    'claim_url' => site_url('/expenses/claim/' . $data['claim']['id_claim'])
                                )
                            );

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

    public function deleteClaimByJSON($id_claim)
    {
        // This will take a DRAFT claim and set it to DELETED.

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
                if ($data['claim']['status'] == ClaimStatus::statusStringToInt('Draft')) {

                    // Check permissions
                    if ($data['claim']['claimant_id'] == $data['userAccount']['username']) {

                        // check if claim is allowed to be updated
                        $updateDBAttempt = $this->Claim_model->changeClaimStatus(
                                                $id_claim,
                                                ClaimStatus::statusStringToInt('Deleted')
                                            );
                        if ($updateDBAttempt) {
                            $this->load->model('Activity_model');
                            $this->Activity_model->changeStatusOnClaimID(
                                $id_claim,
                                $data['userAccount']['username'],
                                $data['claim']['status'],
                                ClaimStatus::statusStringToInt('Deleted')
                            );

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
                        'message' => 'Claim is not a draft',
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
            if ($updateDBAttempt['success']) {
                // Notify all those who have an item in the activity feed
                $users_involved = array_column($data['claim']['activities'], 'by_id_cis_user');
                $users_involved[] = $data['userAccount']; // to avoid re-fetching claim
                $users_involved_emails = array_column($users_involved, 'email');
                $users_involved_emails = array_unique($users_involved_emails);

                $this->load->model('Email_model');
                $this->Email_model->sendEmail(
                    '7_All_Comment',
                    $users_involved_emails,
                    array(
                        'id_claim' => $data['claim']['id_claim'],
                        'claim_url' => site_url('/expenses/claim/' . $data['claim']['id_claim']),
                        'comment_text' => $this->input->post('comment_field'),
                        'comment_author' => $data['userAccount']['fullname']
                    )
                );

            } else {
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
        // This will take a claims in REVIEW and set it to either Bounced, Rejected, or TreasurerReview, then notify the required people.
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
                    $notify_claimant_with_email_name = false;
                    $notify_treasurer_with_email_name = false;
                    if (
                        $review_type == 'cost_centre'
                     && array_reduce($data['userAccount']['managerOfCostCentres'], function ($carry, $item) use ($data) { return ($carry || ($item['cost_centre'] == $data['claim']['cost_centre'])); }, false)
                     && $data['claim']['status'] == ClaimStatus::statusStringToInt('CostCentreReview')
                        ) {

                        $is_authorised = true;
                        if ($review_decision == 'bounce') {
                            $new_status = ClaimStatus::statusStringToInt('Bounced');
                            $notify_claimant_with_email_name = '2_Claimant_Bounced';
                        } else if ($review_decision == 'approve') {
                            $new_status = ClaimStatus::statusStringToInt('TreasurerReview');
                            $notify_treasurer_with_email_name = '3_Treasurer_Review';
                        }
                        

                    } else if (
                        $review_type == 'treasurer'
                     && $data['userAccount']['is_treasurer']
                     && $data['claim']['status'] == ClaimStatus::statusStringToInt('TreasurerReview')
                        ) {

                        $is_authorised = true;
                        if ($review_decision == 'bounce') {
                            $new_status = ClaimStatus::statusStringToInt('Bounced');
                            $notify_claimant_with_email_name = '2_Claimant_Bounced';
                        } else if ($review_decision == 'approve') {
                            $new_status = ClaimStatus::statusStringToInt('PaymentDetails');
                            $notify_claimant_with_email_name = '4_Claimant_Approved';
                        } else if ($review_decision == 'reject') {
                            $new_status = ClaimStatus::statusStringToInt('Rejected');
                            $notify_claimant_with_email_name = '5_Claimant_Rejected';
                        } else if ($review_decision == 'pay') {
                            $new_status = ClaimStatus::statusStringToInt('Paid');
                            $notify_claimant_with_email_name = '6_Claimant_Paid';
                        }
                    } else if (
                        $review_type == 'treasurer'
                     && $data['userAccount']['is_treasurer']
                     && $data['claim']['status'] == ClaimStatus::statusStringToInt('PaymentPending')
                        ) {

                        $is_authorised = true;
                        if ($review_decision == 'pay') {
                            $new_status = ClaimStatus::statusStringToInt('Paid');
                            $notify_claimant_with_email_name = '6_Claimant_Paid';
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

                            $email_data_payload = array(
                                'cost_centre' => $data['claim']['cost_centre'],
                                'claimant_name' => $data['claim']['claimant_name'],
                                'id_claim' => $data['claim']['id_claim'],
                                'claim_description' => $data['claim']['description'],
                                'attachments_count' => count($data['claim']['attachments']),
                                'expenditure_items' => json_decode(str_replace('\\', '', $data['claim']['expenditure_items']), true),
                                'claim_url' => site_url('/expenses/claim/' . $data['claim']['id_claim'])
                            );
                            if ($review_type == 'treasurer') {
                                $email_data_payload['treasurer_name'] = $data['userAccount']['fullname'];
                            }

                            if ($notify_claimant_with_email_name != false) {
                                // Notify the claimant
                                $claimant = $this->User_model->getUserByCIS($data['claim']['claimant_id']);

                                $this->load->model('Email_model');
                                $this->Email_model->sendEmail(
                                    $notify_claimant_with_email_name,
                                    $claimant['email'],
                                    $email_data_payload
                                );
                            }
                            if ($notify_treasurer_with_email_name != false) {
                                // Notify the treasurers
                                $treasurers = $this->User_model->getTreasurers();
                                $treasuers_emails = array_column($treasurers, 'email');

                                $this->load->model('Email_model');
                                $this->Email_model->sendEmail(
                                    $notify_treasurer_with_email_name,
                                    $treasuers_emails,
                                    $email_data_payload
                                );
                            }

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

    public function providePaymentDetailsByJSON($id_claim)
    {
        // This will take a claims in PaymentDetails and set it to PaymentPending, then notify the treasurer.

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
        $this->form_validation->set_rules('claim_input_account_number', 'Account Number', array(
            'required',
            'regex_match[/^(\d{8})$/]'
        ));
        $this->form_validation->set_rules('claim_input_sort_code', 'Sort Code', array(
            'required',
            'regex_match[/^(\d{2}-\d{2}-\d{2})$/]'
        ));

        if ($this->form_validation->run() == FALSE) {
            $error = array(
                'error' => true,
                'message' => $this->form_validation->error_array(),
                'error_code' => 400
            );
        } else {

            // Check permissions
            if ($data['claim']['claimant_id'] == $data['userAccount']['username']) {
                if ($data['claim']['status'] == ClaimStatus::statusStringToInt('PaymentDetails')) {

                    // check if claim is allowed to be updated
                    $updateDBAttempt = $this->Claim_model->changeClaimStatus(
                                            $id_claim,
                                            ClaimStatus::statusStringToInt('PaymentPending')
                                        );
                    if ($updateDBAttempt) {
                        $this->load->model('Activity_model');
                        $this->Activity_model->changeStatusOnClaimID(
                            $id_claim,
                            $data['userAccount']['username'],
                            $data['claim']['status'],
                            ClaimStatus::statusStringToInt('PaymentPending')
                        );

                        // CREATE THE PDF

                        // Notify the treasurers
                        $treasurers = $this->User_model->getTreasurers();
                        $treasuers_emails = array_column($treasurers, 'email');

                        $this->load->model('Email_model');
                        $this->Email_model->sendEmail(
                            '9_Treasurer_PaymentPending',
                            $treasuers_emails,
                            array(
                                'treasurer_name' => 'Treasurers',
                                'cost_centre' => $data['claim']['cost_centre'],
                                'claimant_name' => $data['claim']['claimant_name'],
                                'id_claim' => $data['claim']['id_claim'],
                                'claim_description' => $data['claim']['description'],
                                'attachments_count' => count($data['claim']['attachments']),
                                'expenditure_items' => json_decode(str_replace('\\', '', $data['claim']['expenditure_items']), true),
                                'claim_url' => site_url('/expenses/claim/' . $data['claim']['id_claim'])
                            )
                        );

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
                        'message' => 'This claim is not waiting for payment details.',
                        'error_code' => 403
                    );
                }
            } else {
                $error = array(
                    'error' => true,
                    'message' => 'You are not the owner of this claim.',
                    'error_code' => 403
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
