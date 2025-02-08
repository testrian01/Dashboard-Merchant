<?php defined('BASEPATH') OR exit('No direct script access allowed');

class VADynamic extends CI_Model {



    public function get_vadynamic($refMerchantId, $limit, $start, $search_date_va = null, $search_transid_va = null, $search_status_transaction_va = null) {
        $query = "SELECT cashin_dynamic_va.*, submerchant.c_name as name_submerchant, merchant.c_name as name_merchant
            FROM cashin_dynamic_va 
            JOIN submerchant  on cashin_dynamic_va.ref_subMerchantId = submerchant.id
            JOIN merchant on cashin_dynamic_va.ref_merchantId = merchant.id
            WHERE cashin_dynamic_va.ref_merchantId = '$refMerchantId'";


        if (!empty($search_date_va)) {
            $search_date_va = date('Y-m-d', strtotime($search_date_va));
            $query .= " AND DATE(cashin_dynamic_va.c_datetimeRequest) = '$search_date_va'";
        }

        if (!empty($search_transid_va)) {
            $query .= " AND cashin_dynamic_va.c_merchantTransactionId ='$search_transid_va'";
        }

        if (!empty($search_status_transaction_va)) {
            $query .= " AND cashin_dynamic_va.c_status ='$search_status_transaction_va'";
        }

        $query .= " ORDER BY cashin_dynamic_va.id DESC
                LIMIT $start, $limit";

        // var_dump($query);
        return $this->db->query($query)->result();
    }


    public function count_vadynamic($refMerchantId, $search_date_va = null)
    {
        $query = "SELECT 
                cdqm.id from cashin_dynamic_va cdqm 
                    join submerchant s on cdqm.ref_subMerchantId = s.id
                    where cdqm.ref_merchantId = $refMerchantId";

        if ($search_date_va) {
            $query .= " AND cdqm.c_datetimeRequest = '$search_date_va'";
        }

        return $this->db->query($query)->num_rows();
    }

}
?>