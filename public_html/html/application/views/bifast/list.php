<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="card shadow border-left-info">
        <div class="card-header">
            <h3>Disbursement</h3>
        </div>

        <div class="card-body">
            <div class="row">
                <?php if ($this->session->flashdata('success')) : ?>
                    <div class="alert alert-success">
                        <?php echo $this->session->flashdata('success'); ?>
                    </div>
                <?php endif; ?>

                <?php
                    // session_start();

                    $search_date_bifast_value1 = $this->session->userdata('search_date_bifast1') ?? '';
                    $search_date_bifast_value2 = $this->session->userdata('search_date_bifast2') ?? '';

                    $search_transid_bifast_value = $this->session->userdata('search_transid_bifast') ?? '';
                    $search_status_transaction_bifast_value = $this->session->userdata('search_status_transaction_bifast') ?? '';

                    $error_message = '';
                    if (isset($_SESSION['error_message'])) {
                        $error_message = $_SESSION['error_message'];
                        unset($_SESSION['error_message']);
                    }
                ?>

                <?php if($error_message): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>
                
                <div class="col-12">
                    <form id="mutation_form" method="post" action="<?php echo base_url('admin/bi_fast'); ?>">
                        <div class="row g-3">

                        <div class="col-md-3">
                                <label for="search_date_bifast1">Date Request From:</label>
                                <input type="date" id="search_date_bifast1" name="search_date_bifast1" class="form-control" value="<?php echo $search_date_bifast_value1; ?>" >
                            </div>

                            <div class="col-md-3">
                                <label for="search_date_bifast2">Date Request To:</label>
                                <input type="date" id="search_date_bifast2" name="search_date_bifast2" class="form-control" value="<?php echo $search_date_bifast_value2; ?>" >
                            </div>

                            <div class="col-md-3">
                                <label for="search_transid_bifast">Merchant Transaction Id:</label>
                                <input type="text" id="search_transid_bifast" name="search_transid_bifast" class="form-control" value="<?php echo $search_transid_bifast_value; ?>" >
                            </div>

                            <div class="col-md-3">
                                <label for="search_status_transaction_bifast">Status Transaction:</label>
                                <select id="search_status_transaction_bifast" name="search_status_transaction_bifast" class="form-control">
                                    <option value="">Pilih Salah Satu</option>
                                    <option value="Pending" <?php echo ($search_status_transaction_bifast_value == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                    <option value="Process" <?php echo ($search_status_transaction_bifast_value == 'Process') ? 'selected' : ''; ?>>Process</option>
                                    <option value="Success" <?php echo ($search_status_transaction_bifast_value == 'Success') ? 'selected' : ''; ?>>Success</option>
                                    <option value="Failed" <?php echo ($search_status_transaction_bifast_value == 'Failed') ? 'selected' : ''; ?>>Failed</option>
                                    <option value="Init" <?php echo ($search_status_transaction_bifast_value == 'Init') ? 'selected' : ''; ?>>Init</option>
                                    <option value="Timeout" <?php echo ($search_status_transaction_bifast_value == 'Timeout') ? 'selected' : ''; ?>>Timeout</option>
                                </select>
                            </div>

                            <!-- Buttons -->
                            <div class="col-12 text-start">
                                <button type="submit" name="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Search
                                </button>
                                <a href="<?php echo base_url('admin/resetbi_fast'); ?>" class="btn btn-warning">
                                    <i class="fas fa-undo"></i> Reset
                                </a>
                                <?php
                                    $download_url = base_url('admin/download_bi_fast') . "?search_date_bifast1=" . $search_date_bifast_value1 . "&search_date_bifast2=" . $search_date_bifast_value2;
                                ?>
                                <a href="<?php echo $download_url; ?>"  name="download" class="btn btn-danger"><i class="fas fa-download"></i> Download</a>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>

        <!-- <?php
           
           echo "<pre>";
           print_r($bifasts);
           echo "</pre>";

       ?>  -->
        
        <div class="card-body"> 
            <div class="table-responsive">      
                <table class="table table-hover" style="margin-top:20px; font-size: 14px;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Date Time Request</th>
                            <th>Invoice No</th>
                            <th>Merchant Transaction Id</th>
                            <th>Channel Id</th>
                            <th>Account No</th>
                            <th>Amount</th>
                            <th>Fee</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($bifasts)): ?>
                        <tr>
                            <td colspan="9" align="center">No data in display</td>
                        </tr>
                    <?php else: ?>
                    <?php foreach ($bifasts as $mutation): ?>
                        <tr>
                            <td><?php echo ++$start ?></td>
                            <td><?php echo $mutation->c_datetime; ?></td>
                            <td><?php echo $mutation->c_invoiceNo; ?></td>
                            <td><?php echo $mutation->c_merchantTransactionId; ?></td>
                            <td><?php echo $mutation->ref_cashoutChannelId; ?></td>
                            <td><?php echo $mutation->c_accountNo; ?></td>
                            <td><?php echo number_format($mutation->c_amount, 2); ?></td>
                            <td><?php echo number_format($mutation->c_fee, 2); ?></td>
                            <td><?php echo $mutation->c_status; ?></td>
                            <td>
                                <a href="<?php echo base_url('admin/bi_fast_detail/' . $mutation->id); ?>" class="btn btn-danger btn-sm">Detail</a>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4" style= "font-size: 14px;">
                <div class="pagination-links">
                    <?php echo $pagination; ?>
                </div>
            </div>
                        
        </div>
    </div>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->