<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class File extends CI_Controller {

    public function do_upload()
    {
        $userAccount = $this->User_model->getUserByCIS($_SERVER['REMOTE_USER']);
        

        $error = null;
        $data = array();
        $of_id_claim = $this->input->post('of_id_claim');

        if ( empty($of_id_claim) )
        {
            $error = array('error' => 'failure to provide of_id_claim');

        } else {

            // Check the invoice exists
            $this->load->model('Claim_model');
            $claim = $this->Claim_model->getClaimByID($of_id_claim);
            if (empty($claim)) {
                $error = array('error' => 'Claim does not exist');
            } else {

                // Check if user alloed to upload to this invoice
                if (
                ($claim['claimant_id'] == $userAccount['username'] && $claim['isEditable'])
             || $userAccount['is_admin']
             || $userAccount['is_treasurer']
             || array_reduce($userAccount['managerOfCostCentres'], function ($carry, $item) use ($claim) { return ($carry || ($item['cost_centre'] == $claim['cost_centre'])); }, false)
                ) {

                    // Generate filename (extension filled in from original)
                    $this->load->library('Uuid');
                    $config['file_name']            = $this->uuid->v4();

                    $config['upload_path']          = './uploads/';
                    $config['allowed_types']        = 'gif|jpg|jpeg|png|pdf';
                    $config['max_size']             = 5000;

                    $this->load->library('upload', $config);

                    if ( ! $this->upload->do_upload('userfile') )
                    {
                        $error = array('error' => $this->upload->display_errors());

                    } else {
                        $upload_data = $this->upload->data();

                        // Write to db
                        $this->load->model('File_model');
                        $data['attachment_upload'] = $this->File_model->createFile(
                            $upload_data['file_name'],
                            $upload_data['client_name'],
                            filesize($upload_data['full_path']),
                            $_SERVER['REMOTE_USER'],
                            $of_id_claim
                        );
                    }
                } else {
                    $error = array('error' => 'You are not permitted to upload an attachment to this claim.');
                }
            }
        }



        if (isset($error)) {
            $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode($error));
        } else {
            $this->output
                ->set_status_header(201)
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }

    }


    public function delete_image()
    {
        // auth

        // Check if image exists

        // Check if you are owner of image / admin

        // Check if image 'editable'

        // Delete file

        // Delete row
    }


    public function images_of_claim($claim_id)
    {
        // auth

        // check if claim exists

        // check if you have access to claim

        // return array of images
    }

}
