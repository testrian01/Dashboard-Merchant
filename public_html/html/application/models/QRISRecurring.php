<?php defined('BASEPATH') OR exit('No direct script access allowed');

class QRISRecurring extends CI_Model {
    
    public function get_qrisrecurring($refMerchantId, $limit, $start, $search_date = null) {
        $query = "SELECT crqm.*, s.c_name as name_submerchant 
                    from cashin_recurring_qris_mpm crqm 
                    join submerchant s on crqm.ref_subMerchantId = s.id 
                    where crqm.ref_merchantId = $refMerchantId";

        if ($search_date) {
            $search_date = date('Y-m-d', strtotime($search_date));
            $query .= " AND DATE(crqm.c_datetimeRequest) = '$search_date'";
        }

        $query .= " ORDER BY crqm.id DESC
                    LIMIT $start, $limit";

        return $this->db->query($query)->result();
    }

    public function count_qrisrecurring($refMerchantId, $search_date = null) {
        $query = "SELECT 
                crqm.id from cashin_recurring_qris_mpm crqm 
                    join submerchant s on crqm.ref_subMerchantId = s.id 
                    where crqm.ref_merchantId = $refMerchantId";

        if ($search_date) {
            $query .= " AND crqm.c_datetimeRequest = '$search_date'";
        }

        return $this->db->query($query)->num_rows();
        }
}
?>
