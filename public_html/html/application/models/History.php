<?php defined('BASEPATH') OR exit('No direct script access allowed');

class History extends CI_Model {

    public function get_history($refMerchantId, $limit, $start, $search_date = null) {
    
        $query = "SELECT 
                cashout_payment_ppob.c_datetime, 
                cashout_payment_ppob.ref_cashoutChannelId, 
                c_invoiceNo, 
                c_phone, 
                cashout_payment_ppob.c_amount, 
                c_status
                FROM cashout_payment_ppob 
                LEFT JOIN cashout ON cashout.id = cashout_payment_ppob.ref_cashoutId
                WHERE cashout_payment_ppob.ref_merchantId = $refMerchantId";
        // var_dump($query);
        if ($search_date) {
            $search_date = date('Y-m-d', strtotime($search_date));
            $query .= " AND DATE(cashout_payment_ppob.c_datetime) = '$search_date'";
        }
    
        $query .= " LIMIT $start, $limit";
    
        return $this->db->query($query)->result();
    }
    
    public function count_history($refMerchantId, $search_date = null) {

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