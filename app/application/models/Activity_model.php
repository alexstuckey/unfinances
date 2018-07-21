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

        $activity['activity_datetime'] = date("Y-m-d\TH:i:s",strtotime($activity['activity_datetime']));

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
        $data = array(
            'of_id_claim' => $of_id_claim,
            'by_id_cis' => $by_id_cis,
            'activity_type' => 'comment',
            'activity_value' => $body
            
        );

        return $this->db->insert('activities', $data);
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
