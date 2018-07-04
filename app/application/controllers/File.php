<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class File extends CI_Controller {

    public function do_upload()
    {
        // $this->load->model('User_model');
        // if (!$this->User_model->doesUserExist($_SERVER['REMOTE_USER'])) {
        //     $this->load->helper('url');
        //     redirect('/onboard/welcome');
        // } else if ($this->User_model->isAdmin($_SERVER['REMOTE_USER'])) {
        //     $data['is_admin'] = TRUE;
        // }

        $config['upload_path']          = './uploads/';
        $config['allowed_types']        = 'gif|jpg|jpeg|png|pdf';
        $config['max_size']             = 5000;

        // Generate filename (extension filled in from original)
        $this->load->library('Uuid');
        $config['file_name']            = $this->uuid->v4();

        $this->load->library('upload', $config);

        $error = null;
        $data = array();
        $invoice_id = $this->input->post('invoice_id');

        if ( empty($invoice_id) )
        {
            $error = array('error' => 'failure to provide invoice_id');

        } else {

            // Check if user alloed to upload to this invoice / if it exists
            if ( !true ) {
                $error = array('error' => 'access not permitted to invoice_id = ' . $invoice_id);
            } else {

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
                        $invoice_id
                    );
                }
            }
        }



        if (isset($error))
        {
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
