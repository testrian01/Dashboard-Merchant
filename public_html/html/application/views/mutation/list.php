<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="card shadow border-left-info">
        <div class="card-header">
            <h3>
                Mutation
            </h3>
        </div>
        <!-- <?php
        var_dump($mutations);
        var_dump($date);
        var_dump($position)
        
        ?> -->
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

                <div class="col-lg-4"> 
                    <form id="mutation_form" method="post" action="<?php echo base_url('admin/mutation'); ?>">
                        <div class="row mb-4"> 
                            <div class="col-md-12"> 
                                <label for="search_date">Date:</label>
                                <input type="date" id="search_date" name="search_date" class="form-control" value="<?php echo $search_date_value; ?>" >
                            </div>
                        </div>
                        <div class="row mb-4"> 
                            <div class="col-md-4"> 
                                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                            </div>
                            <div class="col-md-4"> 
                                <a href="<?php echo base_url('admin/resetMutation'); ?>" type="button" name="reset" class="btn btn-warning">Reset</a>
                            </div>
                            <div class="col-md-4"> 
                                <?php
                                    $download_url = base_url('admin/download_mutation') . "?search_date=" . $search_date_value;
                                ?>
                                <a href="<?php echo $download_url; ?>"  name="download" class="btn btn-danger">Download</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" style="margin-top:20px; font-size: 14px;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Date Time</th>
                            <th>Potition</th>
                            <th>Channel Id</th>
                            <th>Invoice No</th>
                            <th>Description</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($mutations)): ?>
                        <tr>
                            <td colspan="7" align="center">No data in display</td>
                        </tr>
                    <?php else: ?>
                    <?php foreach ($mutations as $mutation): ?>
                        <tr>
                            <td><?php echo ++$start ?></td>
                            <td><?php echo $mutation->timeRefLog; ?></td>
                            <td><?php echo $mutation->c_potition; ?></td>
                            <td><?php echo $mutation->channelName; ?></td>
                            <td><?php echo $mutation->refNoLog; ?></td>
                            <td><?php echo $mutation->description; ?></td>
                            <td><?php echo number_format($mutation->c_amount, 2); ?></td>
                            
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
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

