<?php defined('BASEPATH') or exit('No direct script access allowed');

class Mutation_model extends CI_Model
{
 
    public function get_mutations($refMerchantId, $limit, $start, $search_date = null, $search_position = null) {
        
        $query = "SELECT 
            mutation.id, 
            mutation.ref_merchantId, 
            mutation.c_datetime, 
            mutation.c_potition,
            IF(mutation.c_potition = 'Credit', cashin.ref_cashinChannelId, cashout.ref_cashoutChannelId) AS channelName,
            IF(mutation.c_potition = 'Credit', mutation.ref_cashinId, mutation.ref_cashoutId) AS refLog,
            IF(mutation.c_potition = 'Credit', cashin.c_Datetime, cashout.c_Datetime) AS timeRefLog,
            IF(mutation.c_potition = 'Credit', cashin.c_description, cashout.c_description) AS description,
            IF(mutation.c_potition = 'Credit', cashin.c_InvoiceNo, cashout.c_InvoiceNo) AS refNoLog,
            mutation.c_amount,
            mutation.c_BalanceAfter 
            FROM mutation
            LEFT JOIN cashin ON cashin.ref_merchantId = mutation.ref_merchantId AND cashin.id = mutation.ref_cashinId
            LEFT JOIN cashout ON cashout.ref_merchantId = mutation.ref_merchantId AND cashout.Id = mutation.ref_cashoutId
            JOIN merchant ON merchant.id=mutation.ref_merchantId
            WHERE mutation.ref_merchantId = $refMerchantId ";
        // var_dump($query);
        if ($search_date) {
            $search_date = date('Y-m-d', strtotime($search_date));
            $query .= " AND DATE(mutation.c_datetime) = '$search_date'";
        }

        // if ($search_position) {
        //     $query .= " AND mutation.c_potition = '$search_position'";
        // }

        $query .= " LIMIT $start, $limit";
        
        return $this->db->query($query)->result();
    }
    
    
    public function count_mutations($refMerchantId, $search_date = null, $search_potition = null) {
        $query = "SELECT 
            mutation.id
            FROM mutation
            LEFT JOIN cashin ON cashin.ref_merchantId = mutation.ref_merchantId AND cashin.id = mutation.ref_cashinId
            LEFT JOIN cashout ON cashout.ref_merchantId = mutation.ref_merchantId AND cashout.Id = mutation.ref_cashoutId
            JOIN merchant ON merchant.id=mutation.ref_merchantId
            WHERE mutation.ref_merchantId = $refMerchantId ";
        
        if ($search_date) {
            $search_date = date('Y-m-d', strtotime($search_date));
            $query .= " AND DATE(mutation.c_datetime) = '$search_date'";
        }
        if ($search_potition) {
            $query .= " AND mutation.c_potition = '$search_potition'";
        }
        
        return $this->db->query($query)->num_rows();
        return $this->db->count_all_results();
    }  
    
}
?>