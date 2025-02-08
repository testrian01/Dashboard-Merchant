<?php defined('BASEPATH') OR exit('No direct script access allowed');

class SubMerchant extends CI_Model {

    public function get_submerchant($refMerchantId, $limit, $start, $search_name_submerchant = null) {
            // Query untuk data submerchant
    $query = "SELECT * FROM submerchant WHERE ref_merchantId = $refMerchantId";

    // Tambahkan kondisi pencarian nama jika ada
    if ($search_name_submerchant) {
        $query .= " AND c_name LIKE '%$search_name_submerchant%'";
    }

    $query .= " ORDER BY id DESC LIMIT $start, $limit";

    // var_dump($query);
    // Eksekusi query
    return $this->db->query($query)->result();
        }
    }
?>