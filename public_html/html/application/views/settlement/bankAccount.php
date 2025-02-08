<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="card shadow border-left-info">
        <div class="card-header">
            <h3>
                Merchant Bank Account
            </h3>
        </div>

        <div class="card-body">
            <div class="row">
                <?php if ($this->session->flashdata('success')) : ?>
                    <div class="alert alert-success">
                        <?php echo $this->session->flashdata('success'); ?>
                    </div>
                <?php endif; ?>

                <?php
                $search_date_qd_value = $this->session->userdata('search_date_qd') ?? '';
                $search_name_qd_value = $this->session->userdata('search_name_qd') ?? '';
                $search_submerchant_qd_value = $this->session->userdata('search_submerchant_qd') ?? '';

                $error_message = '';
                if (isset($_SESSION['error_message'])) {
                    $error_message = $_SESSION['error_message'];
                    unset($_SESSION['error_message']);
                }
                ?>

                <?php if ($error_message): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>
                <div class="col-lg-12">
                    <div class="card shadow border-left-info">
                        <div class="card-body">
                            <form method="post" action="<?php echo base_url('admin/addBankAccount'); ?>">
                                <div class="col-md-12">


                                    <div class="form-group">
                                        <label for="bank_tujuan">Beneficiary Bank</label>
                                        <select name="bank_tujuan" id="bank_tujuan" style="width: 500px;" class="form-control">
                                            <option value="" selected="" disabled="">Select Bank</option>
                                            <?php foreach ($banks as $bank): ?>
                                                <option value="<?= $bank->id; ?>"><?= $bank->c_description;  ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <?php if (isset($form_error['bank_tujuan'])): ?>
                                            <small class="text-danger"><?= $form_error['bank_tujuan']; ?></small>
                                        <?php endif; ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="accountNo">Account No</label>
                                        <input type="number" class="form-control" required="" id="accountNo" name="accountNo" style="width: 500px;">
                                        <?php if (isset($form_error['accountNo'])): ?>
                                            <small class="text-danger"><?= $form_error['accountNo']; ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- <?php
                echo "<pre>";
                print_r($merchantBankAccounts);
                echo "</pre>";
                ?>  -->

        <div class="card-body">
            <table class="table table-hover" style="margin-top:20px;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Date Time Request</th>
                        <th>Bank</th>
                        <th>Account No</th>
                        <th>Account Name</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($merchantBankAccounts)): ?>
                        <tr>
                            <td colspan="6" align="center">No data in display</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($merchantBankAccounts as $account): ?>
                            <tr>
                                <td><?php echo ++$start ?></td>
                                <td><?php echo $account->c_createdAt; ?></td>
                                <td><?php echo $account->ref_cashoutChannelId; ?></td>
                                <td><?php echo $account->c_beneficiaryAccountNo; ?></td>
                                <td><?php echo $account->c_beneficiaryAccountName; ?></td>
                                <td><?php echo $account->c_status; ?></td>
                                <td>
                                    <?php if($account->c_status != "Active"){ ?>
                                    <a href="#" class="btn btn-primary btn-sm btn-otp" data-toggle="modal" data-target="#modal-otp" data-id="<?php echo $account->id; ?>">
                                        <span class="fa fa-pencil"></span> Masukan Kode OTP
                                    </a>
                                    <?php } ?>
                                    <a href="#" data-toggle="modal" id="deleteRekening" class="btn btn-danger btn-sm" data-target="#modal-confirm" data-id="<?php echo $account->id; ?>" data-bank="<?php echo $account->c_beneficiaryAccountName; ?>" data-no="<?php echo $account->c_beneficiaryAccountNo; ?>"><span class="fa fa-trash"></span> Hapus</a>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>

            <div class="d-flex justify-content-center mt-4" style="font-size: 14px;">
                <div class="pagination-links">
                    <?php echo $pagination; ?>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div class="modal fade" id="modal-confirm" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="<?php echo base_url('admin/deleteBankAccount'); ?>" method="post">
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus rekening berikut?</p>
                        <p><strong>Nama Rekening:</strong> <span id="confirm-account-name"></span></p>
                        <p><strong>No Rekening:</strong> <span id="confirm-account-no"></span></p>
                        <input type="hidden" id="confirm-id" name="id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Masukkan Kode OTP -->
    <div class="modal fade" id="modal-otp" tabindex="-1" role="dialog" aria-labelledby="otpModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="otpModalLabel">Masukkan Kode OTP</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="otp-form" method="post" action="<?php echo base_url('admin/verifyOtp'); ?>">
                    <div class="modal-body">
                        <div class="form-group text-center">
                            <label for="otpCode" class="font-weight-bold">Kode OTP</label>
                            <input type="text"
                                class="form-control w-50 mx-auto text-center"
                                id="otpCode"
                                name="otpCode"
                                maxlength="6"
                                required
                                placeholder="Masukkan Kode OTP">
                            <input type="hidden" id="otpAccountId" name="accountId">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Kirim</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



</div>
<!-- /.container-fluid -->

</div>


<!-- End of Main Content -->