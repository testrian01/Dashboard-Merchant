<?php defined('BASEPATH') OR exit('No direct script access allowed');

class VirtualAccount extends CI_Model {

    public function get_va($refMerchantId, $limit, $start, $search_date_va1 = null, $search_date_va2 = null, $search_date_va_settlement = null, $search_va_invoice_no = null) {
        

        $query = "SELECT 
                    merchant.c_name as name_merchant,
                    cashin_payment_va.id, 
                    cashin_payment_va.c_datetime, 
                    submerchant.c_name as name_submerchant, 
                    cashin.c_invoiceNo, 
                    cashin_payment_va.c_type,
                    cashin_payment_va.ref_merchantId, 
                    cashin_payment_va.ref_subMerchantId, 
                    cashin_payment_va.c_amount, 
                    cashin_payment_va.c_fee,
                    cashin_payment_va.c_datetimePayment, cashin_payment_va.c_isSettlementRealtime, 
                    cashin_payment_va.c_datetimeSettlement, cashin_payment_va.c_isSettlementRealtimeExternal, 
                    cashin_payment_va.c_feeExternal, cashin_payment_va.c_datetimeSettlementExternal,
                    IF(cashin_payment_va.c_type='Dynamic', cashin_dynamic_va.c_merchantTransactionId, cashin_recurring_va.c_merchantTransactionId) AS Merchant_Transaction_Id
                    FROM cashin_payment_va 
                    JOIN cashin on cashin.id = cashin_payment_va.ref_cashinId
                    JOIN submerchant on cashin_payment_va.ref_subMerchantId = submerchant.id 
                    JOIN merchant on cashin_payment_va.ref_merchantId = merchant.id
                    LEFT JOIN cashin_dynamic_va on (cashin_dynamic_va.ref_subMerchantId = cashin_payment_va.ref_subMerchantId AND cashin_dynamic_va.id=cashin_payment_va.ref_cashinDynamicVaId)
                    LEFT JOIN cashin_recurring_va on (cashin_recurring_va.ref_subMerchantId = cashin_payment_va.ref_subMerchantId AND cashin_recurring_va.id=cashin_payment_va.ref_cashinRecurringVaId)
                    WHERE cashin_payment_va.ref_merchantId = '$refMerchantId'";
    
        if (!empty($search_date_va1) && !empty($search_date_va1)) {

            $search_date_va1 = date('Y-m-d', strtotime($search_date_va1));
            $search_date_va2 = date('Y-m-d', strtotime($search_date_va2));

            $query .= " and DATE(cashin_payment_va.c_datetime) between '".$search_date_va1."' and '".$search_date_va2."'";
        }

        if (!empty($search_date_va_settlement)) {
            $search_date_va_settlement = date('Y-m-d', strtotime($search_date_va_settlement));
            $query .= " and DATE(cashin_payment_va.c_datetimeSettlement) = '$search_date_va_settlement'";
        }

        if (!empty($search_va_invoice_no)) {
            $query .= " and cashin.c_invoiceNo= '$search_va_invoice_no'";
        }
    
        $query .= " ORDER BY cashin_payment_va.id DESC
                    LIMIT $start, $limit";

        // var_dump($query);

        return $this->db->query($query)->result();
    }

    public function count_va($refMerchantId, $search_date_va = null) {

        $query = "SELECT 
                cashin_payment_va.id
                FROM cashin_payment_va 
                join cashin on cashin.id = cashin_payment_va.ref_cashinId
                join submerchant on cashin_payment_va.ref_subMerchantId=submerchant.id 
                LEFT JOIN cashin_dynamic_va on (cashin_dynamic_va.ref_merchantId=cashin_payment_va.ref_merchantId AND cashin_dynamic_va.id=cashin_payment_va.ref_cashinDynamicVaId)
                LEFT JOIN cashin_recurring_va on (cashin_recurring_va.ref_merchantId=cashin_payment_va.ref_merchantId AND cashin_recurring_va.id=cashin_payment_va.ref_cashinRecurringVaId)
                where cashin_payment_va.ref_merchantId  = $refMerchantId";

        if ($search_date_va) {
            $query .= " AND DATE(cashin_payment_va.c_datetime) = '$search_date_va'";
        }

        return $this->db->query($query)->num_rows();
    }

    public function va_detail($refMerchantId, $id)
    {
        $query = "SELECT a.c_datetime, a.ref_merchantId, c.c_name AS name_merchant, a.ref_subMerchantId, 
                    d.c_name AS name_submerchant, b.c_invoiceNo, a.ref_cashinChannelId,
                    a.c_type, a.c_amount, a.c_fee, a.c_datetimePayment, 
                    a.c_isSettlementRealtime, a.c_datetimeSettlement, 
                    IF(a.c_type='Dynamic', e.c_merchantTransactionId, f.c_merchantTransactionId) AS c_merchantTransactionId
                    FROM cashin_payment_va a
                    JOIN cashin b ON b.id=a.ref_cashinId
                    JOIN merchant c ON a.ref_merchantId=c.id
                    JOIN submerchant d ON a.ref_subMerchantId=d.id
                    LEFT JOIN cashin_dynamic_va e ON (e.ref_merchantId=a.ref_merchantId AND e.id=a.ref_cashinDynamicVaId) 
                    LEFT JOIN cashin_recurring_va f ON (f.ref_merchantId=a.ref_merchantId AND e.id=a.ref_cashinRecurringVaId)
                    WHERE a.ref_merchantId='$refMerchantId' AND a.id ='$id'";

        // var_dump($query);

        return $this->db->query($query)->result_array();
    }

    public function insertVaDynamic($dataInsert2) {
        foreach ($dataInsert2 as $key => $value) {
            if (is_array($value)) {
                $dataInsert2[$key] = json_encode($value);
            }
        }

        $this->db->insert('cashin_dynamic_va', $dataInsert2);
        return ($this->db->affected_rows() != 1) ? false : true;
    }
    
    public function updateVaDynamic($data, $id) {
        $this->db->where('id', $id);
        $this->db->update('cashin_dynamic_va', $data);
        return ($this->db->affected_rows() != 1) ? false : true;
    }
    public function get_detail_va($idRequest2) {
        $query = "select cdv.*, m.c_name, m.id from cashin_dynamic_va cdv join merchant m on m.id = cdv.ref_merchantId where cdv.id = $idRequest2";
        return $this->db->query($query)->result_array();
    }
}
?>