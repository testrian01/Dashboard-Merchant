<!-- Begin Page Content -->
    <style>
        
    </style>
<div class="container-fluid">
    <div class="card shadow border-left-info">
        <div class="card-header">
            <h3>Detail Deposit VA</h3>
        </div>
        <div class="card-body">
            <table style="width:100%">
                <tbody>
                    <?php foreach ($detail_va as $data): ?>
                    <tr>
                        <td>Bank</td>
                        <td>: <?php echo $data['ref_cashinChannelId']; ?></td>
                    </tr>
                    <tr>
                        <td>Nominal</td>
                        <td>: <?php echo number_format($data['c_amount'], 2); ?></td>
                    </tr>
                    <tr>
                        <td>Catatan</td>
                        <td>: <?php echo $data['c_displayName']; ?></td>
                    </tr>
                    <tr>
                        <td>Va Number</td>
                        <td>: <?php echo $data['c_vaNumber']; ?></td>
                    </tr>   
                    <tr>
                        <td>Date Time Request</td>
                        <td>: <?php echo date('d-m-Y, H:i:s', strtotime($data['c_datetimeRequest'])); ?></td>
                    </tr>
                    <tr>
                        <td>Date Time Expired</td>
                        <td>: <?php echo date('d-m-Y, H:i:s', strtotime($data['c_datetimeExpired'])); ?></td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td>: <?php echo $data['c_status']; ?></td>
                    </tr>   
                    <?php endforeach; ?>   
                </tbody> 
            </table>
            
        </div>
    </div>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->