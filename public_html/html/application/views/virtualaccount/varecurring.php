<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="card shadow border-left-info">
        <div class="card-header">
            <h3>
                Virtual Account Recurring
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
                        // session_start();

                        $search_date_value = '';

                        if(isset($_POST['submit']) && isset($_POST['search_date'])) {
                            $search_date_value = $_POST['search_date'];
                        }

                        if(isset($_POST['submit']) && empty($_POST['search_date'])) {
                            $_SESSION['error_message'] = "Silakan isi tanggal pencarian.";

                            header("Location: " . $_SERVER['HTTP_REFERER']);
                            exit;
                        }

                        $error_message = '';
                        if(isset($_SESSION['error_message'])) {
                            $error_message = $_SESSION['error_message'];

                            unset($_SESSION['error_message']);
                        }
                    ?>

                    <?php if($error_message): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $error_message; ?>
                        </div>
                    <?php endif; ?>
                <div class="col-lg-6"> 
                    <form id="mutation_form" method="post" action="<?php echo base_url('admin/VA_recurring'); ?>">
                        <div class="row mb-4"> 
                            <div class="col-md-12"> 
                                <label for="search_date">Date:</label>
                                <input type="date" id="search_date" name="search_date" class="form-control" value="<?php echo $search_date_value; ?>" >
                            </div>
                        </div>
                        <div class="row mb-4"> 
                            <div class="col-md-6"> 
                                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                            </div>
                            <div class="col-md-6"> 
                                <a href="<?php echo base_url('admin/resetva_recurring'); ?>" type="button" name="reset" class="btn btn-warning">Reset</a>
                            </div>
                            <!-- <div class="col-md-4"> 
                                <?php
                                    $download_url = base_url('admin/download_va_recurring') . "?search_date=" . $search_date_value;
                                ?>
                                <a href="<?php echo $download_url; ?>"  name="download" class="btn btn-danger">Download</a>
                            </div> -->
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>

            <div class="card-body">   
                <div class="table-responsive"> 
                    <table class="table table-hover" style="margin-top:20px;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Date Time Request</th>
                                <th>Sub Merchant</th>
                                <th>Merchant Transaction Id</th>
                                <th>Channel Id</th>
                                <th>VA Number</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <!-- <th>Action</th> -->
                            </tr>
                        </thead>
                        <?php if (empty($varecurrings)): ?>
                            <tr>
                                <td colspan="6" align="center">No data in display</td>
                            </tr>
                        <?php else: ?>
                        <?php foreach ($varecurrings as $mutation): ?>
                            <tr>
                                <td><?php echo ++$start ?></td>
                                <td><?php echo $mutation->c_datetimeRequest; ?></td>
                                <td><?php echo $mutation->name_submerchant; ?></td>
                                <td><?php echo $mutation->c_merchantTransactionId; ?></td>
                                <td><?php echo $mutation->ref_cashinChannelId; ?></td>
                                <td><?php echo $mutation->c_vaNumber; ?></td>
                                <td><?php echo number_format($mutation->c_amount, 2); ?></td>
                                <td><?php echo $mutation->c_status; ?></td>
                                
                            </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-4">
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