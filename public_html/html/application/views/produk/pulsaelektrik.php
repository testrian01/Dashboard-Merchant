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


        <div class="modal" tabindex="-1" id="modaltest">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                    <div class="modal-body">
                        <form action="<?= base_url('admin/purchase'); ?>" method="post">
                            <div class="mb-3">
                                <label for="channel" class="form-label">Produk</label>
                                <input type="tel" class="form-control" id="channel" name="channel" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="Phone" class="form-label">Phone</label>
                                <input type="tel" class="form-control" id="Phone" name="Phone" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="harga" class="form-label">Harga</label>
                                <input type="tel" class="form-control" id="harga" name="harga" readonly>
                            </div>
                            <div class="modal-footer">
                                
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <input type="text" id="phone" name="phone" placeholder="Enter phone number">
                </div>
            </div>

            <!-- <?php
            var_dump($merchant);
            ?> -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive mt-4">
                        <table id="data-table" class="table table-hover">
                            <thead>
                                <tr>
                                    <!-- <th>ID</th>
                                    
                                    <th>Fee</th> -->
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data akan ditambahkan di sini oleh skrip JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Load jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<!-- Load jQuery UI for autocomplete -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
$(document).ready(function() {
    $('#phone').keyup(function() {
        var prefix = $(this).val().substring(0, 4); 
        var provider = '';
        switch (prefix) {
            case '0896':
            case '0897':
            case '0898':
            case '0899':
                provider = 'pulsa_tri';
                break;
            case '0813':
            case '0812':
            case '0821':
            case '0811':
            case '0851':
            case '0852':
                provider = 'pulsa_telkomsel';
                break;
            case '0838':
            case '0831':
                provider = 'pulsa_axis';
                break;
            case '0817':
            case '0818':
            case '0819':
            case '0859':
                provider = 'pulsa_xl';
                break;
            default:
                break;
        }
        if (provider !== '') {
            $.ajax({
                url: "<?php echo base_url('admin/autocomplete'); ?>",
                method: "POST",
                data: { prefix: prefix },
                dataType: "json",
                success: function(data) {
                    // Kosongkan tabel sebelum menambahkan data baru
                    $('#data-table tbody').empty();
                    // Loop melalui setiap objek dalam data JSON
                    $.each(data, function(index, item) {
                        // Bangun baris tabel untuk setiap objek
                        // var row = '<tr>' +
                        //     '<td class="modal-trigger" data-id="' + item.id + '" data-channel="' + item.channel_id + '" data-c_fee="' + item.c_fee + '">' + item.id + '</td>' +
                        //     '<td style="text-align: right;">' + item.c_fee + '</td>' +
                        //     '</tr>';

                            var row = '<tr>' +
                        '<td class="modal-trigger" data-id="' + item.id + '" data-channel="' + item.channel_id + '" data-c_caption="' + item.c_caption + '" data-c_fee= "' + item.c_fee + '">' + item.c_caption + '</td>' +
                        '<td style="text-align: right;">' + item.c_fee + '</td>' +
                        '</tr>';

                        // Tambahkan baris ke dalam tabel
                        $('#data-table tbody').append(row);
                    });

                    // Tambahkan event listener untuk memunculkan modal saat baris diklik
                    $('.modal-trigger').click(function() {
                        var id = $(this).data('id');
                        var harga = $(this).data('c_fee'); // Ambil harga dari data c_fee
                        showModal(id, harga); // Meneruskan harga ke fungsi showModal
                    });
                }

            });
        }
    });

    // Inisialisasi autocomplete pada input phone
    $('#phone').autocomplete({
        source: [], // Data akan diisi oleh permintaan AJAX
        minLength: 0, // Menampilkan autocomplete saat input focus
        autoFocus: true // Fokus otomatis pada pertama hasil autocomplete
    });

    // Fungsi untuk menampilkan modal
    function showModal(id, harga) {
        // Mengubah judul modal sesuai dengan ID yang diterima
        $('.modal-title').text('Kamu akan checkout produk ' + id + ' dengan detail berikut');
        // Mengisi input "Channel" dengan ID yang diterima
        $('#channel').val(id);
        $('#harga').val(harga);
        // Ambil nilai dari input phone
        var phoneValue = $('#phone').val();
        // Set nilai input Phone di dalam modal
        $('#Phone').val(phoneValue);
        // Menampilkan modal
        $('#modaltest').modal('show');
    }
});

</script>

