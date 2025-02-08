<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->

    <div class="card shadow border-left-info">
        <div class="card-header">
           <h3>Deposit</h3>
        </div>
        <div class="card-body">
    
            <p>Untuk melakukan deposit, silahkan melakukan transfer ke rekening berikut:</p>
            
            <ul style="list-style-type: none; padding: 0;">
                <li><strong>Bank:</strong> BCA</li>
                <li><strong>A/N:</strong> Gerbang Inovasi Digital</li>
                <li><strong>No Rekening:</strong> 5050177400</li>
            </ul>

            <p>Mohon setelah selesai transfer, lakukan konfirmasi manual ke email <strong>support@gidi.co.id </strong></p>

            <hr>

            <p>Metode pembayaran lainnya:</p>

            <div class="row">
                <div class="col-sm-3">
                    <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Virtual Account</h5>
                        <a href="<?= base_url('admin/CreateVirtualAccount/'); ?>" class="btn btn-outline-primary">Buat Deposit</a>
                    </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">QRIS</h5>
                        <button type="button" id="qrisButton" class="btn btn-outline-primary">Buat Deposit</button>
                    </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Ewallet</h5>
                        <a href="<?= base_url('admin/CreateEwallet/'); ?>" class="btn btn-outline-primary">Buat Deposit</a>
                    </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Cash in Counter</h5>
                        <button type="button" id="counterButton" class="btn btn-outline-primary">Buat Deposit</button>
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