<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Claim_model extends CI_Model {


    //Loads the database using the ../config/database.php file
    public function __construct()	{
        $this->load->database();
    }

    public function getAllClaims()
    {
        $query = $this->db->get('claims');

        return $query->result_array();
    }

    public function getClaimByID($id_claim)
    {
        $this->db->where('id_claim', $id_claim);
        $query = $this->db->get('claims');

        $claim = null;
        if ($query->num_rows() == 1) {
            $claim = $query->row_array();

            $this->load->model('File_model');
            $claim['attachments'] = $this->File_model->getFilesForClaimID($id_claim);

            $this->load->model('CIS_model');
            $user_cis_profile = $this->CIS_model->get_user_details_by_cisID($claim['claimant_id']);
            $claim['claimant_name'] = $user_cis_profile['fullname'];
        }

        return $claim;
    }

    public function createClaim($claimant_id)
    {
        $data = array(
            'claimant_id' => $claimant_id,
            'date' => date("Y-m-d"),
            'expenditure_items' => "[]"
        );

        $this->db->insert('claims', $data);

        return $this->db->insert_id();
    }

}
