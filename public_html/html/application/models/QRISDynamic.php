<?php defined('BASEPATH') or exit('No direct script access allowed');

class QRISDynamic extends CI_Model
{

    public function get_qrisdynamic($refMerchantId, $limit, $start, $search_date_qd = null, $search_transid_qd = null, $search_status_transaction_qd = null)
    {
        $query = "SELECT cashin_dynamic_qris_mpm.*, submerchant.c_name as name_submerchant, merchant.c_name as name_merchant
            FROM cashin_dynamic_qris_mpm 
            JOIN submerchant  on cashin_dynamic_qris_mpm.ref_subMerchantId = submerchant.id
            JOIN merchant on cashin_dynamic_qris_mpm.ref_merchantId = merchant.id
            WHERE cashin_dynamic_qris_mpm.ref_merchantId = '$refMerchantId'";


        if (!empty($search_date_qd)) {
            $search_date_qd = date('Y-m-d', strtotime($search_date_qd));
            $query .= " AND DATE(cashin_dynamic_qris_mpm.c_datetimeRequest) = '$search_date_qd'";
        }

        if (!empty($search_transid_qd)) {
            $query .= " AND cashin_dynamic_qris_mpm.c_merchantTransactionId ='$search_transid_qd'";
        }

        if (!empty($search_status_transaction_qd)) {
            $query .= " AND cashin_dynamic_qris_mpm.c_status ='$search_status_transaction_qd'";
        }

        $query .= " ORDER BY cashin_dynamic_qris_mpm.id DESC
                LIMIT $start, $limit";

        // var_dump($query);
        return $this->db->query($query)->result();
    }

    public function count_qrisdynamic($refMerchantId, $search_date_qd = null)
    {
        $query = "SELECT 
                cdqm.id from cashin_dynamic_qris_mpm cdqm 
                    join submerchant s on cdqm.ref_subMerchantId = s.id
                    where cdqm.ref_merchantId = $refMerchantId";

        if ($search_date_qd) {
            $query .= " AND cdqm.c_datetimeRequest = '$search_date_qd'";
        }

        return $this->db->query($query)->num_rows();
    }

    public function cashinChannelExternal($cashinExternalId, $cashinChannelId) {

        $this->db->select('*');
        $this->db->from('cashin_external_x_channel');
        $this->db->where('c_cashinExternalId', $cashinExternalId);
        $this->db->where('ref_cashinChannelId', $cashinChannelId);
        $this->db->where('c_status', 'Active');
        
        $query = $this->db->get();
        if($query->num_rows()){
            return $query->row();
        }
        return false;
    }

    public function getDynamicQrMpmFromId($merchantId, $id){
        $this->db->select('*');
        $this->db->from('cashin_dynamic_qris_mpm');
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