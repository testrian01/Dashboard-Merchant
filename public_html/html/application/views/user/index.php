<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->

    <div class="card shadow border-left-info">
        <div class="card-header">
            <h1 class="h3 text-dark"><?= $title; ?></h1>
        </div>
        <div class="card-body">
            <?= $this->session->flashdata('message'); ?>
            <div class="card mb-3" style="max-width: 540px;">
                <div class="row no-gutters">
                    <div class="col-md-4">
                        <img src="<?php echo base_url('public/image/avatar.png'); ?>" class="shadow card-img" alt="...">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title"><?= $merchant['c_name']; ?></h5>
                            <p class="card-text"><?= $merchant['c_email']; ?></p>
                            <!-- <p class="card-text">
                                <small class="text-muted">
                                    <?php
                                    $formattedDate = date('d F Y', strtotime($merchant['c_dateCreated']));
                                    echo "Member Since $formattedDate";
                                    ?>
                                </small>
                            </p> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->
