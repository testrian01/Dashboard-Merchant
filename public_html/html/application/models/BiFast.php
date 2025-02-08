<?php defined('BASEPATH') OR exit('No direct script access allowed');

class BiFast extends CI_Model {

    public function get_bifast($refMerchantId, $limit, $start, $search_date_bifast1 = null, $search_date_bifast2 = null, $search_transid_bifast = null, $search_status_transaction_bifast = null) {
        
        $query = "SELECT cashout_payment_bifast.id, cashout_payment_bifast.c_datetime, 
                    cashout.c_invoiceNo, cashout_payment_bifast.c_merchantTransactionId,
                    cashout_payment_bifast.ref_cashoutChannelId, cashout_payment_bifast.c_accountNo, cashout_payment_bifast.c_amount, cashout_payment_bifast.c_fee, cashout_payment_bifast.c_status
                     FROM cashout_payment_bifast 
                     JOIN cashout ON cashout.id = cashout_payment_bifast.ref_cashoutId
                     WHERE cashout_payment_bifast.ref_merchantId = $refMerchantId";

        if (!empty($search_date_bifast1) && !empty($search_date_bifast2)) {

            $search_date_bifast1 = date('Y-m-d', strtotime($search_date_bifast1));
            $search_date_bifast2 = date('Y-m-d', strtotime($search_date_bifast2));

            $query .= " and DATE(cashout_payment_bifast.c_datetime) between '".$search_date_bifast1."' and '".$search_date_bifast2."'";
        }

        if (!empty($search_transid_bifast)) {
            $query .= " AND cashout_payment_bifast.c_merchantTransactionId ='$search_transid_bifast'";
        }

        if (!empty($search_status_transaction_bifast)) {
            $query .= " AND cashout_payment_bifast.c_status ='$search_status_transaction_bifast'";
        }

        $query .= " ORDER BY cashout_payment_bifast.id DESC
                    LIMIT $start, $limit";

        // var_dump($query);

        return $this->db->query($query)->result();
    }

    public function count_bifast($refMerchantId, $search_date = null) {
        $query = "SELECT 
            cashout_payment_bifast.id
            FROM cashout_payment_bifast 
            JOIN cashout ON cashout.id = cashout_payment_bifast.ref_cashoutId
            WHERE cashout_payment_bifast.ref_merchantId = $refMerchantId";

        if ($search_date) {
            $query .= " AND cashout_payment_bifast.c_datetime = '$search_date'";
        }

        return $this->db->query($query)->num_rows();
    }

    public function getBifastDetail($refMerchantId, $id){

        $query = "SELECT cashout_payment_bifast.*, cashout.*
        FROM cashout_payment_bifast 
        JOIN cashout ON cashout.id = cashout_payment_bifast.ref_cashoutId
        WHERE cashout_payment_bifast.ref_merchantId = $refMerchantId and cashout_payment_bifast.id = $id";

        return $this->db->query($query)->result_array();
    }
}
?>