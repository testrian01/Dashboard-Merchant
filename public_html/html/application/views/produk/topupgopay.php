
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->

    <?php if ($this->session->flashdata('success_message')): ?>
        <div style="color: green;">
            <strong>Sukses:</strong> <?= $this->session->flashdata('success_message'); ?>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error_message')): ?>
        <div style="color: red;">
            <strong>Gagal:</strong> <?= $this->session->flashdata('error_message'); ?>
        </div>
    <?php endif; ?>

        <div class="card shadow border-left-info">
        <?= $this->session->flashdata('message'); ?>

        <div class="card-header">
            <h1 class="h3 text-dark"><?= $title; ?></h1>
        </div>
    <div class="card-body">
        <div class="table-responsive mt-4">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="1000px" heigh="20px"></th>
                        <th></th>
                    </tr>
                </thead>
                
                <tbody>
                    <?php foreach ($gopay as $row): ?>
                        <tr>
                            <td data-bs-toggle="modal" data-bs-target="#exampleModal<?= $row->id; ?>">
                                <?= $row->c_caption; ?>
                            </td>
                            <td>
                                Rp. <?= $row->c_fee; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php foreach ($gopay as $row): ?>
        <div class="modal fade" id="exampleModal<?= $row->id; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Anda akan membeli <strong><span id="modal-id"><?= $row->id; ?></span></strong></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="purchaseForm<?= $row->id; ?>" method="post" action="<?= base_url('admin/purchase_token/' . $row->id); ?>">
                            <div class="mb-3">
                                <label for="token_pln" class="form-label">Produk</label>
                                <input type="tel" class="form-control" hidden id="token_pln<?= $row->id; ?>" name="token_pln" value="<?= $row->id; ?>" required>
                                <input type="tel" class="form-control" id="token_pln<?= $row->id; ?>" name="token_pln" value="<?= $row->c_caption; ?>" readonly>
                                <input type="hidden" name="name" value="topupgopay">
                            </div>
                            <div class="mb-3">
                                <label for="id_pln" class="form-label">No Telp</label>
                                <input type="tel" class="form-control" id="id_pln<?= $row->id; ?>" name="id_pln" required>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    </div>
    <!-- /.container-fluid -->

</div>
<!-- End of Main Content -->
