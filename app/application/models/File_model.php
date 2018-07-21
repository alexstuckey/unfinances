<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class File_model extends CI_Model {

    private function human_filesize($bytes, $decimals = 2)
    {
        if ($bytes < 1024) {
            return $bytes . ' B';
        }

        $factor = floor(log($bytes, 1024));
        $suffixes = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
        return sprintf("%.{$decimals}f ", $bytes / pow(1024, $factor)) . $suffixes[$factor];
    }


    //Loads the database using the ../config/database.php file
    public function __construct()	{
        $this->load->database();
    }

    public function getAllFiles()
    {
        $query = $this->db->get('uploads');

        return $query->result_array();
    }

    public function getFilesForClaimID($id_claim)
    {
        $this->db->where('of_id_claim', $id_claim);
        $query = $this->db->get('uploads');

        $files = $query->result_array();
        foreach ($files as &$file) {
            $file['filesize_human'] = $this->human_filesize($file['filesize_bytes']);
            $file['uploaded_datetime'] = date("c",strtotime($file['uploaded_datetime']));
        }

        return $files;
    }

    public function getFileByFilename($filename)
    {
        $this->db->where('id_filename', $filename);
        $query = $this->db->get('uploads');

        $file = null;
        $file = $query->row_array();
        $file['filesize_human'] = $this->human_filesize($file['filesize_bytes']);
        $file['uploaded_datetime'] = date("c",strtotime($file['uploaded_datetime']));


        return $file;
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

        return $this->getFileByFilename($data['id_filename']);
    }

}
