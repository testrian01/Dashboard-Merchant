<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Qris extends CI_Model {

    public function get_qris($refMerchantId, $limit, $start, $search_date_qris1 = null, $search_date_qris2 = null, $search_date_qris_settlement = null, $search_qris_invoice_no = null)
    {

        $query = "SELECT 
                    merchant.c_name as name_merchant,
                    cashin_payment_qris_mpm.id, 
                    cashin_payment_qris_mpm.c_datetime, 
                    submerchant.c_name as name_submerchant, 
                    cashin.c_invoiceNo, 
                    cashin_payment_qris_mpm.c_type,
                    cashin_payment_qris_mpm.ref_merchantId, 
                    cashin_payment_qris_mpm.ref_subMerchantId, 
                    cashin_payment_qris_mpm.c_amount, 
                    cashin_payment_qris_mpm.c_mdr, 
                    cashin_payment_qris_mpm.c_fee,
                    cashin_payment_qris_mpm.c_datetimePayment, cashin_payment_qris_mpm.c_isSettlementRealtime, 
                    cashin_payment_qris_mpm.c_datetimeSettlement, cashin_payment_qris_mpm.c_isSettlementRealtimeExternal, 
                    cashin_payment_qris_mpm.c_feeExternal, cashin_payment_qris_mpm.c_datetimeSettlementExternal,
                    IF(cashin_payment_qris_mpm.c_type='Dynamic', cashin_dynamic_qris_mpm.c_merchantTransactionId, cashin_recurring_qris_mpm.c_merchantTransactionId) AS Merchant_Transaction_Id
                    FROM cashin_payment_qris_mpm 
                    JOIN cashin on cashin.id = cashin_payment_qris_mpm.ref_cashinId
                    JOIN submerchant on cashin_payment_qris_mpm.ref_subMerchantId = submerchant.id 
                    JOIN merchant on cashin_payment_qris_mpm.ref_merchantId = merchant.id
                    LEFT JOIN cashin_dynamic_qris_mpm on (cashin_dynamic_qris_mpm.ref_subMerchantId = cashin_payment_qris_mpm.ref_subMerchantId AND cashin_dynamic_qris_mpm.id=cashin_payment_qris_mpm.ref_cashinDynamicQrisMpmId)
                    LEFT JOIN cashin_recurring_qris_mpm on (cashin_recurring_qris_mpm.ref_subMerchantId = cashin_payment_qris_mpm.ref_subMerchantId AND cashin_recurring_qris_mpm.id=cashin_payment_qris_mpm.ref_cashinRecurringQrisMpmId)
                    WHERE cashin_payment_qris_mpm.ref_merchantId = '$refMerchantId'";

        if (!empty($search_date_qris1) && !empty($search_date_qris2)) {

            $search_date_qris1 = date('Y-m-d', strtotime($search_date_qris1));
            $search_date_qris2 = date('Y-m-d', strtotime($search_date_qris2));

            $query .= " and DATE(cashin_payment_qris_mpm.c_datetime) between '".$search_date_qris1."' and '".$search_date_qris2."'";
        }

        if (!empty($search_date_qris_settlement)) {
            $search_date_qris_settlement = date('Y-m-d', strtotime($search_date_qris_settlement));
            $query .= " and DATE(cashin_payment_qris_mpm.c_datetimeSettlement) = '$search_date_qris_settlement'";
        }

        if (!empty($search_qris_invoice_no)) {
            $query .= " and cashin.c_invoiceNo= '$search_qris_invoice_no'";
        }


        $query .= " ORDER BY cashin_payment_qris_mpm.id DESC
                    LIMIT $start, $limit";

        // var_dump($query);

        return $this->db->query($query)->result();
    }

    public function count_qris($refMerchantId, $search_date_qris = null)
    {

        $query = "SELECT 
            cashin_payment_qris_mpm.id
            FROM cashin_payment_qris_mpm 
            JOIN cashin on cashin.id = cashin_payment_qris_mpm.ref_cashinId
            JOIN submerchant on cashin_payment_qris_mpm.ref_subMerchantId = submerchant.id 
            LEFT JOIN cashin_dynamic_qris_mpm on (cashin_dynamic_qris_mpm.ref_merchantId = cashin_payment_qris_mpm.ref_merchantId AND cashin_dynamic_qris_mpm.id=cashin_payment_qris_mpm.ref_cashinDynamicQrisMpmId)
            LEFT JOIN cashin_recurring_qris_mpm on (cashin_recurring_qris_mpm.ref_merchantId = cashin_payment_qris_mpm.ref_merchantId AND cashin_recurring_qris_mpm.id=cashin_payment_qris_mpm.ref_cashinRecurringQrisMpmId)
            where cashin_payment_qris_mpm.ref_merchantId  = $refMerchantId";

        if ($search_date_qris) {
            $query .= " AND DATE(cashin_payment_qris_mpm.c_datetime) = '$search_date_qris'";
        }

        return $this->db->query($query)->num_rows();
    }
    
    public function qris_detail($refMerchantId, $id)
    {
        $query = "SELECT a.c_datetime, a.ref_merchantId, c.c_name AS name_merchant, a.ref_subMerchantId, 
                    d.c_name AS name_submerchant, b.c_invoiceNo, 
                    a.c_type, a.c_amount, a.c_mdr, a.c_fee, a.c_datetimePayment, 
                    a.c_isSettlementRealtime, a.c_datetimeSettlement, 
                    IF(a.c_type='Dynamic', e.c_merchantTransactionId, f.c_merchantTransactionId) AS c_merchantTransactionId
                    FROM cashin_payment_qris_mpm a
                    JOIN cashin b ON b.id=a.ref_cashinId
                    JOIN merchant c ON a.ref_merchantId=c.id
                    JOIN submerchant d ON a.ref_subMerchantId=d.id
                    LEFT JOIN cashin_dynamic_qris_mpm e ON (e.ref_merchantId=a.ref_merchantId AND e.id=a.ref_cashinDynamicQrisMpmId) 
                    LEFT JOIN cashin_recurring_qris_mpm f ON (f.ref_merchantId=a.ref_merchantId AND e.id=a.ref_cashinRecurringQrisMpmId)
                    WHERE a.ref_merchantId='$refMerchantId' AND a.id ='$id'";

        // var_dump($query);

        return $this->db->query($query)->result_array();
    }
}
?>