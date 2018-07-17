<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CostCentre_model extends CI_Model {


    //Loads the database using the ../config/database.php file
    public function __construct()
    {
        $this->load->database();
    }

    public function getAllCostCentres()
    {
        $query = $this->db->get('cost_centres');

        $costCentres = $query->result_array();

        foreach ($costCentres as $costCentre) {
            if ($costCentre['manager_id_cis'] == '') {
                $costCentre['active'] = false;
            } else {
                $costCentre['active'] = true;
            }
        }

        return $costCentres;
    }

    public function getCostCentresWithManager($cisID)
    {
        $this->db->where('manager_id_cis', $cisID);
        $query = $this->db->get('cost_centres');

        return $query->result_array();
    }

    public function createNewCostCentre($name)
    {
        if (!empty($name)) {
            $data = array(
                'cost_centre' => $name
            );
            $this->db->insert('cost_centres', $data);

            return true;
        } else {
            return false;
        }
    }

    // does not check for cost centre existance, assumes responsible usage
    public function changeManager($cost_centre, $manager_id_cis)
    {
        $data = array(
            'manager_id_cis' => $manager_id_cis
        );
        $this->db->where('cost_centre', $cost_centre);
        $this->db->update('cost_centres', $data);

        return true;
    }

}
