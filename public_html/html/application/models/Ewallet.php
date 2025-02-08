<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Ewallet extends CI_Model {

    public function get_ewallet($refMerchantId, $limit, $start, $search_date_ewallet = null, $search_date_ewallet_settlement = null, $search_invoice_no = null)
    {

        $query = "SELECT 
                    merchant.c_name as name_merchant,
                    cashin_payment_ewallet.id, 
                    cashin_payment_ewallet.c_datetime, 
                    submerchant.c_name as name_submerchant, 
                    cashin.c_invoiceNo, 
                    cashin_payment_ewallet.c_type,
                    cashin_payment_ewallet.ref_cashinChannelId,
                    cashin_payment_ewallet.ref_merchantId, 
                    cashin_payment_ewallet.ref_subMerchantId, 
                    cashin_payment_ewallet.c_amount, 
                    cashin_payment_ewallet.c_mdr, 
                    cashin_payment_ewallet.c_fee,
                    cashin_payment_ewallet.c_datetimePayment, cashin_payment_ewallet.c_isSettlementRealtime, 
                    cashin_payment_ewallet.c_datetimeSettlement, cashin_payment_ewallet.c_isSettlementRealtimeExternal, 
                    cashin_payment_ewallet.c_feeExternal, cashin_payment_ewallet.c_datetimeSettlementExternal,
                    IF(cashin_payment_ewallet.c_type='Dynamic', cashin_dynamic_ewallet.c_merchantTransactionId, cashin_recurring_ewallet.c_merchantTransactionId) AS Merchant_Transaction_Id
                    FROM cashin_payment_ewallet 
                    JOIN cashin on cashin.id = cashin_payment_ewallet.ref_cashinId
                    JOIN submerchant on cashin_payment_ewallet.ref_subMerchantId = submerchant.id 
                    JOIN merchant on cashin_payment_ewallet.ref_merchantId = merchant.id
                    LEFT JOIN cashin_dynamic_ewallet on (cashin_dynamic_ewallet.ref_merchantId = cashin_payment_ewallet.ref_merchantId AND cashin_dynamic_ewallet.id=cashin_payment_ewallet.ref_cashinDynamicEwalletId)
                    LEFT JOIN cashin_recurring_ewallet on (cashin_recurring_ewallet.ref_merchantId = cashin_payment_ewallet.ref_merchantId AND cashin_recurring_ewallet.id=cashin_payment_ewallet.ref_cashinRecurringEwalletId)
                    WHERE cashin_dynamic_ewallet.ref_merchantId = '$refMerchantId'";

        if (!empty($search_date_ewallet)) {
            $search_date_ewallet = date('Y-m-d', strtotime($search_date_ewallet));
            $query .= " and DATE(cashin_payment_ewallet.c_datetime) = '$search_date_ewallet'";
        }

        if (!empty($search_date_ewallet_settlement)) {
            $search_date_ewallet_settlement = date('Y-m-d', strtotime($search_date_ewallet_settlement));
            $query .= " and DATE(cashin_payment_ewallet.c_datetimeSettlement) = '$search_date_ewallet_settlement'";
        }

        if (!empty($search_invoice_no)) {
            $query .= " and cashin.c_invoiceNo= '$search_invoice_no'";
        }


        $query .= " ORDER BY cashin_payment_ewallet.id DESC
                    LIMIT $start, $limit";

        // var_dump($query);

        return $this->db->query($query)->result();
    }

    public function count_ewallet($refMerchantId, $search_date_ewallet = null)
    {

        $query = "SELECT 
            cashin_payment_ewallet.id
            FROM cashin_payment_ewallet 
            JOIN cashin on cashin.id = cashin_payment_ewallet.ref_cashinId
            JOIN submerchant on cashin_payment_ewallet.ref_subMerchantId = submerchant.id 
            LEFT JOIN cashin_dynamic_ewallet on (cashin_dynamic_ewallet.ref_merchantId = cashin_payment_ewallet.ref_merchantId AND cashin_dynamic_ewallet.id=cashin_payment_ewallet.ref_cashinDynamicEwalletId)
            LEFT JOIN cashin_recurring_ewallet on (cashin_recurring_ewallet.ref_merchantId = cashin_payment_ewallet.ref_merchantId AND cashin_recurring_ewallet.id=cashin_payment_ewallet.ref_cashinRecurringEwalletId)
            where cashin_payment_ewallet.ref_merchantId  = $refMerchantId";

        if ($search_date_ewallet) {
            $query .= " AND DATE(cashin_payment_ewallet.c_datetime) = '$search_date_ewallet'";
        }

        return $this->db->query($query)->num_rows();
    }
    
    public function ewallet_detail($refMerchantId, $id)
    {
        $query = "SELECT a.c_datetime, a.ref_merchantId, c.c_name AS name_merchant, a.ref_subMerchantId, 
                    d.c_name AS name_submerchant, b.c_invoiceNo, 
                    a.c_type, a.ref_cashinChannelId, 
                    a.c_amount, a.c_mdr, a.c_fee, a.c_datetimePayment,
                    a.c_isSettlementRealtime, a.c_datetimeSettlement, 
                    IF(a.c_type='Dynamic', e.c_merchantTransactionId, f.c_merchantTransactionId) AS c_merchantTransactionId
                    FROM cashin_payment_ewallet a
                    JOIN cashin b ON b.id=a.ref_cashinId
                    JOIN merchant c ON a.ref_merchantId=c.id
                    JOIN submerchant d ON a.ref_subMerchantId=d.id
                    LEFT JOIN cashin_dynamic_ewallet e ON (e.ref_merchantId=a.ref_merchantId AND e.id=a.ref_cashinDynamicEwalletId) 
                    LEFT JOIN cashin_recurring_ewallet f ON (f.ref_merchantId=a.ref_merchantId AND e.id=a.ref_cashinRecurringEwalletId)
                    WHERE a.ref_merchantId='$refMerchantId' AND a.id ='$id'";

        // var_dump($query);

        return $this->db->query($query)->result_array();
    }

    public function insertEwalletDynamic($dataInsert2) {
        foreach ($dataInsert2 as $key => $value) {
            if (is_array($value)) {
                $dataInsert2[$key] = json_encode($value);
            }
        }

        $this->db->insert('cashin_dynamic_ewallet', $dataInsert2);
        return ($this->db->affected_rows() != 1) ? false : true;
    }
    
    public function updateEwalletDynamic($data, $id) {
        $this->db->where('id', $id);
        $this->db->update('cashin_dynamic_ewallet', $data);
        return ($this->db->affected_rows() != 1) ? false : true;
    }
    public function get_detail_ewallet($idRequest2) {
        $query = "select cdv.*, m.c_name, m.id from cashin_dynamic_ewallet cdv join merchant m on m.id = cdv.ref_merchantId where cdv.id = $idRequest2";
        return $this->db->query($query)->result_array();
    }
}
?>