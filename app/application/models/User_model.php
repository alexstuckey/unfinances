<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model
{

    // Loads the database using the ../config/database.php file
    public function __construct()
    {
        $this->load->database();
    }

    public function getAllLocalUsers()
    {

        $query = $this->db->get('users');
        return $query->result_array();
    }


    private function getLocalUserData($cisID) {
        if (!empty($cisID)) {

            $this->db->where('id_cis', $cisID);
            $query = $this->db->get('users');

            $claim = null;
            if ($query->num_rows() == 1) {
                $userLocal = $query->row_array();
                if ($userLocal['has_onboarded']) {
                    $userLocal['doesUserExist'] = true;
                } else {
                    $userLocal['doesUserExist'] = false;
                }
                return $userLocal;
            } else {
                return array( 'doesUserExist' => false );
            }
        } else {
            return null;
        }
    }
    // Returns the CIS user details
    public function getUserByCIS($cisID)
    {
        $this->load->model('CIS_model');

        $userCIS = $this->CIS_model->get_user_details_by_cisID($cisID);

        if (!empty($userCIS)) {
            $userLocal = $this->getLocalUserData($userCIS['username']);
        }

        return array_merge($userCIS, $userLocal);
    }

    public function getUserByEmail($email)
    {
        $this->load->model('CIS_model');

        $userCIS = $this->CIS_model->get_user_details_by_email($email);

        if (!empty($userCIS)) {
            $userLocal = $this->getLocalUserData($userCIS['username']);
        }

        return array_merge($userCIS, $userLocal);
    }



}
