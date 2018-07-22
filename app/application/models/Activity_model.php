<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Activity_model extends CI_Model {


    //Loads the database using the ../config/database.php file
    public function __construct()	{
        $this->load->database();
    }

    // Helper function to be run on each activity, whether each row of all or getting an individual
    // Does things like casting types for the result_array and getting author names
    private function perActivityModify($activity)
    {
        // Cast types
        settype($activity['id_activity'], 'int');
        settype($activity['of_id_claim'], 'int');

        // Query real name from username
        $this->load->model('User_model');
        $activity['by_id_cis_user'] = $this->User_model->getUserByCIS($activity['by_id_cis']);

        $activity['activity_datetime'] = date("c",strtotime($activity['activity_datetime']));

        return $activity;
    }

    public function getAllActivities()
    {
        $query = $this->db->get('activities');

        $activities = $query->result_array();

        $activities = array_map(array($this, 'perActivityModify'), $activities);

        return $activities;
    }

    public function getActivitiesForClaimID($id_claim)
    {
        $this->db->where('of_id_claim', $id_claim);
        $query = $this->db->get('activities');

        $activities = $query->result_array();

        $activities = array_map(array($this, 'perActivityModify'), $activities);

        return $activities;
    }



    // Actions on a claim
    public function createOnClaimID($of_id_claim, $by_id_cis)
    {
        $data = array(
            'of_id_claim' => $of_id_claim,
            'by_id_cis' => $by_id_cis,
            'activity_type' => 'create'
        );

        return $this->db->insert('activities', $data);
    }

    public function commentOnClaimID($of_id_claim, $by_id_cis, $body)
    {

        // Get claim
        $this->load->model('Claim_model');
        $claim = $this->Claim_model->getClaimByID($of_id_claim);

        if (empty($claim)) {
            $response['success'] = false;
            $response['message'] = 'This claim does not exist.';
        } else {

            // Prep for permissions check
            // Get user
            $user = $this->User_model->getUserByCIS($by_id_cis);

            $isManagerOfCostCentre = false;
            foreach ($user['managerOfCostCentres'] as $cost_centre) {
                if (isset($cost_centre['cost_centre']) && $cost_centre['cost_centre'] == $claim['cost_centre']) {
                    $isManagerOfCostCentre = true;
                }
            }

            // Check permissions
            // 1. if it's your claim
            // 2. if you are an admin
            // 3. if you are a treasurer
            // 4. if you are the manager of the cost centre of that claim
            if ($claim['claimant_id'] == $user['username']) {
                $response['success'] = true;
            } else if ($user['is_admin'] || $user['is_treasurer'] || $isManagerOfCostCentre) {                
                $response['success'] = true;
            } else {
                $response['success'] = false;
                $response['message'] = 'You are not the owner of this claim.';
            }
        }

        

        // Update claim
        if ($response['success']) {
            $data = array(
            'of_id_claim' => $of_id_claim,
            'by_id_cis' => $by_id_cis,
            'activity_type' => 'comment',
            'activity_value' => $body
            );
            $response['success'] = $this->db->insert('activities', $data);
        }

        return $response;
    }

    public function changeStatusOnClaimID($of_id_claim, $by_id_cis, $status_from, $status_to)
    {
        $data = array(
            'of_id_claim' => $of_id_claim,
            'by_id_cis' => $by_id_cis,
            'activity_type' => 'change_status',
            'activity_value' => $status_to,
            'activity_value_before' => $status_from
            
        );

        return $this->db->insert('activities', $data);
    }



}
