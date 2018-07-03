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
        $config['allowed_types']        = 'gif|jpg|png|pdf';
        $config['max_size']             = 5000;

        $this->load->library('upload', $config);

        $error = null;
        $invoice_id = $this->input->post('invoice_id');

        if ( empty($invoice_id) )
        {
            $error = array('error' => 'failure to provide invoice_id');

        } else {

            if ( ! $this->upload->do_upload('userfile') )
            {
                $error = array('error' => $this->upload->display_errors());

            } else {
                $data = array('upload_data' => $this->upload->data(),
                              'invoice_id' => $invoice_id
                            );
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

}
