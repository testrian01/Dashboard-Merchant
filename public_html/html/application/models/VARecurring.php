<?php defined('BASEPATH') OR exit('No direct script access allowed');

class VARecurring extends CI_Model {

    public function get_varecurring($refMerchantId, $limit, $start, $search_date = null) {
        $query = "SELECT crv.*, s.c_name as name_submerchant 
                 from cashin_recurring_va crv 
                 join submerchant s on s.id = crv.ref_subMerchantId
                 where crv.ref_merchantId = $refMerchantId";

        if ($search_date) {
                $search_date = date('Y-m-d', strtotime($search_date));
                $query .= " AND DATE(crv.c_datetimeRequest) = '$search_date'";
            }

        $query .= " ORDER BY crv.id DESC
                    LIMIT $start, $limit";

        return $this->db->query($query)->result();
    }

    public function count_varecurring($refMerchantId, $search_date = null) {
        $query = "SELECT 
                crv.id from cashin_recurring_va crv 
                 join submerchant s on s.id = crv.ref_subMerchantId
                 where crv.ref_merchantId = $refMerchantId";

        if ($search_date) {
            $query .= " AND crv.c_datetimeRequest = '$search_date'";
        }

        return $this->db->query($query)->num_rows();
        }
    }
?>