<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CostCentre_model extends CI_Model {


    //Loads the database using the ../config/database.php file
    public function __construct()	{
        $this->load->database();
    }

    public function getAllCostCentres()
    {
        $query = $this->db->get('cost_centres');

        return $query->result_array();
    }

}
