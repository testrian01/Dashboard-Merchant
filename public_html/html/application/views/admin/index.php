<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->

    <div class="row">
    <div class="col-sm-12">
        <div class="card">
        <div class="card-body">
            <h4>Welcome <strong><?= $merchant['c_name']; ?></strong> </h4>
        </div>
        </div>
    </div>
    </div>

    <div>&nbsp;</div>
    
    <div class="row">
        <div class="col-sm-4">
            <div class="card">
            <div class="card-body">
                <h5 class="card-title">Balance Total</h5>
                <?php
                    if (isset($saldo['responseDetail']['balanceTotal'])) {
                        $balanceTotal = $saldo['responseDetail']['balanceTotal'];
                        echo 'Rp. ' . number_format($balanceTotal, 2);
                    } else {
                        echo 'Rp. -';
                    }
                ?>
            </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card">
            <div class="card-body">
                <h5 class="card-title">Balance Hold</h5>
                <?php
                    if (isset($saldo['responseDetail']['balanceHold'])) {
                        $balanceHold = $saldo['responseDetail']['balanceHold'];
                        echo 'Rp. ' . number_format($balanceHold, 2);
                    } else {
                        echo 'Rp. -';
                    }
                ?>
            </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card">
            <div class="card-body">
                <h5 class="card-title">Balance Available</h5>
                <?php
                    if (isset($saldo['responseDetail']['balanceAvailable'])) {
                        $balanceAvailable = $saldo['responseDetail']['balanceAvailable'];
                        echo 'Rp. ' . number_format($balanceAvailable, 2);
                    } else {
                        echo 'Rp. -';
                    }
                ?>
            </div>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->
