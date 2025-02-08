<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MerchantDownload extends CI_Model {

    public function get_download($refMerchantId, $limit, $start, $search_date = null) {
    
        $query = "SELECT * 
		        FROM merchant_download 
                WHERE ref_merchantId = $refMerchantId";
       
        if ($search_date) {
            $search_date = date('Y-m-d', strtotime($search_date));
            $query .= " AND DATE(merchant_download.c_datetime) = '$search_date'";
        }
    
        $query .= " ORDER BY id DESC
                LIMIT $start, $limit";
    
        return $this->db->query($query)->result();
    }
    
    public function count_download($refMerchantId, $search_date = null) {

        $query = "SELECT 
            cashout_payment_ppob.id
            FROM cashout_payment_ppob
            left join cashout on cashout.id = cashout_payment_ppob.ref_cashoutId
            WHERE cashout_payment_ppob.ref_merchantId = $refMerchantId ";

        if ($search_date) {
            $query .= " AND cashout_payment_ppob.c_datetime = '$search_date'";
        }

        return $this->db->query($query)->num_rows();
    }
}
?>