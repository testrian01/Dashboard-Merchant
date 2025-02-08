<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="card shadow border-left-info">
        <div class="card-header">
            <h3>
                Ewallet
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
                    $search_date_ewallet_value = $this->session->userdata('search_date_ewallet') ?? '';
                    $search_date_ewallet_settlement_value = $this->session->userdata('search_date_ewallet_settlement') ?? '';
                    $search_name_ewallet_value = $this->session->userdata('search_name_ewallet') ?? '';
                    $search_invoice_no_value = $this->session->userdata('search_invoice_no') ?? '';

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
                    <form id="mutation_form" method="post" action="<?php echo base_url('admin/ewallet'); ?>">
                        <div class="row g-3">

                            <div class="col-md-4">
                                <label for="search_date_ewallet">Date Payment:</label>
                                <input type="date" id="search_date_ewallet" name="search_date_ewallet" class="form-control" value="<?php echo $search_date_ewallet_value; ?>" >
                            </div>

                            <div class="col-md-4">
                                <label for="search_date_ewallet_settlement">Date Settlement:</label>
                                <input type="date" id="search_date_ewallet_settlement" name="search_date_ewallet_settlement" class="form-control" value="<?php echo $search_date_ewallet_settlement_value; ?>" >
                            </div>

                            <div class="col-md-4">
                                <label for="search_invoice_no">Invoice No:</label>
                                <input type="text" id="search_invoice_no" name="search_invoice_no" class="form-control" value="<?php echo $search_invoice_no_value; ?>" >
                            </div>

                            <!-- Buttons -->
                            <div class="col-12 text-start">
                                <button type="submit" name="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Search
                                </button>
                                <a href="<?php echo base_url('admin/resetewallet'); ?>" class="btn btn-warning">
                                    <i class="fas fa-undo"></i> Reset
                                </a>
                                <?php
                                    $download_url = base_url('admin/download_ewallet') . "?search_date_ewallet=" . $search_date_ewallet_value;
                                ?>
                                <a href="<?php echo $download_url; ?>"  name="download" class="btn btn-danger"><i class="fas fa-download"></i> Download</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">       
                <table class="table table-hover" style="margin-top:20px; font-size: 14px; width: 150%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Date Time Payment</th>
                            <th>Sub Merchant Info</th>
                            <th>Invoice No</th>
                            <th>Type</th>
                            <th>Channel Id</th>
                            <th>Amount</th>
                            <th>MDR</th>
                            <th>Fee</th>
                            <th>Datetime Settlement</th>
                            <th>Merchant Transaction Id</th>
                            <th style="text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($ewallets)): ?>
                        <tr>
                            <td colspan="11" align="center">No data in display</td>
                        </tr>
                    <?php else: ?>
                    <?php foreach ($ewallets as $mutation): ?>
                        <tr>
                            <td><?php echo ++$start ?></td>
                            <td><?php echo $mutation->c_datetime; ?></td>
                            <td><?php echo ' [' . $mutation->ref_subMerchantId .'] - '. $mutation->name_submerchant; ?></td>
                            <td><?php echo $mutation->c_invoiceNo; ?></td>
                            <td><?php echo $mutation->c_type; ?></td>
                            <td><?php echo $mutation->ref_cashinChannelId; ?></td>
                            <td><?php echo number_format($mutation->c_amount, 2); ?></td>
                            <td><?php echo $mutation->c_mdr; ?></td>
                            <td><?php echo number_format($mutation->c_fee, 2); ?></td>
                            <td><?php if($mutation->c_isSettlementRealtime == 1) { echo 'Realtime'; } else { echo $mutation->c_datetimeSettlement; } ?></td>
                            <td><?php echo $mutation->Merchant_Transaction_Id; ?></td>
                            <td>
                                <a href="<?php echo base_url('admin/ewallet_detail/' . $mutation->id); ?>" class="btn btn-danger btn-sm">Detail</a>
                                <a onclick="javascript: return confirm('Are you sure, want to resend notification again ??')" href="<?php echo base_url('admin/Sendnotifikasiewallet/' . $mutation->id); ?>" class="btn btn-warning btn-sm">Resend Notification</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4" style="font-size: 14px;">
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