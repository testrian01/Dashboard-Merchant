<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="card shadow border-left-info">
        <div class="card-header">
            <h3>Detail Ewallet</h3>
        </div>
        <div class="card-body">
            <!-- <?php
                echo "<pre>";
                print_r($ewallet_data); // or var_dump($data);
                echo "</pre>";
            ?>         -->

            <table style="width:100%">
                <?php foreach ($ewallet_data as $data): ?>
                    <tr>
                        <td>Date Time</td>
                        <td>: <?php echo $data['c_datetime']; ?></td>
                    </tr>
                    <tr>
                        <td>Merchant Id</td>
                        <td>: <?php echo $data['ref_merchantId']; ?></td>
                    </tr>
                    <tr>
                        <td>Merchant Name</td>
                        <td>: <?php echo $data['name_merchant']; ?></td>
                    </tr>
                    <tr>
                        <td>Sub Merchant Id</td>
                        <td>: <?php echo $data['ref_subMerchantId']; ?></td>
                    </tr>
                    <tr>
                        <td>Sub Merchant Name</td>
                        <td>: <?php echo $data['name_submerchant']; ?></td>
                    </tr>
                    <tr>
                        <td>Invoice No</td>
                        <td>: <?php echo $data['c_invoiceNo']; ?></td>
                    </tr>
                    <tr>
                        <td>Channel Id</td>
                        <td>: <?php echo $data['ref_cashinChannelId']; ?></td>
                    </tr>
                    <tr>
                        <td>Type</td>
                        <td>: <?php echo $data['c_type']; ?></td>
                    </tr>
                    <tr>
                        <td>Amount</td>
                        <td>: <?php echo number_format($data['c_amount'], 2); ?></td>
                    </tr>
                    <tr>
                        <td>MDR</td>
                        <td>: <?php echo $data['c_mdr']; ?></td>
                    </tr>
                    <tr>
                        <td>Fee</td>
                        <td>: <?php echo number_format($data['c_fee'], 2); ?></td>
                    </tr>
                    <tr>
                        <td>Date Time Payment</td>
                        <td>: <?php echo $data['c_datetimePayment']; ?></td>
                    </tr>
                    <tr>
                        <td>Is Settlement Realtime</td>
                        <td>: <?php if($data['c_isSettlementRealtime'] == 1) { echo 'Yes'; } else { echo 'No'; } ?></td>
                    </tr>
                    <tr>
                        <td>Date Time Settlement</td>
                        <td>: <?php if($data['c_isSettlementRealtime'] == 1) { echo 'Realtime'; } else { echo $data['c_datetimeSettlement']; } ?></td>
                    </tr>
                    <tr>
                        <td>Merchant Transaction Id</td>
                        <td>: <?php echo $data['c_merchantTransactionId']; ?></td>
                    </tr>
                    
                <?php endforeach; ?>    
            </table>
            
        </div>
    </div>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->