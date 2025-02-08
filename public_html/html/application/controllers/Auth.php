<?php defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function index()
    {
        if ($this->session->userdata('c_email')) {
            redirect('user');
        }

        $this->form_validation->set_rules('c_email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Login Page';
            $this->load->view('templates/auth_header.php', $data);
            $this->load->view('auth/login');
            $this->load->view('templates/auth_footer.php');
        } else {
            $this->_login();
        }
    }

    private function _login()
    {
        $this->load->helper('recaptcha');
        $c_email = $this->input->post('c_email');
        $merchantPassword = $this->input->post('password');

        // Validasi reCAPTCHA
        $recaptchaResponse = $this->input->post('g-recaptcha-response');

        if (empty($recaptchaResponse)) {
            $data['title'] = 'Login Page';
            $data['error_message'] = 'Please complete the reCAPTCHA verification!';
            $this->load->view('templates/auth_header.php', $data);
            $this->load->view('auth/login', $data);
            $this->load->view('templates/auth_footer.php');
            return; 
        }

        $recaptchaSecret = '6LeFmMgpAAAAADbaOgic1poY12yAHrhTjBDrteav';

        $response = verify_recaptcha($recaptchaResponse, $recaptchaSecret);
        if (!$response['success']) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">reCAPTCHA validation failed!</div>');
            $data['title'] = 'Login Page';
            $this->load->view('templates/auth_header.php', $data);
            $this->load->view('auth/login', $data);
            $this->load->view('templates/auth_footer.php');
            return; // Stop further execution
        }

        $merchant = $this->db->get_where('merchant', ['c_email' => $c_email])->row_array();

        // merchantnya ada
        if ($merchant) {
            // jika merchant aktif
            if ($merchant['c_status'] == 'Active') {
                if (password_verify($merchantPassword, $merchant['c_password'])) {
                    $data = [
                        'c_email' => $merchant['c_email'],
                    ];

                    $this->session->set_userdata($data);

                    // Redirect ke halaman admin setelah login
                    redirect('admin');
                } else {
                    // jika password salah
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Wrong password!</div>');
                    redirect('auth');
                }
            } else {
                // jika user belum aktivasi
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">This email has not been activated!</div>');
                redirect('auth');
            }
            // jika user tidak ada
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">This email is not registered!</div>');
            redirect('auth');
        }

    }

    public function register()
    {
        // Load Google reCAPTCHA helper
        $this->load->helper('recaptcha');

        if ($this->session->userdata('c_email')) {
            redirect('merchant');
        }
        
        // Validasi untuk register
        $this->form_validation->set_rules('c_name', 'Full Name', 'trim|required');
        $this->form_validation->set_rules('c_email', 'Email', 'trim|required|valid_email|is_unique[merchant.c_email]', [
            'is_unique' => 'This email has already been registered!',
        ]);
        $this->form_validation->set_rules('c_password', 'Password', 'trim|required|min_length[4]|matches[password2]', [
            'matches' => 'Passwords do not match!',
            'min_length' => 'Password is too short!'
        ]);
        $this->form_validation->set_rules('password2', 'Repeat Password', 'trim|required|matches[c_password]');

        // Validasi reCAPTCHA
        $recaptchaResponse = $this->input->post('g-recaptcha-response');

        if (empty($recaptchaResponse)) {
            $data['title'] = 'Registration';
            $data['error_message'] = 'Please complete the reCAPTCHA verification!';
            $this->load->view('templates/auth_header.php', $data);
            $this->load->view('auth/register', $data);
            $this->load->view('templates/auth_footer.php');
            return; 
        }

        $recaptchaSecret = '6LeFmMgpAAAAADbaOgic1poY12yAHrhTjBDrteav';

        $response = verify_recaptcha($recaptchaResponse, $recaptchaSecret);
        if (!$response['success']) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">reCAPTCHA validation failed!</div>');
            $data['title'] = 'Registration';
            $this->load->view('templates/auth_header.php', $data);
            $this->load->view('auth', $data);
            $this->load->view('templates/auth_footer.php');
            return; // Stop further execution
        }

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Registration';
            $this->load->view('templates/auth_header.php', $data);
            $this->load->view('auth/register');
            $this->load->view('templates/auth_footer.php');
        } else {
            // Ketika berhasil, akan mengirimkan data ke database
            $data = [
                'c_name' => htmlspecialchars($this->input->post('c_name')),
                'c_email' => htmlspecialchars($this->input->post('c_email')),
                'c_phoneNumber' => htmlspecialchars($this->input->post('c_phoneNumber')),
                'c_password' => password_hash($this->input->post('c_password'), PASSWORD_DEFAULT),
                'c_status' => 'Pending', 
                'c_dateCreated' => date('Y-m-d H:i:s')
            ];

            $token = base64_encode(random_bytes(32));
            $user_token = [
                'email' => $this->input->post('c_email'),
                'token' => $token,
                'date_created' => time()
            ];

            // Insert data ke database
            $this->db->insert('merchant', $data);
            $this->db->insert('merchant_token', $user_token);

            $this->_sendEmail($token, 'verify');
        
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Congratulations, your account has been created. Please activate your account.</div>');
            redirect('auth');
        }                
    }

    private function _sendEmail($token, $type)
    {
        $this->load->model('Model_user');

        $config = [
            'protocol'          => 'smtp',
            'smtp_host'         => 'ssl://mail.gidi.co.id',
            'smtp_user'         => 'noreply@gidi.co.id',
            'smtp_pass'         => 'baeGFKP.B$3M',
            'smtp_port'         => 465,
            'mailtype'          => 'html',
            'charset'           => 'utf-8',
            'newline'           => "\r\n",
            'wordwrap'          => TRUE,
            'wrapchars'         => 1000,
            'validate'          => false,
            'priority'          => 3,
            'crlf'              => "\r\n",
            'bcc_batch_mode'    => FALSE,
            'bcc_batch_size'    => 200
        ];

        $this->email->initialize($config);

        $this->email->from('noreply@gidi.co.id', 'Admin GIDI');
        $this->email->to($this->input->post('c_email'));

        $email = $this->input->post('c_email');
        // $data = $this->Model_user->view_user()->row_array();

        // Membuat URL verifikasi
        $verification_url = base_url('/auth/verify?email=' . $email . '&token=' . urlencode($token));

        if ($type == 'verify') {
            $this->email->subject('Account Verification');

            // Wording HTML untuk pesan email
            $message = '
                <!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <meta http-equiv="X-UA-Compatible" content="IE=edge">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Verifikasi Email</title>
                </head>
                <body>
                    <div class="container">
                        <div class="row">
                            <div class="col-md-4 offset-md-4">
                                <p>Halo User,</p>
                                <p>Terima kasih telah mendaftar di Situs kami. Untuk menyelesaikan proses pendaftaran Anda, kami perlu memverifikasi alamat email Anda.</p>
                                <p>Silakan klik di bawah ini untuk memverifikasi akun Anda:</p>
                                <p class="text-center">
                                    <a href="' . $verification_url . '" style="display: inline-block; background-color: #007bff; color: #ffffff; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Activate</a>
                                </p>
                                <p>Jika Anda tidak dapat mengklik tautan di atas, Anda dapat menyalin dan menempelkan URL berikut ke dalam browser web Anda:</p>
                                <p>' . $verification_url . '</p>
                                <p>Jika Anda tidak merasa mendaftar di [Nama Aplikasi/Situs], Anda mungkin mengabaikan pesan ini.</p>
                                <p>Terima kasih,</p>
                                <p>Tim Gidi</p>
                            </div>
                        </div>
                    </div>
                </body>
                </html>
            ';

            $this->email->message($message);
        } else if ($type == 'forgot') {
            $this->email->subject('Reset Password');

            // Pesan email untuk reset password
            $reset_link = base_url() . 'auth/resetpassword?email=' . $email . '&token=' . urlencode($token);
            $message = '
                <!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <meta http-equiv="X-UA-Compatible" content="IE=edge">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Reset Password</title>
                </head>
                <body>
                    <div class="container">
                        <div class="row">
                            <div class="col-md-4 offset-md-4">
                                <p>Halo User,</p>
                                <p>Kami mendapat permintaan untuk mereset kata sandi akun Anda. Untuk melanjutkan proses reset, silakan klik tautan di bawah ini:</p>
                                <p class="text-center">
                                    <a href="' . $reset_link . '" style="display: inline-block; background-color: #007bff; color: #ffffff; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Reset Password</a>
                                </p>
                                <p>Jika Anda tidak meminta reset kata sandi, Anda bisa mengabaikan pesan ini. Namun, untuk keamanan akun Anda, disarankan untuk mengganti kata sandi Anda secara berkala.</p>
                                
                                <p>Tim Gidi</p>
                            </div>
                        </div>
                    </div>
                </body>
                </html>
            ';

            $this->email->message($message);
        }

        if ($this->email->send()) {
            return true;
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Send email failed</div>');
            redirect('auth');
        }
    }

    public function verify()
    {
        $email = $this->input->get('email');
        $token = $this->input->get('token');

        $user = $this->db->get_where('merchant', ['c_email' => $email])->row_array();

        if ($user) {
            $user_token = $this->db->get_where('merchant_token', ['token' => $token])->row_array();

            if ($user_token) {
                if (time() - $user_token['date_created'] < (60 * 60 * 24)) {
                    $this->db->set('c_status', 'Active');
                    $this->db->where('c_email', $email);
                    $this->db->update('merchant');

                    $this->db->delete('merchant_token', ['email' => $email]);

                    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">' . $email . ' has been activated! Please login.</div>');
                    redirect('auth');
                } else {
                    $this->db->delete('merchant', ['c_email' => $email]);
                    $this->db->delete('merchant_token', ['email' => $email]);

                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Account activation failed! Token expired.</div>');
                    redirect('auth');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Account activation failed! Wrong token.</div>');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Account activation failed! Wrong email.</div>');
            redirect('auth');
        }
    }

    public function logout()
    {
        $this->session->unset_userdata('c_email');
        $this->session->unset_userdata('role_id');

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">You have been logout</div>');
        redirect('auth');
    }

    public function blocked()
    {
        $this->load->view('auth/blocked');
    }

    public function forgotPassword()
    {
        $this->load->helper('recaptcha');
        $this->form_validation->set_rules('c_email', 'Email', 'trim|required|valid_email');

        $recaptchaResponse = $this->input->post('g-recaptcha-response');

        if (empty($recaptchaResponse)) {
            $data['title'] = 'Forgot Password';
            $data['error_message'] = 'Please complete the reCAPTCHA verification!';
            $this->load->view('templates/auth_header.php', $data);
            $this->load->view('auth/forgotPassword', $data);
            $this->load->view('templates/auth_footer.php');
            return; 
        }

        $recaptchaSecret = '6LeFmMgpAAAAADbaOgic1poY12yAHrhTjBDrteav';

        $response = verify_recaptcha($recaptchaResponse, $recaptchaSecret);
        if (!$response['success']) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">reCAPTCHA validation failed!</div>');
            $data['title'] = 'Forgot Password';
            $this->load->view('templates/auth_header.php', $data);
            $this->load->view('forgotPassword', $data);
            $this->load->view('templates/auth_footer.php');
            return; // Stop further execution
        }

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Forgot Password';

            $this->load->view('templates/auth_header.php', $data);
            $this->load->view('auth/forgotPassword');
            $this->load->view('templates/auth_footer.php');
        } else {
            $email = $this->input->post('c_email');
            // var_dump($email);
            $user = $this->db->get_where('merchant', ['c_email' => $email, 'c_status' => 'Active'])->row_array();

            if ($user) {
                $token = base64_encode(random_bytes(32));
                $user_token = [
                    'email' => $email,
                    'token' => $token,
                    'date_created' => time()
                ];

                $this->db->insert('merchant_token', $user_token);
                $this->_sendEmail($token, 'forgot');

                $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Please check your email to reset your password!</div>');
                redirect('auth/forgotpassword');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Email is not registered or activated!</div>');
                redirect('auth/forgotpassword');
            }
        }
    }

    public function resetPassword()
    {
        $email = $this->input->get('email');
        $token = $this->input->get('token');

        $user = $this->db->get_where('merchant', ['c_email' => $email])->row_array();

        if ($user) {
            $user_token = $this->db->get_where('merchant_token', ['token' => $token])->row_array();

            if ($user_token) {
                $this->session->set_userdata('reset_email', $email);
                $this->changePassword();
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Reset password failed! Wrong token.</div>');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Reset password failed! Wrong email.</div>');
            redirect('auth');
        }
    }

    public function changePassword()
    {
        if (!$this->session->userdata('reset_email')) {
            redirect('auth');
        }

        $this->form_validation->set_rules('password1', 'Password', 'trim|required|min_length[3]|matches[password2]');
        $this->form_validation->set_rules('password2', 'Repeat Password', 'trim|required|min_length[3]|matches[password1]');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Change Password';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/change-password');
            $this->load->view('templates/auth_footer');
        } else {
            $password = password_hash($this->input->post('password1'), PASSWORD_DEFAULT);
            $email = $this->session->userdata('reset_email');

            $this->db->set('c_password', $password);
            $this->db->where('c_email', $email);
            $this->db->update('merchant');

            $this->session->unset_userdata('reset_email');

            $this->db->delete('merchant_token', ['email' => $email]);

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Password has been changed! Please login.</div>');
            redirect('auth');
        }
    }
}
