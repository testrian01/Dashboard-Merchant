<?php defined('BASEPATH') or exit('No direct script access allowed');

class EwalletDynamic extends CI_Model
{

    public function get_ewalletdynamic($refMerchantId, $limit, $start, $search_date_ewallet = null, $search_transid_ewallet = null, $search_status_transaction_ewallet = null)
    {
        $query = "SELECT cashin_dynamic_ewallet.*, submerchant.c_name as name_submerchant, merchant.c_name as name_merchant
            FROM cashin_dynamic_ewallet 
            JOIN submerchant  on cashin_dynamic_ewallet.ref_subMerchantId = submerchant.id
            JOIN merchant on cashin_dynamic_ewallet.ref_merchantId = merchant.id
            WHERE cashin_dynamic_ewallet.ref_merchantId = '$refMerchantId'";


        if (!empty($search_date_ewallet)) {
            $search_date_ewallet = date('Y-m-d', strtotime($search_date_ewallet));
            $query .= " AND DATE(cashin_dynamic_ewallet.c_datetimeRequest) = '$search_date_ewallet'";
        }

        if (!empty($search_transid_ewallet)) {
            $query .= " AND cashin_dynamic_ewallet.c_merchantTransactionId ='$search_transid_ewallet'";
        }

        if (!empty($search_status_transaction_ewallet)) {
            $query .= " AND cashin_dynamic_ewallet.c_status ='$search_status_transaction_ewallet'";
        }

        $query .= " ORDER BY cashin_dynamic_ewallet.id DESC
                LIMIT $start, $limit";

        // var_dump($query);
        return $this->db->query($query)->result();
    }

    public function count_ewalletdynamic($refMerchantId, $search_date_ewallet = null)
    {
        $query = "SELECT 
                cdqm.id from cashin_dynamic_ewallet cdqm 
                    join submerchant s on cdqm.ref_subMerchantId = s.id
                    where cdqm.ref_merchantId = $refMerchantId";

        if ($search_date_ewallet) {
            $query .= " AND cdqm.c_datetimeRequest = '$search_date_ewallet'";
        }

        return $this->db->query($query)->num_rows();
    }

    public function getDynamicQrMpmFromId($merchantId, $id){
        $this->db->select('*');
        $this->db->from('cashin_dynamic_ewallet');
        $this->db->where('id', $id);
        $this->db->where('ref_merchantId', $merchantId);
        
        $query = $this->db->get();
        if($query->num_rows()){
            return $query->row();
        }
        return false;
    }
}


?>