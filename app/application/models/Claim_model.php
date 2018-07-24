<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Great example of PHP-styled enum alternative
// https://stackoverflow.com/a/254543/298051
abstract class ClaimStatus {
    const Draft = 0;
    const CostCentreReview = 1;
    const Bounced = 2;
    const TreasurerReview = 3;
    const Rejected = 4;
    const Approved = 5;
    const Paid = 6;

    private static $constCacheArray = NULL;

    private static function getConstants() {
        if (self::$constCacheArray == NULL) {
            self::$constCacheArray = array();
        }
        $calledClass = get_called_class();
        if (!array_key_exists($calledClass, self::$constCacheArray)) {
            $reflect = new ReflectionClass($calledClass);
            self::$constCacheArray[$calledClass] = $reflect->getConstants();
        }
        return self::$constCacheArray[$calledClass];
    }

    private static function statusStringToInt($statusString)
    {
        $statusesByStringInLowercase = array_change_key_case(self::getConstants(), CASE_LOWER);
        return $statusesByStringInLowercase[strtolower($statusString)];
    }



    public static function isValidStatus($status)
    {
        if (is_string($status)) {
            $statusStrings = array_map('strtolower', array_keys(self::getConstants()));
            return in_array(strtolower($status), $statusStrings);
        } else if (is_int($status)) {
            $statusInts = array_values(self::getConstants());
            return in_array($status, $statusInts, false);
        } else {
            // another type, therefore not a valid status
            return false;
        }
    }

    public static function isStatusEditable($status)
    {
        $editableStatusesStrings = array(
            'Draft',
            'Bounced'
        );

        if (self::isValidStatus($status)) {
            if (is_string($status)) {
                return in_array(strtolower($status), $editableStatusesStrings);
            } else if (is_int($status)) {
                $editableStatusesStrings = array_map("self::statusStringToInt", $editableStatusesStrings);
                return in_array($status, $editableStatusesStrings);
            }
        }

        return false;
    }
}

class Claim_model extends CI_Model {


    //Loads the database using the ../config/database.php file
    public function __construct()	{
        $this->load->database();
    }

    // Helper function to be run on each claim, whether each row of all or getting an individual
    // Does things like casting types for the result_array and getting author names
    private function perClaimModify($claim)
    {
        // Cast types
        settype($claim['status'], 'int');

        // Fetch attachments
        $this->load->model('File_model');
        $claim['attachments'] = $this->File_model->getFilesForClaimID($claim['id_claim']);

        // Fetch activities
        $this->load->model('Activity_model');
        $claim['activities'] = $this->Activity_model->getActivitiesForClaimID($claim['id_claim']);

        // Query real name from username
        $this->load->model('User_model');
        $user_profile = $this->User_model->getUserByCIS($claim['claimant_id']);
        $claim['claimant_name'] = $user_profile['fullname'];

        // Determine actions possible on claim
        $claim['isEditable'] = ClaimStatus::isStatusEditable($claim['status']);

        return $claim;
    }

    public function getAllClaims()
    {
        $query = $this->db->get('claims');

        $claims = $query->result_array();

        $claims = array_map(array($this, 'perClaimModify'), $claims);

        return $claims;
    }

    public function getClaimsForUser($cisID)
    {
        $this->db->where('claimant_id', $cisID);
        $query = $this->db->get('claims');

        $claims = $query->result_array();

        $claims = array_map(array($this, 'perClaimModify'), $claims);

        return $claims;
    }

    public function getClaimByID($id_claim)
    {
        $this->db->where('id_claim', $id_claim);
        $query = $this->db->get('claims');

        $claim = null;
        if ($query->num_rows() == 1) {
            $claim = $query->row_array();
            $claim = $this->perClaimModify($claim);
        }

        return $claim;
    }

    public function createClaim($claimant_id)
    {
        $data = array(
            'claimant_id' => $claimant_id,
            'date' => date("Y-m-d"),
            'expenditure_items' => "[]",
            'cost_centre' => "General"
        );

        $this->db->insert('claims', $data);

        return $this->db->insert_id();
    }

    public function mayClaimBeEdited($id_claim)
    {
        $claim = $this->getClaimByID($id_claim);
        return ClaimStatus::isStatusEditable($claim['status']);
    }

    public function updateClaimAsUser($cisID, $id_claim, $description, $cost_centre, $expenditure_items)
    {
        $response = array(
            'success' => false,
        );

        // Get user
        $user = $this->User_model->getUserByCIS($cisID);

        // Get claim
        $claim = $this->getClaimByID($id_claim);

        // Check permissions
        if ($claim['claimant_id'] == $user['username']) {
            if ($claim['isEditable']) {
                $response['success'] = true;
            } else {
                $response['success'] = false;
                $response['message'] = 'This claim is not editable.';
            }
        } else {
            $response['success'] = false;
            $response['message'] = 'You are not the owner of this claim.';
        }

        // Update claim
        if ($response['success']) {
            $this->db->where('id_claim', $id_claim);
            $this->db->set('description', $description);
            $this->db->set('cost_centre', $cost_centre);
            $this->db->set('expenditure_items', $expenditure_items);
            $this->db->update('claims');
        }
        return $response;
    }

    public function changeClaimStatusAsUser($of_id_claim, $by_id_cis, $status_to)
    {
        $this->db->where('id_claim', $id_claim);
        $this->db->set('status', $status_to);
        return $this->db->update('claims');
    }

}
