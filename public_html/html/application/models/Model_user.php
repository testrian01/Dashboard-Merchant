<?php defined('BASEPATH') or exit('No direct script access allowed');

global $internalUrlHit;

class Model_user extends CI_Model
{
    public function __construct()
    {
        parent::__construct();

        global $internalUrlHit;

        $this->internalUrlHit = $internalUrlHit;
    }

    public function view_user()
    {
        return $this->db->get_where('merchant', ['c_email' => $this->session->userdata('c_email')]);
    }

    public function saldo()
    {
        $merchant = $this->db->get_where('merchant', ['c_email' => $this->session->userdata('c_email')])->row();
        if ($merchant) {

            $merchantId = $merchant->id;

            $url = $this->internalUrlHit . '/Merchant/balanceQuery';

            $data = json_encode(array(
                'merchantId' => $merchantId
            ));

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);

            curl_close($ch);

            $result = json_decode($response, true);

            return $result;
        } else {
            return false;
        }
    }

    public function addRole($data, $table)
    {
        $this->db->insert($table, $data);
    }

    public function getUser()
    {
        return $this->db->get('user_role');
    }

    public function hapusRole($where, $table)
    {
        $this->db->where($where);
        $this->db->delete($table);
    }

    public function getActiveCashoutChannel()
    {
        $this->db->select('c_externalIdDefault');
        $this->db->from('cashout_channel');
        $this->db->where('c_channelGroup', 'bifast');
        $this->db->limit(1);

        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->row();
        }
        return false;
    }

    public function getAllBank()
    {
        $this->db->select('id, c_description');
        $this->db->from('cashout_channel');
        $this->db->where('c_channelGroup', 'bifast');
        // $this->db->limit(1);

        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result();
        }
        return false;
    }

    public function getMerchantActiveBankAccount($refMerchantId)
    {
        $this->db->select('a.id, a.ref_cashoutChannelId, a.c_beneficiaryAccountNo, a.c_beneficiaryAccountName, b.c_description');
        $this->db->from('merchant_account_bank a');
        $this->db->join('cashout_channel b', 'b.id = a.ref_cashoutChannelId');


        $this->db->where('a.ref_merchantId', $refMerchantId);
        $this->db->where('a.c_status', 'Active');
        // $this->db->limit(1);

        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result();
        }
        return false;
    }

    public function getMerchantBankAccount($refMerchantId, $limit, $start, $search_date = null)
    {
        $query = "SELECT a.*
                    from merchant_account_bank a 
                    where a.ref_merchantId = $refMerchantId";

        if ($search_date) {
            $search_date = date('Y-m-d', strtotime($search_date));
            $query .= " AND DATE(a.c_createdAt) = '$search_date'";
        }

        $query .= " ORDER BY a.id DESC
                    LIMIT $start, $limit";

        return $this->db->query($query)->result();
    }

    public function count_qrisdynamic($refMerchantId, $search_date = null)
    {
        $query = "SELECT 
                a.id from merchant_account_bank a 
                    where cdqm.ref_merchantId = $refMerchantId";

        if ($search_date) {
            $query .= " AND a.c_createdAt = '$search_date'";
        }

        return $this->db->query($query)->num_rows();
    }

    public function deleteAccount($id, $merchantId)
    {
        $this->db->where('id', $id);
        $this->db->where('ref_merchantId', $merchantId);
        return $this->db->delete('merchant_account_bank'); // Sesuaikan nama tabel Anda
    }

    public function validateOtp($id, $otpCode, $merchantId)
    {
        // Contoh logika validasi OTP

        $this->db->select('id');
        $this->db->from('merchant_account_bank');
        $this->db->where('c_otp', $otpCode);
        $this->db->where('id', $id);
        $this->db->where('ref_merchantId', $merchantId);
        // $this->db->limit(1);

        $query = $this->db->get();
        // if ($query->num_rows()) {
        //     return $query->result();
        // }
        // return false;

        if ($query->num_rows() > 0) {
            // Tandai OTP sebagai digunakan
            $this->db->update('merchant_account_bank', ['c_status' => 'Active'], ['id' => $id, 'ref_merchantId' => $merchantId]);
            return true;
        }

        return false;
    }
}
