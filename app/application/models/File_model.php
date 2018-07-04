<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class File_model extends CI_Model {


    //Loads the database using the ../config/database.php file
    public function __construct()	{
        $this->load->database();
    }

    public function getAllFiles()
    {
        $query = $this->db->get('uploads');

        return $query->result_array();
    }

    public function getFileByFilename($filename)
    {
        $this->db->where('id_filename', $filename);
        $query = $this->db->get('uploads');

        return $query->row_array();
    }

    public function deleteFileByFilename($filename)
    {
        $this->db->where('id_filename', $filename);
        $this->db->delete('uploads');
    }

    public function createFile($filename, $client_name, $filesize_bytes, $uploader_id_cis, $of_id_claim)
    {
        $data = array(
            'id_filename' => $filename,
            'client_name' => $client_name,
            'filesize_bytes' => $filesize_bytes,
            'uploader_id_cis' => $uploader_id_cis,
            'of_id_claim' => $of_id_claim
        );

        $this->db->insert('uploads', $data);
    }

}
