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
                $userLocal['doesUserExist'] = true;
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
            return array_merge($userCIS, $userLocal);
        }

        return $userCIS;
    }

    public function getUserByEmail($email)
    {
        $this->load->model('CIS_model');

        $userCIS = $this->CIS_model->get_user_details_by_email($email);

        if (!empty($userCIS)) {
            $userLocal = $this->getLocalUserData($userCIS['username']);
            return array_merge($userCIS, $userLocal);
        }

        return $userCIS;
    }




    public function addAdmin($cisID)
    {
        $user = $this->getUserByCIS($cisID);
        if ($user['doesUserExist']) {
            $data = array(
                'is_admin' => 1
            );
            $this->db->where('id_cis', $cisID);
            $this->db->update('users', $data);
        }
    }

    public function getAdmins()
    {
        $this->db->where('is_admin', '1');
        $query = $this->db->get('users');

        $admins = array();

        foreach ($query->result_array() as $admin) {
            $admins[] = $this->getUserByCIS($admin['id_cis']);
        }

        return $admins;
    }



    // Creates a new local user from a CIS user, with a 0 onboarding status,
    // ready for the onboarding phase
    public function createUser($cisID)
    {
        $existingUser = $this->getUserByCIS($cisID);

        if (!empty($existingUser)) {
            if ($existingUser['doesUserExist'] == false) {
                $data = array(
                    'id_cis' => $cisID,
                    'has_onboarded' => 0
                );

                $this->db->insert('users', $data);
                return true;
            }
        }

        return false;
    }

    // Assumes correct data
    public function completeOnboardingWithDetails($cisID, $dob, $accountNumber, $sortCode)
    {
        $existingUser = $this->getUserByCIS($cisID);

        if ($existingUser['doesUserExist']) {
            $data = array(
                'has_onboarded' => 1,
                'dob' => $dob,
                'bank_account_number' => $accountNumber,
                'bank_sort_code' => $sortCode

            );

            $this->db->where('id_cis', $cisID);
            $this->db->update('users', $data);
        }

        return false;
    }

}
