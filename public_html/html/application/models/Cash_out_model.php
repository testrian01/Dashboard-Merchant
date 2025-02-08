<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Cash_out_model extends CI_Model {

    public function get_pulsa_data($provider) {
        
        $query = $this->db->query("SELECT * FROM cashout_channel cc WHERE c_channelGroup2 = ? ORDER BY cc.c_fee ASC", array($provider));
        return $query->result();
        
    }

    public function get_paket_data($provider) {
        
        $query = $this->db->query("SELECT * FROM cashout_channel cc WHERE c_channelGroup2 = ? ORDER BY cc.c_fee ASC", array($provider));
        return $query->result();
        
    }
    
    public function get_token_listrik_data() {
        $query = $this->db->query('SELECT * FROM cashout_channel cc WHERE c_channelGroup2 = "token_pln" ORDER BY cc.c_fee');
        return $query->result();
    }

    public function get_top_up_gopay() {
        $query = $this->db->query('SELECT * FROM cashout_channel cc WHERE c_channelGroup2 = "topup_gopay" ORDER BY cc.c_fee ASC');
        return $query->result();
    }

    public function get_top_up_dana() {
        $query = $this->db->query('SELECT * FROM cashout_channel cc WHERE c_channelGroup2 = "topup_dana" ORDER BY cc.c_fee ASC');
        return $query->result();
    }

    public function get_top_up_ovo() {
        $query = $this->db->query('SELECT * FROM cashout_channel cc WHERE c_channelGroup2 = "topup_ovo" ORDER BY cc.c_fee ASC');
        return $query->result();
    }
    public function get_top_up_googleplay() {
        $query = $this->db->query('SELECT * FROM cashout_channel cc WHERE c_channelGroup2 = "google_play" ORDER BY cc.c_fee ASC');
        return $query->result();
    }
    public function get_top_up_freefire() {
        $query = $this->db->query('SELECT * FROM cashout_channel cc WHERE c_channelGroup2 = "free_fire" ORDER BY cc.c_fee ASC');
        return $query->result();
    }
    public function get_top_up_garena() {
        $query = $this->db->query('SELECT * FROM cashout_channel cc WHERE c_channelGroup2 = "google_play" ORDER BY cc.c_fee ASC');
        return $query->result();
    }
    public function get_top_up_hago() {
        $query = $this->db->query('SELECT * FROM cashout_channel cc WHERE c_channelGroup2 = "hago" ORDER BY cc.c_fee ASC');
        return $query->result();
    }
    public function get_top_up_mobile_legend() {
        $query = $this->db->query('SELECT * FROM cashout_channel cc WHERE c_channelGroup2 = "diamond_mlbb" ORDER BY cc.c_fee ASC');
        return $query->result();
    }
    public function get_top_up_pubg() {
        $query = $this->db->query('SELECT * FROM cashout_channel cc WHERE c_channelGroup2 = "pubg_mobile" ORDER BY cc.c_fee ASC');
        return $query->result();
    }
    
}