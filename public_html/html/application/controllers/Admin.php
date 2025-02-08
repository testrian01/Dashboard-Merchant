<?php defined('BASEPATH') or exit('No direct script access allowed');

global $stateProgram;

global $internalUrlHit;
global $externalUrlHit;

class Admin extends CI_Controller
{
   public function __construct()
   {
      parent::__construct();

      global $stateProgram;

      global $internalUrlHit;
      global $externalUrlHit;

      $this->load->model('Cash_out_model');
      $this->load->model('Mutation_model');
      $this->load->model('Model_user');
      $this->load->model('VirtualAccount');
      $this->load->model('Ewallet');
      $this->load->library('session');

      $this->stateProgram = $stateProgram;

      $this->internalUrlHit = $internalUrlHit;
      $this->externalUrlHit = $externalUrlHit;
   }

   public function index()
   {
      //    if (!$this->session->userdata('c_email')) {
      //       redirect('auth');
      //   }
      $data['title'] = 'Dashboard';
      $data['merchant'] = $this->Model_user->view_user()->row_array();
      $data['saldo'] = $this->Model_user->saldo();

      $this->load->view('templates/user_header.php', $data);
      $this->load->view('templates/user_sidebar.php', $data);
      $this->load->view('templates/user_topbar.php', $data);
      $this->load->view('admin/index', $data);
      $this->load->view('templates/user_footer.php');
   }

   public function role()
   {
      $data['title'] = 'Role';
      $data['merchant'] = $this->Model_user->view_user()->row_array();
      $data['role'] = $this->Model_user->getUser()->result_array();

      $this->form_validation->set_rules('role', 'Role', 'required');

      if ($this->form_validation->run() == false) {
         $this->load->view('templates/user_header.php', $data);
         $this->load->view('templates/user_sidebar.php', $data);
         $this->load->view('templates/user_topbar.php', $data);
         $this->load->view('admin/role', $data);
         $this->load->view('templates/user_footer.php');
      } else {
         $data = [
            'role' => $this->input->post('role')
         ];

         $this->Model_user->addRole($data, 'user_role');

         $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">New Role Added</div>');
         redirect('admin/role');
      }
   }

   public function changeAccess()
   {
      $menu_id = $this->input->post('menuId');
      $role_id = $this->input->post('roleId');

      $data = [
         'role_id' => $role_id,
         'menu_id' => $menu_id
      ];

      $result = $this->db->get_where('user_access_menu', $data);

      if ($result->num_rows() < 1) {
         $this->db->insert('user_access_menu', $data);
      } else {
         $this->db->delete('user_access_menu', $data);
      }

      $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Access Changed!</div>');
   }

   public function deposit()
   {
      if (!$this->session->userdata('c_email')) {
         redirect('auth');
      }
      $data['merchant'] = $this->Model_user->view_user()->row_array();
      $data['title'] = 'Deposit';
      $data['saldo'] = $this->Model_user->saldo();
      $this->load->view('templates/user_header.php', $data);
      $this->load->view('templates/user_sidebar.php', $data);
      $this->load->view('templates/user_topbar.php', $data);
      $this->load->view('deposit/list', $data);
      $this->load->view('templates/user_footer.php', $data);
   }

   public function pulsareguler()
   {

      if (!$this->session->userdata('c_email')) {
         redirect('auth');
      }
      $data['merchant'] = $this->Model_user->view_user()->row_array();
      $data['title'] = 'Pulsa Reguler';
      $data['saldo'] = $this->Model_user->saldo();
      $this->load->view('templates/user_header.php', $data);
      $this->load->view('templates/user_sidebar.php', $data);
      $this->load->view('templates/user_topbar.php', $data);
      $this->load->view('produk/pulsaelektrik', $data);
      $this->load->view('templates/user_footer.php', $data);
   }

   public function get_pulsa_data()
   {

      if (!$this->session->userdata('c_email')) {
         redirect('auth');
      }
      $provider = $this->input->post('provider');
      $data['cashout_channels'] = $this->Cash_out_model->get_pulsa_data($provider);
      $data['merchant'] = $this->Model_user->view_user()->row_array();
      $data['title'] = 'Pulsa Reguler';
      $data['saldo'] = $this->Model_user->saldo();
      $this->load->view('templates/user_header.php', $data);
      $this->load->view('templates/user_sidebar.php', $data);
      $this->load->view('templates/user_topbar.php', $data);
      $this->load->view('produk/pulsaelektrik', $data);
      $this->load->view('templates/user_footer.php', $data);
   }

   public function purchase()
   {
      if (!$this->session->userdata('c_email')) {
         redirect('auth');
      }

      $data['merchant'] = $this->Model_user->view_user()->row_array();

      $merchantId = $data['merchant']['id'];
      $phone = $data['merchant']['c_phoneNumber'];
      $ref_cashoutChannelId = $this->input->post('channel');
      $idCustomer = $this->input->post('Phone');

      $result = $this->sendToAPI($merchantId, $ref_cashoutChannelId, $phone, $idCustomer);
      // var_dump($ref_cashoutChannelId);

      if (isset($result['responseDetail']['result']) && $result['responseDetail']['result'] === 'success') {
         $this->session->set_flashdata('success_message', 'Pembelian berhasil.');
      } else {
         $this->session->set_flashdata('error_message', 'Pembelian gagal.');
      }

      redirect('admin/pulsareguler');
   }


   private function sendToAPI($merchantId, $ref_cashoutChannelId, $phone, $idCustomer)
   {

      $api_url = $this->externalUrlHit . '/Portalpulsa/purchase';
      $data = array(
         'merchantId' => $merchantId,
         'ref_cashoutChannelId' => $ref_cashoutChannelId,
         'phone' => $phone,
         'idCustomer' => $idCustomer,
      );

      // var_dump($data);
      $json_data = json_encode($data);

      $ch = curl_init($api_url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

      $response = curl_exec($ch);
      curl_close($ch);

      $result = json_decode($response, true);

      return $result;
   }

   public function purchase_token($channelId)
   {
      $data['merchant'] = $this->Model_user->view_user()->row_array();

      $merchantId = $data['merchant']['id'];
      $phone = $data['merchant']['c_phoneNumber'];
      $ref_cashoutChannelId = $channelId;
      $idCustomer = $this->input->post('id_pln');
      $name = $this->input->post('name');

      $result = $this->sendToAPIPurchase($merchantId, $ref_cashoutChannelId, $phone, $idCustomer);
      // var_dump($result);
      if (isset($result['responseDetail']['result']) && $result['responseDetail']['result'] === 'success') {
         $this->session->set_flashdata('success_message', 'Pembelian berhasil.');
      } else {
         $this->session->set_flashdata('error_message', 'Pembelian gagal.');
      }

      redirect('admin/' . $name);
   }


   private function sendToAPIPurchase($merchantId, $ref_cashoutChannelId, $phone, $idCustomer)
   {

      $api_url = $this->externalUrlHit . '/Portalpulsa/purchase';
      $data = [
         'merchantId' => $merchantId,
         'ref_cashoutChannelId' => $ref_cashoutChannelId,
         'phone' => $phone,
         'idCustomer' => $idCustomer,
      ];

      // var_dump($data);
      $json_data = json_encode($data);

      $ch = curl_init($api_url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

      $response = curl_exec($ch);
      curl_close($ch);

      $result = json_decode($response, true);

      return $result;
   }

   public function paketdata()
   {
      $data['merchant'] = $this->Model_user->view_user()->row_array();
      $data['title'] = 'Paket Data';
      $data['saldo'] = $this->Model_user->saldo();
      $this->load->view('templates/user_header.php', $data);
      $this->load->view('templates/user_sidebar.php', $data);
      $this->load->view('templates/user_topbar.php', $data);
      $this->load->view('produk/paketdata', $data);
      $this->load->view('templates/user_footer.php', $data);
   }

   public function tokenlistrik()
   {
      $data['merchant'] = $this->Model_user->view_user()->row_array();
      $data['title'] = 'Token Listrik';
      $data['token_listrik'] = $this->Cash_out_model->get_token_listrik_data();

      $data['saldo'] = $this->Model_user->saldo();
      $this->load->view('templates/user_header.php', $data);
      $this->load->view('templates/user_sidebar.php', $data);
      $this->load->view('templates/user_topbar.php', $data);
      $this->load->view('produk/tokenlistrik', $data);
      $this->load->view('templates/user_footer.php', $data);
   }

   public function topupgopay()
   {
      $data['merchant'] = $this->Model_user->view_user()->row_array();
      $data['title'] = 'Top Up Gopay';
      $data['gopay'] = $this->Cash_out_model->get_top_up_gopay();

      // var_dump($data['gopay']);
      $data['saldo'] = $this->Model_user->saldo();

      $this->load->view('templates/user_header.php', $data);
      $this->load->view('templates/user_sidebar.php', $data);
      $this->load->view('templates/user_topbar.php', $data);
      $this->load->view('produk/topupgopay', $data);
      $this->load->view('templates/user_footer.php', $data);
   }
   public function topupdana()
   {
      $data['merchant'] = $this->Model_user->view_user()->row_array();
      $data['title'] = 'Top Up Dana';
      $data['dana'] = $this->Cash_out_model->get_top_up_dana();

      $data['saldo'] = $this->Model_user->saldo();
      $this->load->view('templates/user_header.php', $data);
      $this->load->view('templates/user_sidebar.php', $data);
      $this->load->view('templates/user_topbar.php', $data);
      $this->load->view('produk/topupdana', $data);
      $this->load->view('templates/user_footer.php', $data);
   }
   public function topupovo()
   {
      $data['merchant'] = $this->Model_user->view_user()->row_array();
      $data['title'] = 'Top Up Ovo';
      $data['saldo'] = $this->Model_user->saldo();
      $data['ovo'] = $this->Cash_out_model->get_top_up_ovo();
      $this->load->view('templates/user_header.php', $data);
      $this->load->view('templates/user_sidebar.php', $data);
      $this->load->view('templates/user_topbar.php', $data);
      $this->load->view('produk/topupovo', $data);
      $this->load->view('templates/user_footer.php', $data);
   }
   public function googleplay()
   {
      $data['merchant'] = $this->Model_user->view_user()->row_array();
      $data['title'] = 'List Google Play';
      $data['saldo'] = $this->Model_user->saldo();
      $data['googleplay'] = $this->Cash_out_model->get_top_up_googleplay();
      $this->load->view('templates/user_header.php', $data);
      $this->load->view('templates/user_sidebar.php', $data);
      $this->load->view('templates/user_topbar.php', $data);
      $this->load->view('produk/googleplay', $data);
      $this->load->view('templates/user_footer.php', $data);
   }
   public function freefire()
   {
      $data['merchant'] = $this->Model_user->view_user()->row_array();
      $data['title'] = 'Free Fire';
      $data['saldo'] = $this->Model_user->saldo();
      $data['freefire'] = $this->Cash_out_model->get_top_up_freefire();
      $this->load->view('templates/user_header.php', $data);
      $this->load->view('templates/user_sidebar.php', $data);
      $this->load->view('templates/user_topbar.php', $data);
      $this->load->view('produk/freefire', $data);
      $this->load->view('templates/user_footer.php', $data);
   }
   public function Garena()
   {
      $data['merchant'] = $this->Model_user->view_user()->row_array();
      $data['title'] = 'Garena AOV';
      $data['saldo'] = $this->Model_user->saldo();
      $data['garena'] = $this->Cash_out_model->get_top_up_garena();
      $this->load->view('templates/user_header.php', $data);
      $this->load->view('templates/user_sidebar.php', $data);
      $this->load->view('templates/user_topbar.php', $data);
      $this->load->view('produk/garena', $data);
      $this->load->view('templates/user_footer.php', $data);
   }
   public function hago()
   {
      $data['merchant'] = $this->Model_user->view_user()->row_array();
      $data['title'] = 'Hago';
      $data['saldo'] = $this->Model_user->saldo();
      $data['hago'] = $this->Cash_out_model->get_top_up_hago();
      $this->load->view('templates/user_header.php', $data);
      $this->load->view('templates/user_sidebar.php', $data);
      $this->load->view('templates/user_topbar.php', $data);
      $this->load->view('produk/hago', $data);
      $this->load->view('templates/user_footer.php', $data);
   }

   public function mobilelegend()
   {
      $data['merchant'] = $this->Model_user->view_user()->row_array();
      $data['title'] = 'Mobile Legend Bang Bang';
      $data['saldo'] = $this->Model_user->saldo();
      $data['mlbb'] = $this->Cash_out_model->get_top_up_mobile_legend();
      $this->load->view('templates/user_header.php', $data);
      $this->load->view('templates/user_sidebar.php', $data);
      $this->load->view('templates/user_topbar.php', $data);
      $this->load->view('produk/mobilelegend', $data);
      $this->load->view('templates/user_footer.php', $data);
   }

   public function pubgmobile()
   {
      $data['merchant'] = $this->Model_user->view_user()->row_array();
      $data['title'] = 'PUBG Mobile';
      $data['saldo'] = $this->Model_user->saldo();
      $data['pubg'] = $this->Cash_out_model->get_top_up_pubg();
      $this->load->view('templates/user_header.php', $data);
      $this->load->view('templates/user_sidebar.php', $data);
      $this->load->view('templates/user_topbar.php', $data);
      $this->load->view('produk/pubgmobile', $data);
      $this->load->view('templates/user_footer.php', $data);
   }

   public function mutation()
   {
      if (!$this->session->userdata('c_email')) {
         redirect('auth');
      }

      $data['title'] = 'Mutation';
      $data['saldo'] = $this->Model_user->saldo();
      $data['merchant'] = $this->Model_user->view_user()->row_array();
      $refMerchantId = $data['merchant']['id'];

      $search_position = $this->input->post('search_position');
      $search_date = $this->input->post('search_date');

      if ($search_date) {
         $this->session->set_userdata('search_date', $search_date);
      } else {

         $search_date = $this->session->userdata('search_date');
      }

      $this->db->from('mutation');
      $this->db->where('mutation.ref_merchantId', $refMerchantId);
      if ($search_date) {
         $this->db->like('c_datetime', $search_date);
      }
      $config['total_rows'] = $this->db->count_all_results();

      $config['base_url'] = base_url('admin/mutation');
      $config['per_page'] = 10;
      $config['uri_segment'] = 3;

      $config['full_tag_open'] = '<ul class="pagination justify-content-center">';
      $config['full_tag_close'] = '</ul>';

      $config['first_link'] = 'First';
      $config['last_link'] = 'Last';

      $config['first_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['first_tag_close'] = '</span></li>';

      $config['prev_link'] = '&laquo';
      $config['prev_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['prev_tag_close'] = '</span></li>';

      $config['next_link'] = '&raquo';
      $config['next_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['next_tag_close'] = '</span></li>';

      $config['last_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['last_tag_close'] = '</span></li>';

      $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
      $config['cur_tag_close'] = '</a></li>';

      $config['num_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['num_tag_close'] = '</span></li>';

      $this->pagination->initialize($config);

      $start = $this->uri->segment(3);
      $data['start'] = isset($start) ? $start : 0;

      $data['mutations'] = $this->Mutation_model->get_mutations($refMerchantId, $config['per_page'], $data['start'], $search_date, $search_position);
      // var_dump($data['mutations']);
      $data['position'] = $search_position;
      $data['date'] = $search_date;
      $data['pagination'] = $this->pagination->create_links();

      // Load views
      $this->load->view('templates/user_header.php', $data);
      $this->load->view('templates/user_sidebar.php', $data);
      $this->load->view('templates/user_topbar.php', $data);
      $this->load->view('mutation/list', $data);
      $this->load->view('templates/user_footer.php', $data);
   }

   public function resetMutation()
   {
      $this->session->unset_userdata('search_date');
      redirect('admin/mutation');
   }

   public function download_mutation()
   {

      $this->load->model('Mutation_model');
      $search_date = isset($_GET['search_date']) ? $_GET['search_date'] : '';
      // var_dump($search_date);
      if (empty($search_date)) {
         $this->session->set_flashdata('error_message', 'Please fill date and search before to continue download.');
         redirect('admin/mutation');
      }
      $data['merchant'] = $this->Model_user->view_user()->row_array();
      $refMerchantId = $data['merchant']['id'];

      $additionalFilter = $search_date . '|';

      $data = array(
         'ref_merchantId' => $refMerchantId,
         'c_datetime' => date('Y-m-d H:i:s'),
         'c_additionalFilter' => $additionalFilter,
         'c_type' => 'Mutation',
      );

      $result = $this->db->insert('merchant_download', $data);
      if ($result) {
         $this->session->set_flashdata('success', 'Your request is beeing processed. Please go to Download Report menu to retrieve the file <a href="' . base_url('admin/report') . '">Download Report</a> menu to retrieve the file');
      } else {
         $this->session->set_flashdata('error', 'Failed request download');
      }

      redirect('admin/mutation');
   }

   public function history()
   {

      $this->load->model('History');
      if (!$this->session->userdata('c_email')) {
         redirect('auth');
      }

      $data['title'] = 'Purchase';
      $data['merchant'] = $this->Model_user->view_user()->row_array();
      $data['saldo'] = $this->Model_user->saldo();
      $refMerchantId = $data['merchant']['id'];

      $search_date = $this->input->post('search_date');
      if ($search_date === "") {
         $data['alert_message'] = "tanggal pencarian harus diisi.";
      }

      if ($search_date) {
         $this->session->set_userdata('search_date', $search_date);
      } else {
         $search_date = $this->session->userdata('search_date');
      }

      $this->db->from('cashout_payment_ppob');
      $this->db->where('cashout_payment_ppob.ref_merchantId', $refMerchantId);
      if ($search_date) {
         $this->db->like('c_datetime', $search_date);
      }

      $config['total_rows'] = $this->db->count_all_results();

      $config['base_url'] = base_url('admin/history');
      $config['per_page'] = 10;
      $config['uri_segment'] = 3;

      $config['full_tag_open'] = '<ul class="pagination justify-content-center">';

      $config['full_tag_open'] = '<ul class="pagination justify-content-center">';
      $config['full_tag_close'] = '</ul>';

      $config['first_link'] = 'First';
      $config['last_link'] = 'Last';

      $config['first_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['first_tag_close'] = '</span></li>';

      $config['prev_link'] = '&laquo';
      $config['prev_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['prev_tag_close'] = '</span></li>';

      $config['next_link'] = '&raquo';
      $config['next_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['next_tag_close'] = '</span></li>';

      $config['last_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['last_tag_close'] = '</span></li>';

      $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
      $config['cur_tag_close'] = '</a></li>';

      $config['num_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['num_tag_close'] = '</span></li>';

      $this->pagination->initialize($config);

      $start = $this->uri->segment(3);
      $data['start'] = isset($start) ? $start : 0;

      $data['historys'] = $this->History->get_history($refMerchantId, $config['per_page'], $data['start'], $search_date);
      // var_dump($data['historys']);
      $data['pagination'] = $this->pagination->create_links();

      $this->load->view('templates/user_header.php', $data);
      $this->load->view('templates/user_sidebar.php', $data);
      $this->load->view('templates/user_topbar.php', $data);
      $this->load->view('history/list', $data);
      $this->load->view('templates/user_footer.php', $data);
   }
   public function resetHistory()
   {

      $this->session->unset_userdata('search_date');
      redirect('admin/history');
   }

   public function download_history()
   {

      $search_date = isset($_GET['search_date']) ? $_GET['search_date'] : '';
      // var_dump($search_date);
      if (empty($search_date)) {
         $this->session->set_flashdata('error_message', 'Please fill date and search before to continue download.');
         redirect('admin/history');
      }
      $data['merchant'] = $this->Model_user->view_user()->row_array();
      $refMerchantId = $data['merchant']['id'];

      $additionalFilter = $search_date . '|';

      $data = array(
         'ref_merchantId' => $refMerchantId,
         'c_datetime' => date('Y-m-d H:i:s'),
         'c_additionalFilter' => $additionalFilter,
         'c_type' => 'PPOB',
      );

      $result = $this->db->insert('merchant_download', $data);
      if ($result) {
         $this->session->set_flashdata('success', 'Your request is beeing processed. Please go to Download Report menu to retrieve the file <a href="' . base_url('admin/report') . '">Download Report</a> menu to retrieve the file');
      } else {
         $this->session->set_flashdata('error', 'Failed request download');
      }

      redirect('admin/history');
   }

   public function virtual_account()
   {

      $this->load->model('VirtualAccount');

      if (!$this->session->userdata('c_email')) {
         redirect('auth');
      }

      $data['title'] = 'Virtual Account';
      $data['merchant'] = $this->Model_user->view_user()->row_array();

      if ($data['merchant']['c_openapiStatus'] != "Active") {
         redirect($_SERVER['HTTP_REFERER']);
         exit;
      }

      $data['saldo'] = $this->Model_user->saldo();
      $refMerchantId = $data['merchant']['id'];

      $search_date_va1 = $this->input->post('search_date_va1');
      $search_date_va2 = $this->input->post('search_date_va2');
      $search_date_va_settlement = $this->input->post('search_date_va_settlement');
      $search_invoice_no = $this->input->post('search_invoice_no');

      if ($search_date_va1) {
         $this->session->set_userdata('search_date_va1', $search_date_va1);
      } else {
         $search_date_va1 = $this->session->userdata('search_date_va1');
      }

      if ($search_date_va2) {
         $this->session->set_userdata('search_date_va2', $search_date_va2);
      } else {
         $search_date_va2 = $this->session->userdata('search_date_va2');
      }

      if ($search_date_va_settlement) {
         $this->session->set_userdata('search_date_va_settlement', $search_date_va_settlement);
      } else {
         $search_date_va_settlement = $this->session->userdata('search_date_va_settlement');
      }

      if ($search_invoice_no) {
         $this->session->set_userdata('search_invoice_no', $search_invoice_no);
      } else {
         $search_invoice_no = $this->session->userdata('search_invoice_no');
      }

      $this->db->from('cashin_payment_va');
      $this->db->join('cashin', 'cashin.id = cashin_payment_va.ref_cashinId');
      $this->db->where('cashin_payment_va.ref_merchantId', $refMerchantId);
      if (!empty($search_date_va1) && !empty($search_date_va2)) {

         $date1 = new DateTime($search_date_va1);
         $date2 = new DateTime($search_date_va2);
         $interval = $date1->diff($date2);

         if ($interval->m > 1 || $interval->y > 0 || ($interval->m == 1 && $interval->d > 0)) {

            $this->session->unset_userdata('search_date_va1');
            $this->session->unset_userdata('search_date_va2');
            $this->session->unset_userdata('search_date_va_settlement');
            $this->session->unset_userdata('search_va_invoice_no');

            $this->session->set_flashdata('error_message', 'Range filter date cannot more 1 month.');
            redirect('admin/virtual_account');
         } else {
            $this->db->where("date(cashin_payment_va.c_datetime) between '" . $search_date_va1 . "' and '" . $search_date_va2 . "'");
         }
      }

      if ($search_date_va_settlement) {
         $this->db->like('cashin_payment_va.c_datetimeSettlement', $search_date_va_settlement);
      }

      if ($search_invoice_no) {
         $this->db->where('cashin.c_invoiceNo', $search_invoice_no);
      }

      // echo $this->db->count_all_results();

      $config['total_rows'] = $this->db->count_all_results();

      $config['base_url'] = base_url('admin/virtual_account');
      $config['per_page'] = 10;
      $config['uri_segment'] = 3;

      $config['full_tag_open'] = '<ul class="pagination justify-content-center">';

      $config['full_tag_open'] = '<ul class="pagination justify-content-center">';
      $config['full_tag_close'] = '</ul>';

      $config['first_link'] = 'First';
      $config['last_link'] = 'Last';

      $config['first_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['first_tag_close'] = '</span></li>';

      $config['prev_link'] = '&laquo';
      $config['prev_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['prev_tag_close'] = '</span></li>';

      $config['next_link'] = '&raquo';
      $config['next_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['next_tag_close'] = '</span></li>';

      $config['last_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['last_tag_close'] = '</span></li>';

      $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
      $config['cur_tag_close'] = '</a></li>';

      $config['num_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['num_tag_close'] = '</span></li>';

      $this->pagination->initialize($config);

      $start = $this->uri->segment(3);
      $data['start'] = isset($start) ? $start : 0;

      $data['Vas'] = $this->VirtualAccount->get_va($refMerchantId, $config['per_page'], $data['start'], $search_date_va1, $search_date_va2, $search_date_va_settlement, $search_va_invoice_no);

      $data['pagination'] = $this->pagination->create_links();

      $this->load->view('templates/user_header.php', $data);
      $this->load->view('templates/user_sidebar.php', $data);
      $this->load->view('templates/user_topbar.php', $data);
      $this->load->view('virtualaccount/list', $data);
      $this->load->view('templates/user_footer.php', $data);
   }

   public function VA_detail()
   {
      $id = $this->uri->segment(3);
      $data['merchant'] = $this->Model_user->view_user()->row_array();
      $refMerchantId = $data['merchant']['id'];
      $data['title'] = 'Detail VA';
      $data['saldo'] = $this->Model_user->saldo();

      $this->load->model('VirtualAccount');
      $data['va_data'] = $this->VirtualAccount->va_detail($refMerchantId, $id);

      $this->load->view('templates/user_header.php', $data);
      $this->load->view('templates/user_sidebar.php', $data);
      $this->load->view('templates/user_topbar.php', $data);
      $this->load->view('virtualaccount/detail_va', $data);
      $this->load->view('templates/user_footer.php', $data);
   }

   public function resetVA()
   {
      $this->session->unset_userdata('search_date_va1');
      $this->session->unset_userdata('search_date_va2');
      $this->session->unset_userdata('search_date_va_settlement');
      $this->session->unset_userdata('search_va_invoice_no');
      redirect('admin/virtual_account');
   }

   public function download_VA()
   {

      $search_date_va1 = isset($_GET['search_date_va1']) ? $_GET['search_date_va1'] : '';
      $search_date_va2 = isset($_GET['search_date_va2']) ? $_GET['search_date_va2'] : '';

      if (empty($search_date_va1) || empty($search_date_va2)) {
         $this->session->set_flashdata('error_message', 'Please fill date and search before to continue download.');
         redirect('admin/virtual_account');
      }

      $data['merchant'] = $this->Model_user->view_user()->row_array();
      $refMerchantId = $data['merchant']['id'];

      $additionalFilter = $search_date_va1 . '|' . $search_date_va2 . '|';

      $data = array(
         'ref_merchantId' => $refMerchantId,
         'c_datetime' => date('Y-m-d H:i:s'),
         'c_additionalFilter' => $additionalFilter,
         'c_type' => 'VA',
      );

      $result = $this->db->insert('merchant_download', $data);
      if ($result) {
         $this->session->set_flashdata('success', 'Your request is beeing processed. Please go to Download Report menu to retrieve the file <a href="' . base_url('admin/report') . '">Download Report</a> menu to retrieve the file');
      } else {
         $this->session->set_flashdata('error', 'Failed request download');
      }

      redirect('admin/virtual_account');
   }

   public function SendnotifikasiVA()
   {

      $merchant = $this->Model_user->view_user()->row_array();
      $refMerchantId = $merchant['id'];

      $ref_cashinPaymentVaId = $this->uri->segment(3);

      $internalRequestBody = array(
         "msgType" => "consumer_notification_va",
         "msgInfo" => array(
            "ref_cashinPaymentVaId" => $ref_cashinPaymentVaId,
            "merchantId" => $refMerchantId
         )
      );

      $internalUrlHit = $this->internalUrlHit . "/Rabbitmq/createQueue";

      $internalCurl = curl_init();
      curl_setopt_array($internalCurl, array(
         CURLOPT_URL => $internalUrlHit,
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_TIMEOUT => 30,
         CURLOPT_FOLLOWLOCATION => true,
         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
         CURLOPT_SSL_VERIFYHOST => 0,
         CURLOPT_SSL_VERIFYPEER => 0,
         CURLOPT_CUSTOMREQUEST => 'POST',
         CURLOPT_POSTFIELDS => json_encode($internalRequestBody),
         CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
         ),
      ));

      curl_exec($internalCurl);
      curl_close($internalCurl);

      $this->session->set_flashdata('success', 'Notification has resend');

      redirect('admin/virtual_account');
   }


   public function simulationPaymentQris()
   {

      $this->load->model('QRISDynamic');

      $merchant = $this->Model_user->view_user()->row_array();
      $refMerchantId = $merchant['id'];

      $ref_cashinDynamicQrisMpmId = $this->uri->segment(3);

      $getDynamicQrMpmFromId = $this->QRISDynamic->getDynamicQrMpmFromId($refMerchantId, $ref_cashinDynamicQrisMpmId);

      if (!empty($getDynamicQrMpmFromId->id) && $getDynamicQrMpmFromId->c_status == 'Created') {

         $payAmount = round($getDynamicQrMpmFromId->c_amount, 2);
         $feeExternal = 0;
         $creditAmount = 0;


         $cashinChannelExternal = $this->QRISDynamic->cashinChannelExternal($getDynamicQrMpmFromId->ref_cashinExternalId, 'qris_mpm');
         if (!empty($cashinChannelExternal->id)) {
            if ($cashinChannelExternal->c_feeType == 'Percetange') {
               $feeExternal = $payAmount * $cashinChannelExternal->c_fee / 100;
            } else {
               $feeExternal = $cashinChannelExternal->c_fee;
            }
         }



         if ($getDynamicQrMpmFromId->ref_cashinExternalId == 'gvconnect') {

            $creditAmount = $payAmount - $feeExternal;

            $ChannelType = "Dynamic";
            $InvoiceNo = "QRIS_MPM_240220_00000033";
            $DatePayment = date("Y-m-d H:i:s");


            // $signature = $this->generateSignature($getDynamicQrMpmFromId->ref_merchantId, $getDynamicQrMpmFromId->ref_subMerchantId, $ChannelType, $InvoiceNo, $getDynamicQrMpmFromId->c_merchantTransactionId, $DatePayment, $payAmount, $cashinChannelExternal->c_fee, $feeExternal, 1, $DatePayment, "ThXV8GzZi2CUAHXXlph1ZvDQDOio1S");

            $signatureMust1 = hash('sha256', $getDynamicQrMpmFromId->ref_subMerchantId . $ChannelType . $InvoiceNo . $getDynamicQrMpmFromId->c_merchantTransactionId . $DatePayment . $payAmount . $cashinChannelExternal->c_fee . $feeExternal . "1" . $DatePayment . "ThXV8GzZi2CUAHXXlph1ZvDQDOio1S");
            $signature = hash('sha256', $getDynamicQrMpmFromId->ref_merchantId . $signatureMust1);
            // echo "Signature : " . $signature;
            // exit;

            //    {
            //       "merchantId": "{{merchantId}}",
            //       "subMerchantId": "{{subMerchantId}}",
            //       "channelType": "Dynamic",
            //       "invoiceNo": "QRIS_MPM_240220_00000032",
            //       "transactionId": "{{transactionId}}",
            //       "datetimePayment": "2024-02-23 03:53:48",
            //       "amount": 10000,
            //       "mdr": 0.7,
            //       "fee": 70,
            //       "isSettlementRealtime": 0,
            //       "settlementDate": "2024-02-26 18:00:00",
            //       "signature": "{{signature}}"
            //   }

            $externalRequestBody = array(
               "merchantId" => $getDynamicQrMpmFromId->ref_merchantId,
               "subMerchantId" => $getDynamicQrMpmFromId->ref_subMerchantId,
               "channelType" => 'Dynamic',
               "invoiceNo" => $InvoiceNo,
               "transactionId" => $getDynamicQrMpmFromId->c_merchantTransactionId,
               "datetimePayment" => $DatePayment,
               "amount" => $payAmount,
               "mdr" => $cashinChannelExternal->c_fee,
               "fee" => $feeExternal,
               "isSettlementRealtime" => 1,
               "settlementDate" => $DatePayment,
               "signature" => $signature
            );

            // echo json_encode($externalRequestBody);
            // exit;


            // $externalRequestBody = array(
            //    "originalReferenceNo"         => $originalReferenceNo,
            //    "latestTransactionStatus"     => "00",
            //    "additionalInfo"              => array(
            //       "type"               => "payment_qris",
            //       "status"             => "success",
            //       "datetime"           => date('Y-m-d H:i:s'),
            //       "merchant_id"        => "1204",
            //       "bussiness_id"       => "24090200001",
            //       "reference_label"    => '',
            //       "rrn"                => "294128ip17tr",
            //       "bill_number"        => '',
            //       "invoice_no"         => date('ymdHis').rand(11,99),
            //       "amount"             => '',
            //       "mdr"                => '',
            //       "final_amount"       => '',
            //       "issuer_name"        => "GV e-Money",
            //       "issuer_id"          => "93600916",
            //       "customer_name"      => "MUHAMMAD FAUZI",
            //       "customer_pan"       => "9360091600002444998",
            //       "store_label"        => "",
            //       "terminal_label"     => "A01"
            //    )
            // );

            // $externalUrlHit = 'https://stagginggatewayexternal.gidi.co.id/gvconnect/snap/qr-mpm/api/v1.0/qr-mpm-notify';
            $externalUrlHit = 'https://stagginggatewayexternal.masin.co.id/gidi/Callback/QrisMpm';

            $externalCurl = curl_init();
            curl_setopt_array($externalCurl, array(
               CURLOPT_URL => $externalUrlHit,
               CURLOPT_RETURNTRANSFER => true,
               CURLOPT_ENCODING => '',
               CURLOPT_MAXREDIRS => 10,
               CURLOPT_TIMEOUT => 30,
               CURLOPT_FOLLOWLOCATION => true,
               CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
               CURLOPT_SSL_VERIFYHOST => 0,
               CURLOPT_SSL_VERIFYPEER => 0,
               CURLOPT_CUSTOMREQUEST => 'POST',
               CURLOPT_POSTFIELDS => json_encode($externalRequestBody),
               CURLOPT_HTTPHEADER => array(
                  'Content-Type: application/json'
               ),
            ));

            // $externalResponseBody = curl_exec($externalCurl);
            // curl_close($externalCurl);

            $externalResponseBody = curl_exec($externalCurl);


            // Mengecek jika terjadi error pada curl_exec
            if ($externalResponseBody === false) {
               // Menangani error jika ada
               $error = curl_error($externalCurl);
               // echo "Curl Error: " . $error;
               $this->session->set_flashdata('error', $error);
            } else {
               // Mendapatkan kode HTTP response
               $httpCode = curl_getinfo($externalCurl, CURLINFO_HTTP_CODE);

               // Menampilkan response body dan HTTP code
               // echo "HTTP Code: " . $httpCode . "\n";
               // echo "Response Body: " . $externalResponseBody;
               if ($httpCode == 200) {
                  $this->session->set_flashdata('success', 'Simulation success');
               } else {
                  $this->session->set_flashdata('error', 'Response Code not 200 | ' . $httpCode . ' | ' . json_encode($externalResponseBody));
               }
            }

            curl_close($externalCurl);



            // echo '<pre>';
            // print_r($externalResponseBody);
            // echo '</pre>';
            // exit;

            // $this->session->set_flashdata('success', 'Simulation success');

         } else {
            $this->session->set_flashdata('success', 'Transaction not created not found, Error code: #222');
         }
      } else {
         $this->session->set_flashdata('success', 'Bill not found, Error code: #111');
      }

      redirect('admin/qris_dynamic');
   }

   private function generateSignature($merchantId, $subMerchantId, $channelType, $invoiceNo, $transactionId, $datetimePayment, $amount, $mdr, $fee, $isSettlementRealtime, $settlementDate, $credentialKey)
   {
      // Gabungkan data menjadi string untuk sub hash (inner hash)
      $dataToHashInner = $subMerchantId . $channelType . $invoiceNo . $transactionId . $datetimePayment . $amount . $mdr . $fee . $isSettlementRealtime . $settlementDate . $credentialKey;
      // Hash pertama: SHA256 pada string sub data
      $hashInner = hash('sha256', $dataToHashInner);

      // Gabungkan merchantId dengan hash pertama
      $dataToHashOuter = $merchantId . $hashInner;

      // Hash kedua: SHA256 pada string outer data
      $hashOuter = hash('sha256', $dataToHashOuter);

      // Return signature hash (hasil akhir)
      return $hashOuter;
   }


   public function qris()
   {

      $this->load->model('Qris');

      if (!$this->session->userdata('c_email')) {
         redirect('auth');
      }

      $data['title'] = 'QRIS';
      $data['merchant'] = $this->Model_user->view_user()->row_array();

      if ($data['merchant']['c_openapiStatus'] != "Active") {
         redirect($_SERVER['HTTP_REFERER']);
         exit;
      }
      $data['saldo'] = $this->Model_user->saldo();
      $refMerchantId = $data['merchant']['id'];

      $search_date_qris1 = $this->input->post('search_date_qris1');
      $search_date_qris2 = $this->input->post('search_date_qris2');
      $search_date_qris_settlement = $this->input->post('search_date_qris_settlement');
      $search_qris_invoice_no = $this->input->post('search_qris_invoice_no');


      if ($search_date_qris1) {
         $this->session->set_userdata('search_date_qris1', $search_date_qris1);
      } else {
         $search_date_qris1 = $this->session->userdata('search_date_qris1');
      }

      if ($search_date_qris2) {
         $this->session->set_userdata('search_date_qris2', $search_date_qris2);
      } else {
         $search_date_qris2 = $this->session->userdata('search_date_qris2');
      }

      if ($search_date_qris_settlement) {
         $this->session->set_userdata('search_date_qris_settlement', $search_date_qris_settlement);
      } else {
         $search_date_qris_settlement = $this->session->userdata('search_date_qris_settlement');
      }

      if ($search_qris_invoice_no) {
         $this->session->set_userdata('search_qris_invoice_no', $search_qris_invoice_no);
      } else {
         $search_qris_invoice_no = $this->session->userdata('search_qris_invoice_no');
      }

      $this->db->from('cashin_payment_qris_mpm');
      $this->db->join('cashin', 'cashin.id = cashin_payment_qris_mpm.ref_cashinId');
      $this->db->where('cashin_payment_qris_mpm.ref_merchantId', $refMerchantId);
      if (!empty($search_date_qris1) && !empty($search_date_qris2)) {

         $date1 = new DateTime($search_date_qris1);
         $date2 = new DateTime($search_date_qris2);
         $interval = $date1->diff($date2);

         if ($interval->m > 1 || $interval->y > 0 || ($interval->m == 1 && $interval->d > 0)) {

            $this->session->unset_userdata('search_date_qris1');
            $this->session->unset_userdata('search_date_qris2');
            $this->session->unset_userdata('search_date_qris_settlement');
            $this->session->unset_userdata('search_qris_invoice_no');

            $this->session->set_flashdata('error_message', 'Range filter date cannot more 1 month.');
            redirect('admin/qris');
         } else {
            $this->db->where("date(cashin_payment_qris_mpm.c_datetime) between '" . $search_date_qris1 . "' and '" . $search_date_qris2 . "'");
         }
      }
      if ($search_date_qris_settlement) {
         $this->db->where('cashin_payment_qris_mpm.c_datetimeSettlement', $search_date_qris_settlement);
      }

      if ($search_qris_invoice_no) {
         $this->db->where('cashin.c_invoiceNo', $search_qris_invoice_no);
      }

      $config['base_url'] = base_url('admin/qris');
      $config['total_rows'] = $this->db->count_all_results();

      $config['per_page'] = 10;
      $config['uri_segment'] = 3;

      $config['full_tag_open'] = '<ul class="pagination justify-content-center">';
      $config['full_tag_close'] = '</ul>';

      $config['first_link'] = 'First';
      $config['last_link'] = 'Last';

      $config['first_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['first_tag_close'] = '</span></li>';

      $config['prev_link'] = '&laquo';
      $config['prev_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['prev_tag_close'] = '</span></li>';

      $config['next_link'] = '&raquo';
      $config['next_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['next_tag_close'] = '</span></li>';

      $config['last_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['last_tag_close'] = '</span></li>';

      $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
      $config['cur_tag_close'] = '</a></li>';

      $config['num_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['num_tag_close'] = '</span></li>';

      $this->pagination->initialize($config);

      $start = $this->uri->segment(3);
      $data['start'] = isset($start) ? $start : 0;

      $data['qriss'] = $this->Qris->get_qris($refMerchantId, $config['per_page'], $data['start'], $search_date_qris1, $search_date_qris2, $search_date_qris_settlement, $search_qris_invoice_no);
      $data['pagination'] = $this->pagination->create_links();

      $this->load->view('templates/user_header.php', $data);
      $this->load->view('templates/user_sidebar.php', $data);
      $this->load->view('templates/user_topbar.php', $data);
      $this->load->view('qris/list', $data);
      $this->load->view('templates/user_footer.php', $data);
   }

   public function qris_detail()
   {
      $id = $this->uri->segment(3);
      $data['merchant'] = $this->Model_user->view_user()->row_array();
      $data['title'] = 'Detail QRIS';
      $refMerchantId = $data['merchant']['id'];
      $data['saldo'] = $this->Model_user->saldo();

      $this->load->model('Qris');
      $data['qris_data'] = $this->Qris->qris_detail($refMerchantId, $id);

      $this->load->view('templates/user_header.php', $data);
      $this->load->view('templates/user_sidebar.php', $data);
      $this->load->view('templates/user_topbar.php', $data);
      $this->load->view('qris/detail_qris', $data);
      $this->load->view('templates/user_footer.php', $data);
   }

   public function resetqris()
   {
      $this->session->unset_userdata('search_date_qris1');
      $this->session->unset_userdata('search_date_qris2');
      $this->session->unset_userdata('search_date_qris_settlement');
      $this->session->unset_userdata('search_qris_invoice_no');
      redirect('admin/qris');
   }

   public function download_qris()
   {

      $search_date_qris1 = isset($_GET['search_date_qris1']) ? $_GET['search_date_qris1'] : '';
      $search_date_qris2 = isset($_GET['search_date_qris2']) ? $_GET['search_date_qris2'] : '';
      if (empty($search_date_qris1) || empty($search_date_qris2)) {
         $this->session->set_flashdata('error_message', 'Please fill date and search before to continue download.');
         redirect('admin/qris');
      }

      $data['merchant'] = $this->Model_user->view_user()->row_array();
      $refMerchantId = $data['merchant']['id'];

      $additionalFilter = $search_date_qris1 . '|' . $search_date_qris2 . '|';

      $data = array(
         'ref_merchantId' => $refMerchantId,
         'c_datetime' => date('Y-m-d H:i:s'),
         'c_additionalFilter' => $additionalFilter,
         'c_type' => 'QRIS',
      );

      $result = $this->db->insert('merchant_download', $data);
      if ($result) {
         $this->session->set_flashdata('success', 'Your request is beeing processed. Please go to Download Report menu to retrieve the file <a href="' . base_url('admin/report') . '">Download Report</a> menu to retrieve the file');
      } else {
         $this->session->set_flashdata('error', 'Failed request download');
      }

      redirect('admin/qris');
   }

   public function SendnotifikasiQRIS()
   {
      $merchant = $this->Model_user->view_user()->row_array();
      $refMerchantId = $merchant['id'];

      $ref_cashinPaymentQrisMpmId = $this->uri->segment(3);

      $internalRequestBody = array(
         "msgType" => "consumer_notification_qris_mpm",
         "msgInfo" => array(
            "ref_cashinPaymentQrisMpmId" => $ref_cashinPaymentQrisMpmId,
            "merchantId" => $refMerchantId
         )
      );

      $internalUrlHit = $this->internalUrlHit . "/Rabbitmq/createQueue";

      $internalCurl = curl_init();
      curl_setopt_array($internalCurl, array(
         CURLOPT_URL => $internalUrlHit,
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_ENCODING => '',
         CURLOPT_MAXREDIRS => 10,
         CURLOPT_TIMEOUT => 30,
         CURLOPT_FOLLOWLOCATION => true,
         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
         CURLOPT_SSL_VERIFYHOST => 0,
         CURLOPT_SSL_VERIFYPEER => 0,
         CURLOPT_CUSTOMREQUEST => 'POST',
         CURLOPT_POSTFIELDS => json_encode($internalRequestBody),
         CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
         ),
      ));

      curl_exec($internalCurl);
      curl_close($internalCurl);

      $this->session->set_flashdata('success', 'Notification has resend');

      redirect('admin/qris');
   }

   public function bi_fast()
   {

      $this->load->model('BiFast');

      if (!$this->session->userdata('c_email')) {
         redirect('auth');
      }

      $data['title'] = 'Disbursement';
      $data['merchant'] = $this->Model_user->view_user()->row_array();
      if ($data['merchant']['c_openapiStatus'] != "Active") {
         redirect($_SERVER['HTTP_REFERER']);
         exit;
      }
      $data['saldo'] = $this->Model_user->saldo();
      $refMerchantId = $data['merchant']['id'];

      $search_date_bifast1 = $this->input->post('search_date_bifast1');
      $search_date_bifast2 = $this->input->post('search_date_bifast2');

      $search_transid_bifast = $this->input->post('search_transid_bifast');
      $search_status_transaction_bifast = $this->input->post('search_status_transaction_bifast');

      if (!empty($search_date_bifast1)) {
         $this->session->set_userdata('search_date_bifast1', $search_date_bifast1);
      } else {
         $search_date_bifast1 = $this->session->userdata('search_date_bifast1');
      }

      if (!empty($search_date_bifast2)) {
         $this->session->set_userdata('search_date_bifast2', $search_date_bifast2);
      } else {
         $search_date_bifast2 = $this->session->userdata('search_date_bifast2');
      }

      if ($search_transid_bifast) {
         $this->session->set_userdata('search_transid_bifast', $search_transid_bifast);
      } else {
         $search_transid_bifast = $this->session->userdata('search_transid_bifast');
      }

      if ($search_status_transaction_bifast) {
         $this->session->set_userdata('search_status_transaction_bifast', $search_status_transaction_bifast);
      } else {
         $search_status_transaction_bifast = $this->session->userdata('search_status_transaction_bifast');
      }

      $this->db->select('merchant.c_name, cashout_payment_bifast.id, cashout_payment_bifast.c_datetime, cashout.c_invoiceNo, cashout_payment_bifast.c_merchantTransactionId, cashout_payment_bifast.ref_cashoutChannelId, cashout_payment_bifast.c_amount, cashout_payment_bifast.c_fee, cashout_payment_bifast.c_status');
      $this->db->from('cashout_payment_bifast');
      $this->db->join('cashout', 'cashout.id = cashout_payment_bifast.ref_cashoutId');
      $this->db->join('merchant', 'merchant.id = cashout_payment_bifast.ref_merchantId');
      $this->db->where('cashout_payment_bifast.ref_merchantId', $refMerchantId);

      if (!empty($search_date_bifast1) && !empty($search_date_bifast2)) {

         $date1 = new DateTime($search_date_bifast1);
         $date2 = new DateTime($search_date_bifast2);
         $interval = $date1->diff($date2);

         if ($interval->m > 1 || $interval->y > 0 || ($interval->m == 1 && $interval->d > 0)) {

            $this->session->unset_userdata('search_date_bifast1');
            $this->session->unset_userdata('search_date_bifast2');
            $this->session->unset_userdata('search_transid_bifast');
            $this->session->unset_userdata('search_status_transaction_bifast');

            $this->session->set_flashdata('error_message', 'Range filter date cannot more 1 month.');
            redirect('admin/bi_fast');
         } else {
            $this->db->where("date(cashout_payment_bifast.c_datetime) between '" . $search_date_bifast1 . "' and '" . $search_date_bifast2 . "'");
         }
      }

      if (!empty($search_transid_bifast)) {
         $this->db->where('cashout_payment_bifast.c_merchantTransactionId', $search_transid_bifast);
      }

      if (!empty($search_status_transaction_bifast)) {
         $this->db->where('cashout_payment_bifast.c_status', $search_status_transaction_bifast);
      }

      $config['total_rows'] = $this->db->count_all_results();

      $config['base_url'] = base_url('admin/bi_fast');
      $config['per_page'] = 10;
      $config['uri_segment'] = 3;

      $config['full_tag_open'] = '<ul class="pagination justify-content-center">';
      $config['full_tag_close'] = '</ul>';

      $config['first_link'] = 'First';
      $config['last_link'] = 'Last';

      $config['first_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['first_tag_close'] = '</span></li>';

      $config['prev_link'] = '&laquo';
      $config['prev_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['prev_tag_close'] = '</span></li>';

      $config['next_link'] = '&raquo';
      $config['next_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['next_tag_close'] = '</span></li>';

      $config['last_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['last_tag_close'] = '</span></li>';

      $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
      $config['cur_tag_close'] = '</a></li>';

      $config['num_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['num_tag_close'] = '</span></li>';

      $this->pagination->initialize($config);

      $start = $this->uri->segment(3);
      $data['start'] = isset($start) ? $start : 0;

      $data['bifasts'] = $this->BiFast->get_bifast($refMerchantId, $config['per_page'], $data['start'], $search_date_bifast1, $search_date_bifast2, $search_transid_bifast, $search_status_transaction_bifast);
      $data['pagination'] = $this->pagination->create_links();
      $data['date'] = $search_date;

      $this->load->view('templates/user_header.php', $data);
      $this->load->view('templates/user_sidebar.php', $data);
      $this->load->view('templates/user_topbar.php', $data);
      $this->load->view('bifast/list', $data);
      $this->load->view('templates/user_footer.php', $data);
   }


   public function bi_fast_detail()
   {
      $id = $this->uri->segment(3);
      $data['merchant'] = $this->Model_user->view_user()->row_array();
      $data['title'] = 'Detail BI Fast';
      $refMerchantId = $data['merchant']['id'];
      $data['saldo'] = $this->Model_user->saldo();

      $this->load->model('BiFast');
      $data['bifast_data'] = $this->BiFast->getBifastDetail($refMerchantId, $id);

      $this->load->view('templates/user_header.php', $data);
      $this->load->view('templates/user_sidebar.php', $data);
      $this->load->view('templates/user_topbar.php', $data);
      $this->load->view('bifast/detail', $data);
      $this->load->view('templates/user_footer.php', $data);
   }

   public function resetbi_fast()
   {
      $this->session->unset_userdata('search_date_bifast1');
      $this->session->unset_userdata('search_date_bifast2');
      $this->session->unset_userdata('search_transid_bifast');
      $this->session->unset_userdata('search_status_transaction_bifast');
      redirect('admin/bi_fast');
   }

   public function download_bi_fast()
   {
      $search_date_bifast1 = isset($_GET['search_date_bifast1']) ? $_GET['search_date_bifast1'] : '';
      $search_date_bifast2 = isset($_GET['search_date_bifast2']) ? $_GET['search_date_bifast2'] : '';

      if (empty($search_date_bifast1) || empty($search_date_bifast2)) {
         $this->session->set_flashdata('error_message', 'Please fill date and search before to continue download.');
         redirect('admin/bi_fast');
      }
      $data['merchant'] = $this->Model_user->view_user()->row_array();
      $refMerchantId = $data['merchant']['id'];

      $additionalFilter = $search_date_bifast1 . '|' . $search_date_bifast2 . '|';

      $data = array(
         'ref_merchantId' => $refMerchantId,
         'c_datetime' => date('Y-m-d H:i:s'),
         'c_additionalFilter' => $additionalFilter,
         'c_type' => 'BI Fast',
      );

      $result = $this->db->insert('merchant_download', $data);
      if ($result) {
         $this->session->set_flashdata('success', 'Your request is beeing processed. Please go to Download Report menu to retrieve the file <a href="' . base_url('admin/report') . '">Download Report</a> menu to retrieve the file');
      } else {
         $this->session->set_flashdata('error', 'Failed request download');
      }

      redirect('admin/bi_fast');
   }
   public function Va_dynamic()
   {
      if (!$this->session->userdata('c_email')) {
         redirect('auth');
      }
      $this->load->model('Model_user');
      $this->load->model('VADynamic');


      $data['title'] = 'VA Dynamic';
      $data['saldo'] = $this->Model_user->saldo();
      $data['merchant'] = $this->Model_user->view_user()->row_array();
      if ($data['merchant']['c_openapiStatus'] != "Active") {
         redirect($_SERVER['HTTP_REFERER']);
         exit;
      }
      $refMerchantId = $data['merchant']['id'];

      $search_date_va = $this->input->post('search_date_va');
      $search_transid_va = $this->input->post('search_transid_va');
      $search_status_transaction_va = $this->input->post('search_status_transaction_va');

      if ($search_date_va) {
         $this->session->set_userdata('search_date_va', $search_date_va);
      } else {
         $search_date_va = $this->session->userdata('search_date_va');
      }
      if ($search_transid_va) {
         $this->session->set_userdata('search_transid_va', $search_transid_va);
      } else {
         $search_transid_va = $this->session->userdata('search_transid_va');
      }
      if ($search_status_transaction_va) {
         $this->session->set_userdata('search_status_transaction_va', $search_status_transaction_va);
      } else {
         $search_status_transaction_va = $this->session->userdata('search_status_transaction_va');
      }
      $this->db->from('cashin_dynamic_va');
      $this->db->where('cashin_dynamic_va.ref_merchantId', $refMerchantId);
      if (!empty($search_date_va)) {
         $this->db->like('cashin_dynamic_va.c_datetimeRequest', $search_date_va);
      }

      if (!empty($search_transid_va)) {
         $this->db->like('cashin_dynamic_va.c_merchantTransactionId', $search_transid_va);
      }

      if (!empty($search_status_transaction_va)) {
         $this->db->like('cashin_dynamic_va.c_status', $search_status_transaction_va);
      }

      $config['total_rows'] = $this->db->count_all_results();
      // var_dump($config['total_rows']);
      $config['base_url'] = base_url('admin/Va_dynamic');
      $config['per_page'] = 10;
      $config['uri_segment'] = 3;
      $config['full_tag_open'] = '<ul class="pagination justify-content-center">';
      $config['full_tag_close'] = '</ul>';

      $config['first_link'] = 'First';
      $config['last_link'] = 'Last';

      $config['first_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['first_tag_close'] = '</span></li>';

      $config['prev_link'] = '&laquo';
      $config['prev_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['prev_tag_close'] = '</span></li>';

      $config['next_link'] = '&raquo';
      $config['next_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['next_tag_close'] = '</span></li>';

      $config['last_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['last_tag_close'] = '</span></li>';

      $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
      $config['cur_tag_close'] = '</a></li>';

      $config['num_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['num_tag_close'] = '</span></li>';

      $this->pagination->initialize($config);

      $start = $this->uri->segment(3);
      $data['start'] = isset($start) ? $start : 0;

      $data['vadynamics'] = $this->VADynamic->get_vadynamic($refMerchantId, $config['per_page'], $data['start'], $search_date_va, $search_transid_va, $search_status_transaction_va);
      $data['pagination'] = $this->pagination->create_links();

      $this->load->view('templates/user_header.php', $data);
      $this->load->view('templates/user_sidebar.php', $data);
      $this->load->view('templates/user_topbar.php', $data);
      $this->load->view('virtualaccount/vadynamic', $data);
      $this->load->view('templates/user_footer.php', $data);
   }

   public function resetVa_dynamic()
   {

      $this->session->unset_userdata('search_date_va');
      $this->session->unset_userdata('search_transid_va');
      $this->session->unset_userdata('search_status_transaction_va');
      redirect('admin/Va_dynamic');
   }

   // public function download_va_dynamic() {

   //    $search_date = isset($_GET['search_date']) ? $_GET['search_date'] : '';

   //    if(empty($search_date)) {
   //       $this->session->set_flashdata('error_message', 'Please fill date and search before to continue download.');
   //       redirect('admin/Va_dynamic');
   //    }
   //    $data['merchant'] = $this->Model_user->view_user()->row_array();
   //    $refMerchantId = $data['merchant']['id'];

   //       $data = array(
   //          'ref_merchantId' => $refMerchantId,
   //          'c_datetime' => $search_date,
   //          'c_type' => 'va_dynamic',
   //          );

   //       $result = $this->db->insert('merchant_download', $data);
   //       if ($result) {

   //          $this->session->set_flashdata('success', 'Data berhasil di download');
   //    } else {

   //          $this->session->set_flashdata('error', 'Failed request download');
   //    }

   //    redirect('admin/Va_dynamic');
   // }

   public function VA_recurring()
   {
      $this->load->model('VARecurring');
      if (!$this->session->userdata('c_email')) {
         redirect('auth');
      }

      $data['title'] = 'VA Recurring';
      $data['merchant'] = $this->Model_user->view_user()->row_array();
      if ($data['merchant']['c_openapiStatus'] != "Active") {
         redirect($_SERVER['HTTP_REFERER']);
         exit;
      }
      $refMerchantId = $data['merchant']['id'];
      $data['saldo'] = $this->Model_user->saldo();

      $search_date = $this->input->post('search_date');

      if ($search_date) {
         $this->session->set_userdata('search_date', $search_date);
      } else {
         $search_date = $this->session->userdata('search_date');
      }

      $this->db->from('cashin_recurring_va');
      $this->db->where('cashin_recurring_va.ref_merchantId', $refMerchantId);
      if ($search_date) {
         $this->db->like('c_datetimeRequest', $search_date);
      }

      $config['base_url'] = base_url('admin/VA_recurring');
      $config['total_rows'] = $this->db->count_all_results();
      $config['per_page'] = 10;
      $config['uri_segment'] = 3;

      $config['full_tag_open'] = '<ul class="pagination justify-content-center">';
      $config['full_tag_close'] = '</ul>';

      $config['first_link'] = 'First';
      $config['last_link'] = 'Last';

      $config['first_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['first_tag_close'] = '</span></li>';

      $config['prev_link'] = '&laquo';
      $config['prev_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['prev_tag_close'] = '</span></li>';

      $config['next_link'] = '&raquo';
      $config['next_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['next_tag_close'] = '</span></li>';

      $config['last_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['last_tag_close'] = '</span></li>';

      $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
      $config['cur_tag_close'] = '</a></li>';

      $config['num_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['num_tag_close'] = '</span></li>';

      $this->pagination->initialize($config);

      $start = $this->uri->segment(3);
      $data['start'] = isset($start) ? $start : 0;

      $data['varecurrings'] = $this->VARecurring->get_varecurring($refMerchantId, $config['per_page'], $data['start'], $search_date);
      $data['pagination'] = $this->pagination->create_links();

      $this->load->view('templates/user_header.php', $data);
      $this->load->view('templates/user_sidebar.php', $data);
      $this->load->view('templates/user_topbar.php', $data);
      $this->load->view('virtualaccount/varecurring', $data);
      $this->load->view('templates/user_footer.php', $data);
   }

   public function resetVa_recurring()
   {

      $this->session->unset_userdata('search_date');
      redirect('admin/VA_recurring');
   }

   // public function download_va_recurring() {

   //    $search_date = isset($_GET['search_date']) ? $_GET['search_date'] : '';

   //    if(empty($search_date)) {
   //       $this->session->set_flashdata('error_message', 'Please fill date and search before to continue download.');
   //       redirect('admin/VA_recurring');
   //    }
   //    $data['merchant'] = $this->Model_user->view_user()->row_array();
   //    $refMerchantId = $data['merchant']['id'];

   //       $data = array(
   //          'ref_merchantId' => $refMerchantId,
   //          'c_datetime' => $search_date,
   //          'c_type' => 'va_recurring',
   //          );

   //       $result = $this->db->insert('merchant_download', $data);
   //       if ($result) {

   //          $this->session->set_flashdata('success', 'Data berhasil di download');
   //    } else {

   //          $this->session->set_flashdata('error', 'Failed request download');
   //    }

   //    redirect('admin/VA_recurring');
   // }

   public function qris_dynamic()
   {
      $this->load->model('QRISDynamic');
      if (!$this->session->userdata('c_email')) {
         redirect('auth');
      }

      $data['title'] = 'QRIS Dynamic';
      $data['merchant'] = $this->Model_user->view_user()->row_array();
      if ($data['merchant']['c_openapiStatus'] != "Active") {
         redirect($_SERVER['HTTP_REFERER']);
         exit;
      }
      $refMerchantId = $data['merchant']['id'];
      $data['saldo'] = $this->Model_user->saldo();

      $search_date_qd = $this->input->post('search_date_qd');
      $search_transid_qd = $this->input->post('search_transid_qd');
      $search_status_transaction_qd = $this->input->post('search_status_transaction_qd');

      // if (($search_date_qd === "" && $search_name_qd === "") || $search_transid === "") {
      //    $this->session->set_flashdata('error_message', 'Merchant atau Tanggal pencarian harus diisi');
      //    redirect('admin/qris_dynamic');
      // }

      if ($search_date_qd) {
         $this->session->set_userdata('search_date_qd', $search_date_qd);
      } else {
         $search_date_qd = $this->session->userdata('search_date_qd');
      }
      if ($search_transid_qd) {
         $this->session->set_userdata('search_transid_qd', $search_transid_qd);
      } else {
         $search_transid_qd = $this->session->userdata('search_transid_qd');
      }
      if ($search_status_transaction_qd) {
         $this->session->set_userdata('search_status_transaction_qd', $search_status_transaction_qd);
      } else {
         $search_status_transaction_qd = $this->session->userdata('search_status_transaction_qd');
      }

      $this->db->from('cashin_dynamic_qris_mpm');
      $this->db->where('cashin_dynamic_qris_mpm.ref_merchantId', $refMerchantId);
      if (!empty($search_date_qd)) {
         $this->db->like('cashin_dynamic_qris_mpm.c_datetimeRequest', $search_date_qd);
      }

      if (!empty($search_name_qd)) {
         $this->db->like('cashin_dynamic_qris_mpm.ref_merchantId', $search_name_qd);
      }

      if (!empty($search_transid_qd)) {
         $this->db->like('cashin_dynamic_qris_mpm.c_merchantTransactionId', $search_transid_qd);
      }

      if (!empty($search_status_transaction_qd)) {
         $this->db->like('cashin_dynamic_qris_mpm.c_status', $search_status_transaction_qd);
      }

      $config['base_url'] = base_url('admin/qris_dynamic');
      $config['total_rows'] = $this->db->count_all_results();
      $config['per_page'] = 10;
      $config['uri_segment'] = 3;

      $config['full_tag_open'] = '<ul class="pagination justify-content-center">';
      $config['full_tag_close'] = '</ul>';

      $config['first_link'] = 'First';
      $config['last_link'] = 'Last';

      $config['first_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['first_tag_close'] = '</span></li>';

      $config['prev_link'] = '&laquo';
      $config['prev_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['prev_tag_close'] = '</span></li>';

      $config['next_link'] = '&raquo';
      $config['next_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['next_tag_close'] = '</span></li>';

      $config['last_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['last_tag_close'] = '</span></li>';

      $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
      $config['cur_tag_close'] = '</a></li>';

      $config['num_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['num_tag_close'] = '</span></li>';

      $this->pagination->initialize($config);

      $start = $this->uri->segment(3);
      $data['start'] = isset($start) ? $start : 0;

      $data['stateProgram'] = $this->stateProgram;

      $data['qrisdynamics'] = $this->QRISDynamic->get_qrisdynamic($refMerchantId, $config['per_page'], $data['start'], $search_date_qd, $search_transid_qd, $search_status_transaction_qd);
      $data['pagination'] = $this->pagination->create_links();

      $this->load->view('templates/user_header.php', $data);
      $this->load->view('templates/user_sidebar.php', $data);
      $this->load->view('templates/user_topbar.php', $data);
      $this->load->view('qris/qrisdynamic', $data);
      $this->load->view('templates/user_footer.php', $data);
   }

   public function resetqris_dynamic()
   {

      $this->session->unset_userdata('search_date_qd');
      $this->session->unset_userdata('search_transid_qd');
      $this->session->unset_userdata('search_status_transaction_qd');

      redirect('admin/qris_dynamic');
   }

   // public function download_qris_dynamic() {

   //    $search_date = isset($_GET['search_date']) ? $_GET['search_date'] : '';

   //    if(empty($search_date)) {
   //       $this->session->set_flashdata('error_message', 'Please fill date and search before to continue download.');
   //       redirect('admin/qris_dynamic');
   //    }
   //    $data['merchant'] = $this->Model_user->view_user()->row_array();
   //    $refMerchantId = $data['merchant']['id'];

   //       $data = array(
   //          'ref_merchantId' => $refMerchantId,
   //          'c_datetime' => $search_date,
   //          'c_type' => 'qris_dynamic',
   //          );

   //       $result = $this->db->insert('merchant_download', $data);
   //       if ($result) {

   //          $this->session->set_flashdata('success', 'Data berhasil di download');
   //    } else {

   //          $this->session->set_flashdata('error', 'Failed request download');
   //    }

   //    redirect('admin/qris_dynamic');
   // }

   public function qris_recurring()
   {
      $this->load->model('Model_user');
      $this->load->model('QRISRecurring');
      if (!$this->session->userdata('c_email')) {
         redirect('auth');
      }

      $data['title'] = 'QRIS Recurring';
      $data['merchant'] = $this->Model_user->view_user()->row_array();
      if ($data['merchant']['c_openapiStatus'] != "Active") {
         redirect($_SERVER['HTTP_REFERER']);
         exit;
      }
      $refMerchantId = $data['merchant']['id'];
      $data['saldo'] = $this->Model_user->saldo();

      $search_date = $this->input->post('search_date');

      if ($search_date) {
         $this->session->set_userdata('search_date', $search_date);
      } else {
         $search_date = $this->session->userdata('search_date');
      }

      $this->db->from('cashin_recurring_qris_mpm');
      $this->db->where('cashin_recurring_qris_mpm.ref_merchantId', $refMerchantId);
      if ($search_date) {
         $this->db->like('c_datetimeRequest', $search_date);
      }

      $config['base_url'] = base_url('admin/qris_recurring');
      $config['total_rows'] = $this->db->count_all_results();
      $config['per_page'] = 10;
      $config['uri_segment'] = 3;

      $config['full_tag_open'] = '<ul class="pagination justify-content-center">';
      $config['full_tag_close'] = '</ul>';

      $config['first_link'] = 'First';
      $config['last_link'] = 'Last';

      $config['first_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['first_tag_close'] = '</span></li>';

      $config['prev_link'] = '&laquo';
      $config['prev_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['prev_tag_close'] = '</span></li>';

      $config['next_link'] = '&raquo';
      $config['next_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['next_tag_close'] = '</span></li>';

      $config['last_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['last_tag_close'] = '</span></li>';

      $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
      $config['cur_tag_close'] = '</a></li>';

      $config['num_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['num_tag_close'] = '</span></li>';
      $this->pagination->initialize($config);

      $start = $this->uri->segment(3);
      $data['start'] = isset($start) ? $start : 0;

      $data['qrisrecurrings'] = $this->QRISRecurring->get_qrisrecurring($refMerchantId, $config['per_page'], $data['start'], $search_date);
      $data['pagination'] = $this->pagination->create_links();

      $this->load->view('templates/user_header.php', $data);
      $this->load->view('templates/user_sidebar.php', $data);
      $this->load->view('templates/user_topbar.php', $data);
      $this->load->view('qris/qrisrecurring', $data);
      $this->load->view('templates/user_footer.php', $data);
   }

   public function resetqris_recurring()
   {

      $this->session->unset_userdata('search_date');
      redirect('admin/qris_recurring');
   }

   // public function download_qris_recurring() {

   //    $search_date = isset($_GET['search_date']) ? $_GET['search_date'] : '';
   //    if(empty($search_date)) {
   //       $this->session->set_flashdata('error_message', 'Please fill date and search before to continue download.');
   //       redirect('admin/qris_recurring');
   //    }
   //    $data['merchant'] = $this->Model_user->view_user()->row_array();
   //    $refMerchantId = $data['merchant']['id'];

   //       $data = array(
   //          'ref_merchantId' => $refMerchantId,
   //          'c_datetime' => $search_date,
   //          'c_type' => 'qris_recurring',
   //          );

   //       $result = $this->db->insert('merchant_download', $data);
   //       if ($result) {

   //          $this->session->set_flashdata('success', 'Data berhasil di download');
   //    } else {

   //          $this->session->set_flashdata('error', 'Failed request download');
   //    }

   //    redirect('admin/qris_recurring');
   // }

   public function report()
   {

      $this->load->model('MerchantDownload');
      if (!$this->session->userdata('c_email')) {
         redirect('auth');
      }

      $data['title'] = 'Report';
      $data['merchant'] = $this->Model_user->view_user()->row_array();
      $refMerchantId = $data['merchant']['id'];
      $data['saldo'] = $this->Model_user->saldo();
      $search_date = $this->input->post('search_date');

      if ($search_date) {
         $this->session->set_userdata('search_date', $search_date);
      } else {
         $search_date = $this->session->userdata('search_date');
      }

      $this->db->from('merchant_download');
      $this->db->where('merchant_download.ref_merchantId', $refMerchantId);
      if ($search_date) {
         $this->db->where('DATE(c_datetime)', $search_date);
      }

      $config['base_url'] = base_url('admin/report');
      $config['total_rows'] = $this->db->count_all_results();
      $config['per_page'] = 10;
      $config['uri_segment'] = 3;

      $config['full_tag_open'] = '<ul class="pagination justify-content-center">';
      $config['full_tag_close'] = '</ul>';

      $config['first_link'] = 'First';
      $config['last_link'] = 'Last';

      $config['first_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['first_tag_close'] = '</span></li>';

      $config['prev_link'] = '&laquo';
      $config['prev_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['prev_tag_close'] = '</span></li>';

      $config['next_link'] = '&raquo';
      $config['next_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['next_tag_close'] = '</span></li>';

      $config['last_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['last_tag_close'] = '</span></li>';

      $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
      $config['cur_tag_close'] = '</a></li>';

      $config['num_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['num_tag_close'] = '</span></li>';
      $this->pagination->initialize($config);

      $start = $this->uri->segment(3);
      $data['start'] = isset($start) ? $start : 0;

      $data['downloads'] = $this->MerchantDownload->get_download($refMerchantId, $config['per_page'], $data['start'], $search_date);
      $data['pagination'] = $this->pagination->create_links();

      $this->load->view('templates/user_header.php', $data);
      $this->load->view('templates/user_sidebar.php', $data);
      $this->load->view('templates/user_topbar.php', $data);
      $this->load->view('report/index', $data);
      $this->load->view('templates/user_footer.php', $data);
   }
   public function resetdownload()
   {

      $this->session->unset_userdata('search_date');
      redirect('admin/report');
   }
   public function download()
   {
      $filename = $this->input->get('filename');

      if (!empty($filename)) {

         $filepath = '/var/www/download_report/' . $filename;

         if (file_exists($filepath)) {

            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($filepath) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filepath));

            readfile($filepath);

            exit;
         } else {

            echo 'File not found.';
         }
      } else {

         echo 'Filename parameter is missing.';
      }
   }
   public function submerchant()
   {
      $this->load->model('SubMerchant');

      if (!$this->session->userdata('c_email')) {
         redirect('auth');
      }
      $data['title'] = 'Sub Merchant';
      $data['merchant'] = $this->Model_user->view_user()->row_array();
      $refMerchantId = $data['merchant']['id'];
      $data['saldo'] = $this->Model_user->saldo();

      $search_name_submerchant = $this->input->post('search_name_submerchant');

      if ($search_name_submerchant === "") {
         $data['alert_message'] = "Submerchant pencarian harus diisi.";
      }

      if ($search_name_submerchant) {
         $this->session->set_userdata('search_name_submerchant', $search_name_submerchant);
      } else {
         $search_name_submerchant = $this->session->userdata('search_name_submerchant');
      }

      $this->db->from('submerchant');
      $this->db->where('submerchant.ref_merchantId', $refMerchantId);

      if ($search_name_submerchant) {
         $this->db->like('c_name', $search_name_submerchant);
      }

      $config['total_rows'] = $this->db->count_all_results();
      // var_dump($config['total_rows']);
      $config['base_url'] = base_url('admin/submerchant');
      $config['per_page'] = 10;
      $config['uri_segment'] = 3;

      $config['full_tag_open'] = '<ul class="pagination justify-content-center">';
      $config['full_tag_close'] = '</ul>';

      $config['first_link'] = 'First';
      $config['last_link'] = 'Last';

      $config['first_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['first_tag_close'] = '</span></li>';

      $config['prev_link'] = '&laquo';
      $config['prev_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['prev_tag_close'] = '</span></li>';

      $config['next_link'] = '&raquo';
      $config['next_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['next_tag_close'] = '</span></li>';

      $config['last_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['last_tag_close'] = '</span></li>';

      $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
      $config['cur_tag_close'] = '</a></li>';

      $config['num_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['num_tag_close'] = '</span></li>';
      $this->pagination->initialize($config);

      $start = $this->uri->segment(3);
      $data['start'] = isset($start) ? $start : 0;

      $data['submerchants'] = $this->SubMerchant->get_submerchant($refMerchantId, $config['per_page'], $data['start'], $search_name_submerchant);
      $data['pagination'] = $this->pagination->create_links();
      // var_dump($data['pagination']);

      $this->load->view('templates/user_header.php', $data);
      $this->load->view('templates/user_sidebar.php', $data);
      $this->load->view('templates/user_topbar.php', $data);
      $this->load->view('submerchant/index', $data);
      $this->load->view('templates/user_footer.php', $data);
   }

   public function resetsubmerchant()
   {

      $this->session->unset_userdata('search_name_submerchant');
      redirect('admin/submerchant');
   }
   public function autocomplete()
   {
      if ($this->input->is_ajax_request()) {
         $prefix = $this->input->post('prefix');
         $provider = $this->get_provider_by_prefix($prefix);
         echo json_encode($provider);
      }
   }

   private function get_provider_by_prefix($prefix)
   {
      $provider = '';
      switch ($prefix) {
         case '0896':
         case '0897':
         case '0898':
         case '0899':
            $provider = 'pulsa_tri';
            break;
         case '0813':
         case '0812':
         case '0821':
         case '0811':
         case '0851':
         case '0852':
            $provider = 'pulsa_telkomsel';
            break;
         case '0817':
         case '0818':
         case '0819':
         case '0859':
            $provider = 'pulsa_xl';
            break;
         case '0838':
         case '0831':
            $provider = 'pulsa_axis';
            break;
         default:
            break;
      }
      return $this->Cash_out_model->get_pulsa_data($provider);
   }

   public function show_pulsaelektrik_page()
   {
      $data['autocomplete_data'] = $this->autocomplete();
      // var_dump($data['autocomplete_data']);
      $this->load->view('templates/user_header.php', $data);
      $this->load->view('templates/user_sidebar.php', $data);
      $this->load->view('templates/user_topbar.php', $data);
      $this->load->view('produk/pulsaelektrik', $data);
      $this->load->view('templates/user_footer.php', $data);
   }

   public function autocompletepaket()
   {
      if ($this->input->is_ajax_request()) {
         $prefix = $this->input->post('prefix');
         $provider = $this->get_provider_by_prefipaket($prefix);
         echo json_encode($provider);
      }
   }

   private function get_provider_by_prefipaket($prefix)
   {
      $provider = '';
      switch ($prefix) {
         case '0896':
         case '0897':
         case '0898':
         case '0899':
            $provider = 'paket_data_tri';
            break;
         case '0813':
         case '0812':
         case '0821':
         case '0811':
         case '0851':
         case '0852':
            $provider = 'paket_data_telkomsel';
            break;
         case '0817':
         case '0818':
         case '0819':
         case '0859':
            $provider = 'paket_data_xl';
            break;
         case '0838':
         case '0831':
            $provider = 'paket_data_axis';
            break;
         default:
            break;
      }
      return $this->Cash_out_model->get_paket_data($provider);
   }

   public function show_pulsaelektrik_page_paket()
   {
      $data['autocomplete_data'] = $this->autocomplete();
      // var_dump($data['autocomplete_data']);
      $this->load->view('templates/user_header.php', $data);
      $this->load->view('templates/user_sidebar.php', $data);
      $this->load->view('templates/user_topbar.php', $data);
      $this->load->view('produk/paketdata', $data);
      $this->load->view('templates/user_footer.php', $data);
   }

   public function settlement()
   {

      $this->load->model('Model_user');
      if (!$this->session->userdata('c_email')) {
         redirect('auth');
      }

      $data['title'] = 'Settlement';
      $data['merchant'] = $this->Model_user->view_user()->row_array();
      if ($data['merchant']['c_openapiStatus'] != "Active") {
         redirect($_SERVER['HTTP_REFERER']);
         exit;
      }

      $data['saldo'] = $this->Model_user->saldo();

      $this->load->view('templates/user_header.php', $data);
      $this->load->view('templates/user_sidebar.php', $data);
      $this->load->view('templates/user_topbar.php', $data);
      $this->load->view('disbursement/index', $data);
      $this->load->view('templates/user_footer.php', $data);
   }
   public function CreateVirtualAccount()
   {

      $this->load->model('Model_user');
      if (!$this->session->userdata('c_email')) {
         redirect('auth');
      }

      $data['title'] = 'Virtual Account';
      $data['merchant'] = $this->Model_user->view_user()->row_array();
      // if ($data['merchant']['c_openapiStatus'] != "Active") {
      //    redirect($_SERVER['HTTP_REFERER']);
      //    exit;
      // }

      $data['saldo'] = $this->Model_user->saldo();

      $this->load->view('templates/user_header.php', $data);
      $this->load->view('templates/user_sidebar.php', $data);
      $this->load->view('templates/user_topbar.php', $data);
      $this->load->view('virtualaccount/index', $data);
      $this->load->view('templates/user_footer.php', $data);
   }

   public function createDepositVa()
   {
      $nominal = $this->input->post('nominal');
      $bank_tujuan = $this->input->post('bank_tujuan');
      $note = $this->input->post('note');
      $data['merchant'] = $this->Model_user->view_user()->row_array();
      $query = $this->db->query("SELECT * FROM submerchant WHERE ref_merchantId = ?", $data['merchant']['id']);
      $submerchant = $query->row();
      $datetime = date('Y-m-d H:i:s');
      $chanelgroup = 'va';
      $referenceNo = $this->generateReferenceNo('VADY', '0', 20);
      $datetimeExpired = date('Y-m-d H:i:s', strtotime('+4 hours', strtotime($datetime)));

      if (!empty($this->data['post']['datetimeExpired'])) {
         $datetimeExpired = $this->data['post']['datetimeExpired'];
      }

      $statusGenerate = 'Pending';
      $cashinExternalId = $this->db->query("SELECT * FROM cashin_channel WHERE id = ? AND c_channelGroup = 'va'", array($channel_tujuan));
      $cashinexternal = $cashinExternalId->result();
      $cashinexternal_ref = $cashinexternal[0]->c_cashinExternalId;

      $merchantTransactionId = mt_rand(1000000000000000, 9999999999999999);

      $dataInsert2 = array(
         'ref_merchantId' => $data['merchant']['id'],
         'ref_subMerchantId' => $submerchant->id,
         'c_datetimeRequest' => date('Y-m-d H:i:s'),
         'c_channelGroup' => $chanelgroup,
         'ref_cashinChannelId' => $bank_tujuan,
         'c_customNumber' => '',
         'c_displayName' => $note,
         'c_amount' => $nominal,
         'c_datetimeExpired' => $datetimeExpired,
         'c_referenceNo' => $referenceNo,
         'c_status' => $statusGenerate,
         'ref_cashinExternalId' => $cashinexternal_ref
      );

      $processInsert2 = $this->VirtualAccount->insertVaDynamic($dataInsert2);
      if ($processInsert2 == true) {
         $idRequest2 = $this->db->insert_id();
         //  $this->session->set_flashdata('message', 'Berhasil menambahkan virtual account.');
      } else {
         //  $this->session->set_flashdata('message', 'Gagal menambahkan virtual account.');
         redirect('admin/CreateVirtualAccount');
         return;
      }

      if ($cashinexternal_ref == 'ifp') {
         $externalRequestBody = array(
            'ref_cashinDynamicVaId' => $idRequest2,
            'merchantId' => $data['merchant']['id']
         );

         $externalUrlHit = $this->externalUrlHit . "/ifp/Va/createDynamic";

         $externalCurl = curl_init();
         curl_setopt_array($externalCurl, array(
            CURLOPT_URL => $externalUrlHit,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($externalRequestBody),
            CURLOPT_HTTPHEADER => array(
               'Content-Type: application/json'
            ),
         ));

         $externalResponseBody = curl_exec($externalCurl);
         curl_close($externalCurl);

         $externalResponseDecode = json_decode($externalResponseBody, true);

         //  $this->session->set_flashdata('api_response', $externalResponseDecode);

         if (!empty($externalResponseDecode['responseCode'])) {
            if ($externalResponseDecode['responseCode'] == 'SUCCESS') {
               $statusGenerate = 'Created';
               $vaNumber = $externalResponseDecode['responseDetail']['payment_details']['va_number'];

               $dataUpdate = array(
                  'c_status' => $statusGenerate,
                  'c_vaNumber' => $vaNumber,
                  'ref_cashinExternalLogVaIdCreate' => $externalResponseDecode['responseLogId']
               );

               $this->VirtualAccount->updateVaDynamic($dataUpdate, $idRequest2);
               $this->session->set_flashdata('success', 'Virtual account berhasil dibuat dan diperbarui. <a href="' . site_url('admin/ViewVirtualAccount/' . $idRequest2) . '">Lihat detail</a>');
            } else {
               $this->session->set_flashdata('success', 'Virtual account gagal dibuat.');
            }
         } else {
            $this->session->set_flashdata('success', 'Virtual account gagal mendapatkan response dari server eksternal.');
         }
      }

      redirect('admin/CreateVirtualAccount');
   }

   public function generateReferenceNo($prefix, $val, $val_len = 27)
   {
      return substr($prefix, 0, 6) . date("YmdHis") . $this->generateString(5, 3);
   }

   public function generateString($len = 25, $type = 1)
   {
      if ($type == 1) {
         $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
      } else if ($type == 2) {
         $characters = '9876543210';
      } else if ($type == 3) {
         $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
      } else {
         $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
      }

      $randomString = '';
      for ($i = 0; $i < $len; $i++) {
         $index = rand(0, strlen($characters) - 1);
         $randomString .= $characters[$index];
      }
      return $randomString;
   }
   public function ViewVirtualAccount($idRequest2)
   {

      // var_dump($idRequest2);
      $this->load->model('Model_user');
      if (!$this->session->userdata('c_email')) {
         redirect('auth');
      }

      $data['title'] = 'Detail Virtual Account';
      $data['merchant'] = $this->Model_user->view_user()->row_array();
      // if ($data['merchant']['c_openapiStatus'] != "Active") {
      //    redirect($_SERVER['HTTP_REFERER']);
      //    exit;
      // }

      $data['saldo'] = $this->Model_user->saldo();

      $data['detail_va'] = $this->VirtualAccount->get_detail_va($idRequest2);

      $this->load->view('templates/user_header.php', $data);
      $this->load->view('templates/user_sidebar.php', $data);
      $this->load->view('templates/user_topbar.php', $data);
      $this->load->view('virtualaccount/detail', $data);
      $this->load->view('templates/user_footer.php', $data);
   }



   public function merchantBankAccount()
   {
      $this->load->model('QRISDynamic');
      if (!$this->session->userdata('c_email')) {
         redirect('auth');
      }

      $data['title'] = 'Merchant Bank Account';
      $data['merchant'] = $this->Model_user->view_user()->row_array();
      if ($data['merchant']['c_openapiStatus'] != "Active") {
         redirect($_SERVER['HTTP_REFERER']);
         exit;
      }

      // Ambil error dan input data dari flashdata
      $data['form_error'] = $this->session->flashdata('form_error');
      $data['input_data'] = $this->session->flashdata('input_data');
      $refMerchantId = $data['merchant']['id'];
      $data['saldo'] = $this->Model_user->saldo();

      $search_date = $this->input->post('search_date');

      if ($search_date) {
         $this->session->set_userdata('search_date', $search_date);
      } else {
         $search_date = $this->session->userdata('search_date');
      }

      // $this->db->from('merchant_account_bank');
      // $this->db->where('ref_merchantId', $refMerchantId);
      // // if ($search_date) {
      // //    $this->db->like('c_datetimeRequest', $search_date);
      // // }

      // echo $this->db->last_query();
      // exit;

      $config['base_url'] = base_url('admin/qris_dynamic');
      $config['total_rows'] = $this->db->count_all_results();
      $config['per_page'] = 10;
      $config['uri_segment'] = 3;

      $config['full_tag_open'] = '<ul class="pagination justify-content-center">';
      $config['full_tag_close'] = '</ul>';

      $config['first_link'] = 'First';
      $config['last_link'] = 'Last';

      $config['first_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['first_tag_close'] = '</span></li>';

      $config['prev_link'] = '&laquo';
      $config['prev_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['prev_tag_close'] = '</span></li>';

      $config['next_link'] = '&raquo';
      $config['next_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['next_tag_close'] = '</span></li>';

      $config['last_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['last_tag_close'] = '</span></li>';

      $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
      $config['cur_tag_close'] = '</a></li>';

      $config['num_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['num_tag_close'] = '</span></li>';

      $this->pagination->initialize($config);

      $start = $this->uri->segment(3);
      $data['start'] = isset($start) ? $start : 0;

      $data['stateProgram'] = $this->stateProgram;

      $data['banks'] = $this->Model_user->getAllBank();

      $data['merchantBankAccounts'] = $this->Model_user->getMerchantBankAccount($refMerchantId, $config['per_page'], $data['start'], $search_date);
      $data['pagination'] = $this->pagination->create_links();

      $this->load->view('templates/user_header.php', $data);
      $this->load->view('templates/user_sidebar.php', $data);
      $this->load->view('templates/user_topbar.php', $data);
      $this->load->view('settlement/bankAccount', $data);
      $this->load->view('templates/user_footer.php', $data);
   }

   public function addBankAccount()
   {
      $error = 0;

      if (!$this->session->userdata('c_email')) {
         redirect('auth');
      }
      $data['merchant'] = $this->Model_user->view_user()->row_array();

      $merchantId = $data['merchant']['id'];

      $this->form_validation->set_rules('bank_tujuan', 'Beneficiary Bank', 'required');
      $this->form_validation->set_rules('accountNo', 'Account No', 'trim|required|integer');
      $this->form_validation->set_error_delimiters('', '');
      if ($this->form_validation->run() == FALSE) {
         // Simpan pesan error form validation ke session
         $form_error = array(
            'bank_tujuan' => form_error('bank_tujuan'),
            'accountNo' => form_error('accountNo'),
         );
         $this->session->set_flashdata('form_error', $form_error);
         $this->session->set_flashdata('input_data', $this->input->post()); // Simpan input sebelumnya

         redirect('admin/merchantBankAccount');
      } else {
         $externalRequestBody = array(
            'merchantId' => $merchantId,
            'ref_cashoutChannelId' => $this->input->post('bank_tujuan'),
            'beneficiaryAccountNo' => $this->input->post('accountNo'),
         );

         $internalUrlHit = $this->internalUrlHit . "/Merchant/registerAccountBank";

         $externalCurl = curl_init();
         curl_setopt_array($externalCurl, array(
            CURLOPT_URL => $internalUrlHit,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($externalRequestBody),
            CURLOPT_HTTPHEADER => array(
               'Content-Type: application/json'
            ),
         ));

         $externalResponseBody = curl_exec($externalCurl);
         curl_close($externalCurl);

         $externalResponseDecode = json_decode($externalResponseBody, true);

         if (!empty($externalResponseDecode['responseCode'])) {
            if ($externalResponseDecode['responseCode'] == 'SUCCESS') {
               $this->session->set_flashdata('success', 'Berhasil tambah data');
            } else {
               $this->session->set_flashdata('success', 'Gagal menambah data | No rekening invalid atau tidak ditemukan');
            }
         } else {
            $this->session->set_flashdata('success', 'External server error');
         }
         redirect('admin/merchantBankAccount');
      }
   }

   public function deleteBankAccount()
   {
      if (!$this->session->userdata('c_email')) {
         redirect('auth');
      }
      $data['merchant'] = $this->Model_user->view_user()->row_array();
      $refMerchantId = $data['merchant']['id'];
      $id = $this->input->post('id');

      if (!$id) {
         $this->session->set_flashdata('error_message', 'ID tidak valid.');
         redirect('admin/merchantBankAccount');
      }



      // Model untuk hapus data
      $this->load->model('Model_user'); // Sesuaikan dengan model Anda
      $result = $this->Model_user->deleteAccount($id, $refMerchantId);

      if ($result) {
         $this->session->set_flashdata('success', 'Rekening berhasil dihapus.');
      } else {
         $this->session->set_flashdata('error_message', 'Gagal menghapus rekening.');
      }

      redirect('admin/merchantBankAccount');
   }


   public function createSettlement()
   {

      $this->load->model('Model_user');
      if (!$this->session->userdata('c_email')) {
         redirect('auth');
      }

      $data['title'] = 'Merchant Bank Account';
      $data['merchant'] = $this->Model_user->view_user()->row_array();
      if ($data['merchant']['c_openapiStatus'] != "Active") {
         redirect($_SERVER['HTTP_REFERER']);
         exit;
      }

      // Ambil error dan input data dari flashdata
      $data['form_error'] = $this->session->flashdata('form_error');
      $data['input_data'] = $this->session->flashdata('input_data');
      $refMerchantId = $data['merchant']['id'];
      $data['saldo'] = $this->Model_user->saldo();

      $data['merchantBankAccounts'] = $this->Model_user->getMerchantActiveBankAccount($refMerchantId);

      $this->load->view('templates/user_header.php', $data);
      $this->load->view('templates/user_sidebar.php', $data);
      $this->load->view('templates/user_topbar.php', $data);
      $this->load->view('settlement/create', $data);
      $this->load->view('templates/user_footer.php', $data);
   }

   public function processSettlement()
   {
      $error = 0;

      if (!$this->session->userdata('c_email')) {
         redirect('auth');
      }
      $data['merchant'] = $this->Model_user->view_user()->row_array();

      $merchantId = $data['merchant']['id'];

      $this->form_validation->set_rules('accountNo', 'Account No', 'trim|required|integer');
      $this->form_validation->set_rules('nominal', 'Nominal', 'trim|required|numeric');
      $this->form_validation->set_error_delimiters('', '');
      if ($this->form_validation->run() == FALSE) {
         // Simpan pesan error form validation ke session
         $form_error = array(
            'nominal' => form_error('nominal'),
            'accountNo' => form_error('accountNo'),
         );
         $this->session->set_flashdata('form_error', $form_error);
         $this->session->set_flashdata('input_data', $this->input->post()); // Simpan input sebelumnya

         redirect('admin/merchantBankAccount');
      } else {
         $externalRequestBody = array(
            'merchantId' => $merchantId,
            'ref_accountBank' => $this->input->post('accountNo'),
            'methodFee' => 'Merchant',
            'amount' => $this->input->post('nominal'),
         );


         $internalUrlHit = $this->internalUrlHit . "/Merchant/transferToAccountBank";

         $externalCurl = curl_init();
         curl_setopt_array($externalCurl, array(
            CURLOPT_URL => $internalUrlHit,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($externalRequestBody),
            CURLOPT_HTTPHEADER => array(
               'Content-Type: application/json'
            ),
         ));

         $externalResponseBody = curl_exec($externalCurl);
         curl_close($externalCurl);

         $externalResponseDecode = json_decode($externalResponseBody, true);

         if (!empty($externalResponseDecode['responseCode'])) {
            if ($externalResponseDecode['responseCode'] == 'Success') {
               $this->session->set_flashdata('success', 'Settlement Success');

               redirect('admin/bi_fast_detail/' . $externalResponseDecode['responseDetail']['refLogID']);
               exit;
            } else {
               $this->session->set_flashdata('error', 'Failed');
            }
         } else {
            $this->session->set_flashdata('error', 'External server error');
         }
         redirect('admin/bi_fast');
      }
   }

   public function verifyOtp()
   {

      $this->load->model('Model_user');
      if (!$this->session->userdata('c_email')) {
         redirect('auth');
      }
      $data['merchant'] = $this->Model_user->view_user()->row_array();
      $refMerchantId = $data['merchant']['id'];
      $otpCode = $this->input->post('otpCode');
      $accountId = $this->input->post('accountId');

      // Validasi kode OTP
      if (empty($otpCode) || strlen($otpCode) !== 6) {
         $this->session->set_flashdata('error_message', 'Kode OTP tidak valid.');
         redirect('admin/merchantBankAccount'); // Ganti dengan route yang sesuai
      }

      // Contoh validasi OTP (logika bisa disesuaikan dengan kebutuhan)
      $isValidOtp = $this->Model_user->validateOtp($accountId, $otpCode, $refMerchantId);

      if ($isValidOtp) {
         $this->session->set_flashdata('success', 'Kode OTP berhasil diverifikasi.');
      } else {
         $this->session->set_flashdata('error_message', 'Kode OTP salah atau sudah kedaluwarsa.');
      }

      redirect('admin/merchantBankAccount'); // Ganti dengan route yang sesuai
   }


   public function ewallet()
   {

      $this->load->model('Ewallet');

      if (!$this->session->userdata('c_email')) {
         redirect('auth');
      }

      $data['title'] = 'Ewallet';
      $data['merchant'] = $this->Model_user->view_user()->row_array();

      if ($data['merchant']['c_openapiStatus'] != "Active") {
         redirect($_SERVER['HTTP_REFERER']);
         exit;
      }
      $data['saldo'] = $this->Model_user->saldo();
      $refMerchantId = $data['merchant']['id'];

      $search_date_ewallet = $this->input->post('search_date_ewallet');
      $search_date_ewallet_settlement = $this->input->post('search_date_ewallet_settlement');
      $search_invoice_no = $this->input->post('search_invoice_no');


      if ($search_date_ewallet) {
         $this->session->set_userdata('search_date_ewallet', $search_date_ewallet);
      } else {
         $search_date_ewallet = $this->session->userdata('search_date_ewallet');
      }

      if ($search_date_ewallet_settlement) {
         $this->session->set_userdata('search_date_ewallet_settlement', $search_date_ewallet_settlement);
      } else {
         $search_date_ewallet_settlement = $this->session->userdata('search_date_ewallet_settlement');
      }

      if ($search_invoice_no) {
         $this->session->set_userdata('search_invoice_no', $search_invoice_no);
      } else {
         $search_invoice_no = $this->session->userdata('search_invoice_no');
      }

      $this->db->from('cashin_payment_ewallet');
      $this->db->join('cashin', 'cashin.id = cashin_payment_ewallet.ref_cashinId');
      $this->db->where('cashin_payment_ewallet.ref_merchantId', $refMerchantId);
      if ($search_date_ewallet) {
         $this->db->like('cashin_payment_ewallet.c_datetime', $search_date_ewallet);
      }

      if ($search_date_ewallet_settlement) {
         $this->db->like('cashin_payment_ewallet.c_datetimeSettlement', $search_date_ewallet);
      }

      if ($search_name_ewallet) {
         $this->db->like('cashin_payment_ewallet.ref_merchantId', $search_name_ewallet);
      }

      if ($search_invoice_no) {
         $this->db->like('cashin.c_invoiceNo', $search_invoice_no);
      }

      $config['base_url'] = base_url('admin/ewallet');
      $config['total_rows'] = $this->db->count_all_results();

      $config['per_page'] = 10;
      $config['uri_segment'] = 3;

      $config['full_tag_open'] = '<ul class="pagination justify-content-center">';
      $config['full_tag_close'] = '</ul>';

      $config['first_link'] = 'First';
      $config['last_link'] = 'Last';

      $config['first_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['first_tag_close'] = '</span></li>';

      $config['prev_link'] = '&laquo';
      $config['prev_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['prev_tag_close'] = '</span></li>';

      $config['next_link'] = '&raquo';
      $config['next_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['next_tag_close'] = '</span></li>';

      $config['last_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['last_tag_close'] = '</span></li>';

      $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
      $config['cur_tag_close'] = '</a></li>';

      $config['num_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['num_tag_close'] = '</span></li>';

      $this->pagination->initialize($config);

      $start = $this->uri->segment(3);
      $data['start'] = isset($start) ? $start : 0;

      $data['ewallets'] = $this->Ewallet->get_ewallet($refMerchantId, $config['per_page'], $data['start'], $search_date_ewallet, $search_date_ewallet_settlement, $search_invoice_no);
      $data['pagination'] = $this->pagination->create_links();

      $this->load->view('templates/user_header.php', $data);
      $this->load->view('templates/user_sidebar.php', $data);
      $this->load->view('templates/user_topbar.php', $data);
      $this->load->view('ewallet/list', $data);
      $this->load->view('templates/user_footer.php', $data);
   }

   public function ewallet_detail()
   {
      $id = $this->uri->segment(3);
      $data['merchant'] = $this->Model_user->view_user()->row_array();
      $data['title'] = 'Detail Ewallet';
      $refMerchantId = $data['merchant']['id'];
      $data['saldo'] = $this->Model_user->saldo();

      $this->load->model('Ewallet');
      $data['ewallet_data'] = $this->Ewallet->ewallet_detail($refMerchantId, $id);

      $this->load->view('templates/user_header.php', $data);
      $this->load->view('templates/user_sidebar.php', $data);
      $this->load->view('templates/user_topbar.php', $data);
      $this->load->view('ewallet/detail_ewallet', $data);
      $this->load->view('templates/user_footer.php', $data);
   }

   public function resetewallet()
   {
      $this->session->unset_userdata('search_date_ewallet');
      $this->session->unset_userdata('search_date_ewallet_settlement');
      $this->session->unset_userdata('search_invoice_no');
      redirect('admin/ewallet');
   }

   public function download_ewallet()
   {

      $search_date_ewallet = isset($_GET['search_date_ewallet']) ? $_GET['search_date_ewallet'] : '';
      if (empty($search_date_ewallet)) {
         $this->session->set_flashdata('error_message', 'Please fill date and search before to continue download.');
         redirect('admin/ewallet');
      }

      $data['merchant'] = $this->Model_user->view_user()->row_array();
      $refMerchantId = $data['merchant']['id'];

      $additionalFilter = $search_date_ewallet . '|';

      $data = array(
         'ref_merchantId' => $refMerchantId,
         'c_datetime' => date('Y-m-d H:i:s'),
         'c_additionalFilter' => $additionalFilter,
         'c_type' => 'Ewallet',
      );

      $result = $this->db->insert('merchant_download', $data);
      if ($result) {
         $this->session->set_flashdata('success', 'Your request is beeing processed. Please go to Download Report menu to retrieve the file <a href="' . base_url('admin/report') . '">Download Report</a> menu to retrieve the file');
      } else {
         $this->session->set_flashdata('error', 'Failed request download');
      }

      redirect('admin/ewallet');
   }


   public function ewallet_dynamic()
   {
      $this->load->model('EwalletDynamic');
      if (!$this->session->userdata('c_email')) {
         redirect('auth');
      }

      $data['title'] = 'Ewallet Dynamic';
      $data['merchant'] = $this->Model_user->view_user()->row_array();
      if ($data['merchant']['c_openapiStatus'] != "Active") {
         redirect($_SERVER['HTTP_REFERER']);
         exit;
      }
      $refMerchantId = $data['merchant']['id'];
      $data['saldo'] = $this->Model_user->saldo();

      $search_date_ewallet = $this->input->post('search_date_ewallet');
      $search_transid_ewallet = $this->input->post('search_transid_ewallet');
      $search_status_transaction_ewallet = $this->input->post('search_status_transaction_ewallet');

      // if (($search_date_ewallet === "" && $search_name_ewallet === "") || $search_transid === "") {
      //    $this->session->set_flashdata('error_message', 'Merchant atau Tanggal pencarian harus diisi');
      //    redirect('admin/ewallet_dynamic');
      // }

      if ($search_date_ewallet) {
         $this->session->set_userdata('search_date_ewallet', $search_date_ewallet);
      } else {
         $search_date_ewallet = $this->session->userdata('search_date_ewallet');
      }
      if ($search_transid_ewallet) {
         $this->session->set_userdata('search_transid_ewallet', $search_transid_ewallet);
      } else {
         $search_transid_ewallet = $this->session->userdata('search_transid_ewallet');
      }
      if ($search_status_transaction_ewallet) {
         $this->session->set_userdata('search_status_transaction_ewallet', $search_status_transaction_ewallet);
      } else {
         $search_status_transaction_ewallet = $this->session->userdata('search_status_transaction_ewallet');
      }

      $this->db->from('cashin_dynamic_ewallet');
      $this->db->where('cashin_dynamic_ewallet.ref_merchantId', $refMerchantId);
      if (!empty($search_date_ewallet)) {
         $this->db->like('cashin_dynamic_ewallet.c_datetimeRequest', $search_date_ewallet);
      }

      if (!empty($search_transid_ewallet)) {
         $this->db->like('cashin_dynamic_ewallet.c_merchantTransactionId', $search_transid_ewallet);
      }

      if (!empty($search_status_transaction_ewallet)) {
         $this->db->like('cashin_dynamic_ewallet.c_status', $search_status_transaction_ewallet);
      }

      $config['base_url'] = base_url('admin/ewallet_dynamic');
      $config['total_rows'] = $this->db->count_all_results();
      $config['per_page'] = 10;
      $config['uri_segment'] = 3;

      $config['full_tag_open'] = '<ul class="pagination justify-content-center">';
      $config['full_tag_close'] = '</ul>';

      $config['first_link'] = 'First';
      $config['last_link'] = 'Last';

      $config['first_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['first_tag_close'] = '</span></li>';

      $config['prev_link'] = '&laquo';
      $config['prev_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['prev_tag_close'] = '</span></li>';

      $config['next_link'] = '&raquo';
      $config['next_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['next_tag_close'] = '</span></li>';

      $config['last_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['last_tag_close'] = '</span></li>';

      $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
      $config['cur_tag_close'] = '</a></li>';

      $config['num_tag_open'] = '<li class="page-item"><span class="page-link">';
      $config['num_tag_close'] = '</span></li>';

      $this->pagination->initialize($config);

      $start = $this->uri->segment(3);
      $data['start'] = isset($start) ? $start : 0;

      $data['stateProgram'] = $this->stateProgram;

      $data['ewalletdynamics'] = $this->EwalletDynamic->get_ewalletdynamic($refMerchantId, $config['per_page'], $data['start'], $search_date_ewallet, $search_transid_ewallet, $search_status_transaction_ewallet);
      $data['pagination'] = $this->pagination->create_links();

      $this->load->view('templates/user_header.php', $data);
      $this->load->view('templates/user_sidebar.php', $data);
      $this->load->view('templates/user_topbar.php', $data);
      $this->load->view('ewallet/ewalletdynamic', $data);
      $this->load->view('templates/user_footer.php', $data);
   }

   public function resetewallet_dynamic()
   {

      $this->session->unset_userdata('search_date_ewallet');
      $this->session->unset_userdata('search_transid_ewallet');
      $this->session->unset_userdata('search_status_transaction_ewallet');

      redirect('admin/ewallet_dynamic');
   }


   public function CreateEwallet()
   {

      $this->load->model('Model_user');
      if (!$this->session->userdata('c_email')) {
         redirect('auth');
      }

      $data['title'] = 'Ewallet';
      $data['merchant'] = $this->Model_user->view_user()->row_array();
      // if ($data['merchant']['c_openapiStatus'] != "Active") {
      //    redirect($_SERVER['HTTP_REFERER']);
      //    exit;
      // }

      $data['saldo'] = $this->Model_user->saldo();

      $this->load->view('templates/user_header.php', $data);
      $this->load->view('templates/user_sidebar.php', $data);
      $this->load->view('templates/user_topbar.php', $data);
      $this->load->view('ewallet/index', $data);
      $this->load->view('templates/user_footer.php', $data);
   }

   public function createDepositEwallet()
   {
      $nominal = $this->input->post('nominal');
      $channel_tujuan = $this->input->post('channel_tujuan');
      $phone_number = $this->input->post('phone_number');
      $data['merchant'] = $this->Model_user->view_user()->row_array();
      $query = $this->db->query("SELECT * FROM submerchant WHERE ref_merchantId = ?", $data['merchant']['id']);
      $submerchant = $query->row();
      $datetime = date('Y-m-d H:i:s');
      $chanelgroup = 'ewallet';
      $referenceNo = $this->generateReferenceNo('EWLDY', '0', 20);
      $datetimeExpired = date('Y-m-d H:i:s', strtotime('+1 hours', strtotime($datetime)));

      if (!empty($this->data['post']['datetimeExpired'])) {
         $datetimeExpired = $this->data['post']['datetimeExpired'];
      }

      $statusGenerate = 'Pending';
      $cashinExternalId = $this->db->query("SELECT * FROM cashin_channel WHERE id = ? AND c_channelGroup = 'ewallet'", array($channel_tujuan));
      $cashinexternal = $cashinExternalId->result();
      $cashinexternal_ref = $cashinexternal[0]->c_externalIdDefault;

      $merchantTransactionId = mt_rand(1000000000000000, 9999999999999999);

      $dataInsert2 = array(
         'ref_merchantId' => $data['merchant']['id'],
         'ref_subMerchantId' => $submerchant->id,
         'c_datetimeRequest' => date('Y-m-d H:i:s'),
         'c_channelGroup' => $chanelgroup,
         'ref_cashinChannelId' => $channel_tujuan,
         'c_phoneNumber' => $phone_number,
         'c_amount' => $nominal,
         'c_datetimeExpired' => $datetimeExpired,
         'c_referenceNo' => $referenceNo,
         'c_status' => $statusGenerate,
         'ref_cashinExternalId' => $cashinexternal_ref
      );

      // var_dump(json_encode($dataInsert2));

      $processInsert2 = $this->Ewallet->insertEwalletDynamic($dataInsert2);
      if ($processInsert2 == true) {
         $idRequest2 = $this->db->insert_id();
         //  $this->session->set_flashdata('message', 'Berhasil menambahkan ewallet.');
      } else {
         //  $this->session->set_flashdata('message', 'Gagal menambahkan ewallet.');
         redirect('admin/CreateEwallet');
         return;
      }

      if ($cashinexternal_ref == 'ifp') {

         $externalRequestBody = array(
            'ref_cashinDynamicEwalletId' => $idRequest2,
            'merchantId' => $data['merchant']['id']
         );

         $externalUrlHit = $this->externalUrlHit . "/ifp/Ewallet/createDynamic";

         $externalCurl = curl_init();
         curl_setopt_array($externalCurl, array(
            CURLOPT_URL => $externalUrlHit,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($externalRequestBody),
            CURLOPT_HTTPHEADER => array(
               'Content-Type: application/json'
            ),
         ));

         $externalResponseBody = curl_exec($externalCurl);
         curl_close($externalCurl);

         $externalResponseDecode = json_decode($externalResponseBody, true);

         //  $this->session->set_flashdata('api_response', $externalResponseDecode);

         if (!empty($externalResponseDecode['responseCode'])) {
            if ($externalResponseDecode['responseCode'] == 'SUCCESS') {

               $statusGenerate = 'Created';
               $ewalletLink       = $externalResponseDecode['responseDetail']['wallet_response']['redirect_url_http'];

               $dataUpdate = array(
                  'c_status' => $statusGenerate,
                  'c_ewalletLink' => $ewalletLink,
                  'ref_cashinExternalLogEwalletIdCreate' => $externalResponseDecode['responseLogId']
               );

               $this->Ewallet->updateEwalletDynamic($dataUpdate, $idRequest2);
               $this->session->set_flashdata('success', 'Ewallet berhasil dibuat dan diperbarui. <a href="' . site_url('admin/ViewEwallet/' . $idRequest2) . '">Lihat detail</a>');
            } else {
               $this->session->set_flashdata('success', 'Ewallet gagal dibuat.');
            }
         } else {
            $this->session->set_flashdata('success', 'Ewallet gagal mendapatkan response dari server eksternal.');
         }
      }

      redirect('admin/CreateEwallet');
   }


   public function ViewEwallet($idRequest2)
   {

      // var_dump($idRequest2);
      $this->load->model('Model_user');
      if (!$this->session->userdata('c_email')) {
         redirect('auth');
      }

      $data['title'] = 'Detail Ewallet';
      $data['merchant'] = $this->Model_user->view_user()->row_array();
      // if ($data['merchant']['c_openapiStatus'] != "Active") {
      //    redirect($_SERVER['HTTP_REFERER']);
      //    exit;
      // }

      $data['saldo'] = $this->Model_user->saldo();

      $data['detail_ewallet'] = $this->Ewallet->get_detail_ewallet($idRequest2);

      $this->load->view('templates/user_header.php', $data);
      $this->load->view('templates/user_sidebar.php', $data);
      $this->load->view('templates/user_topbar.php', $data);
      $this->load->view('ewallet/detail', $data);
      $this->load->view('templates/user_footer.php', $data);
   }
}
