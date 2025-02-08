<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="card shadow border-left-info">
        <div class="card-header">
            <h3>
                Ewallet Dynamic
            </h3>
        </div>

        <div class="card-body">
            <div class="row">
                <!-- Success Flash Message -->
                <?php if ($this->session->flashdata('success')) : ?>
                    <div class="alert alert-success">
                        <?php echo $this->session->flashdata('success'); ?>
                    </div>
                <?php endif; ?>

                <!-- Error Message -->
                <?php
                $search_date_ewallet_value = $this->session->userdata('search_date_ewallet') ?? '';
                $search_transid_ewallet_value = $this->session->userdata('search_transid_ewallet') ?? '';
                $search_status_transaction_ewallet_value = $this->session->userdata('search_status_transaction_ewallet') ?? '';

                $error_message = '';
                if (isset($_SESSION['error_message'])) {
                    $error_message = $_SESSION['error_message'];
                    unset($_SESSION['error_message']);
                }
                ?>

                <?php if ($error_message) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>

                <!-- Form Filter -->
                <div class="col-12">
                    <form id="mutation_form" method="post" action="<?php echo base_url('admin/ewallet_dynamic'); ?>">
                        <div class="row g-3">
                            <!-- Date Request -->
                            <div class="col-md-4">
                                <label for="search_date_ewallet" class="form-label">Date Request:</label>
                                <input type="date" id="search_date_ewallet" name="search_date_ewallet" class="form-control" value="<?php echo $search_date_ewallet_value; ?>">
                            </div>

                            <!-- Merchant Transaction ID -->
                            <div class="col-md-4">
                                <label for="search_transid_ewallet" class="form-label">Merchant Transaction Id:</label>
                                <input type="text" id="search_transid_ewallet" name="search_transid_ewallet" class="form-control" value="<?php echo $search_transid_ewallet_value; ?>">
                            </div>

                            <!-- Status Transaction -->
                            <div class="col-md-4">
                                <label for="search_status_transaction_ewallet" class="form-label">Status Transaction:</label>
                                <select id="search_status_transaction_ewallet" name="search_status_transaction_ewallet" class="form-select">
                                    <option value="">-- Choose --</option>
                                    <option value="Pending" <?php echo ($search_status_transaction_ewallet_value == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                    <option value="Created" <?php echo ($search_status_transaction_ewallet_value == 'Created') ? 'selected' : ''; ?>>Created</option>
                                    <option value="Paid" <?php echo ($search_status_transaction_ewallet_value == 'Paid') ? 'selected' : ''; ?>>Paid</option>
                                    <option value="Failed" <?php echo ($search_status_transaction_ewallet_value == 'Failed') ? 'selected' : ''; ?>>Failed</option>
                                </select>
                            </div>

                            <!-- Buttons -->
                            <div class="col-12 text-start">
                                <button type="submit" name="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Search
                                </button>
                                <a href="<?php echo base_url('admin/resetewallet_dynamic'); ?>" class="btn btn-warning">
                                    <i class="fas fa-undo"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="card-body">       
                <table class="table table-hover" style="margin-top:20px;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Date Time Request</th>
                            <th>Sub Merchant Info</th>
                            <th>Merchant Transaction Id</th>
                            <th>Channel Id</th>
                            <th>Amount</th>
                            <th>Date Time Expired</th>
                            <th>Status</th>
                            <th>#</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($ewalletdynamics)): ?>
                        <tr>
                            <td colspan="6" align="center">No data in display</td>
                        </tr>
                    <?php else: ?>
                    <?php foreach ($ewalletdynamics as $mutation): ?>
                        <tr>
                            <td><?php echo ++$start ?></td>
                            <td><?php echo $mutation->c_datetimeRequest; ?></td>
                            <td><?php echo ' [' . $mutation->ref_subMerchantId .'] - '. $mutation->name_submerchant; ?></td>
                            <td><?php echo $mutation->c_merchantTransactionId; ?></td>
                            <td><?php echo $mutation->ref_cashinChannelId; ?></td>
                            <td><?php echo number_format($mutation->c_amount); ?></td>
                            <td><?php echo $mutation->c_datetimeExpired; ?></td>
                            <td>
                                <?php 
                                    if ($mutation->c_status == "Paid") {
                                        echo '<a href="' . base_url('admin/ewallet_detail/' . $mutation->ref_cashinPaymentEwalletId) . '" target="_blank">' . $mutation->c_status . '</a>';
                                    } else {
                                        echo $mutation->c_status;
                                    }
                                ?>
                            </td>
                            <td>
                                <?php if($stateProgram == 'Development' && $mutation->c_status == 'Created') {  ?>

                                    <a onclick="javascript: return confirm('Are you sure, want to simulation payment ??')" href="<?php echo base_url('admin/simulationPaymentEwallet/' . $mutation->id); ?>" class="btn btn-success btn-sm">Simulation Payment</a>

                                <?php } ?>
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

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->