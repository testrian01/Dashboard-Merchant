<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="card shadow border-left-info">
        <div class="card-header">
           <h3>Buat Deposit Ewallet</h3>
        </div>
        <div class="card-body">

            <?php if ($this->session->flashdata('message')): ?>
                <div class="alert alert-info">
                    <?php echo $this->session->flashdata('message'); ?>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success">
                    <?php echo $this->session->flashdata('success'); ?>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('api_response')): ?>
                <div class="alert alert-info">
                    <h4>API Response:</h4>
                    <pre><?php print_r($this->session->flashdata('api_response')); ?></pre>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo $this->session->flashdata('error'); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <form method="post" action="<?php echo base_url('admin/createDepositEwallet'); ?>">
                    <div class="col-md-12">
    
                        <div class="form-group">
                            <label for="nominal">Nominal</label>
                            <input type="text" class="form-control" required id="nominal" name="nominal" style="width: 500px;">
                        </div>
                        <div class="form-group">
                            <label for="channel_tujuan">Channel</label>
                            <select name="channel_tujuan" id="channel_tujuan" style="width: 500px;" class="form-control">
                                <option value="" selected disabled>Channel</option>
                                <option value="ewallet_dana">DANA</option>
                                <option value="ewallet_ovo">OVO</option>
                                <option value="ewallet_shopeepay">SHOPEEPAY</option>
                                <option value="ewallet_linkaja">LINKAJA</option>
                                <option value="ewallet_virgo">VIRGO</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="phone_number">No Hp</label>
                            <input type="text" class="form-control" id="phone_number" name="phone_number" style="width: 500px;">
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
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->