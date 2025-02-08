<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="card shadow border-left-info">
        <?php if ($this->session->flashdata('success')) : ?>
            <div class="alert alert-success">
                <?php echo $this->session->flashdata('success'); ?>
            </div>
        <?php endif; ?>
        <div class="card-header">
            <h3>Detail BI Fast</h3>
        </div>
        <div class="card-body">
            <!-- <?php
                    echo "<pre>";
                    print_r($bifast_data); // or var_dump($data);
                    echo "</pre>";
                    ?>         -->
            <table style="width:100%">
                <?php foreach ($bifast_data as $data): ?>
                    <tr>
                        <td>Date Time Request</td>
                        <td>: <?php echo $data['c_datetime']; ?></td>
                    </tr>
                    <tr>
                        <td>Invoice No</td>
                        <td>: <?php echo $data['c_invoiceNo']; ?></td>
                    </tr>
                    <tr>
                        <td>Channel Id</td>
                        <td>: <?php echo $data['ref_cashoutChannelId']; ?></td>
                    </tr>
                    <tr>
                        <td>Account No</td>
                        <td>: <?php echo $data['c_accountNo']; ?></td>
                    </tr>
                    <tr>
                        <td>Amount</td>
                        <td>: <?php echo number_format($data['c_amount'], 2); ?></td>
                    </tr>
                    <tr>
                        <td>Method Fee</td>
                        <td>: <?php echo $data['c_methodFee']; ?></td>
                    </tr>
                    <tr>
                        <td>Fee</td>
                        <td>: <?php echo number_format($data['c_fee'], 2); ?></td>
                    </tr>
                    <tr>
                        <td>Amount Transfer</td>
                        <td>: <?php echo number_format($data['c_amountTransfer'], 2); ?></td>
                    </tr>
                    <tr>
                        <td>Amount Debit</td>
                        <td>: <?php echo number_format($data['c_amountDebit'], 2); ?></td>
                    </tr>
                    <tr>
                        <td>Merchant Transaction Id</td>
                        <td>: <?php echo $data['c_merchantTransactionId']; ?></td>
                    </tr>
                    <tr>
                        <td>Note Transfer</td>
                        <td>: <?php echo $data['c_transferNote']; ?></td>
                    </tr>
                    <tr>
                        <td>Status Transfer</td>
                        <td>: <?php echo $data['c_status']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>

        </div>
    </div>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->