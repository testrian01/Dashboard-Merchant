<!-- Footer -->
<footer class="sticky-footer bg-white">
  <div class="container my-auto">
    <div class="copyright text-center my-auto">
      <span>Copyright &copy; gidi.co.id <?= date('Y'); ?></span>
    </div>
  </div>
</footer>
<!-- End of Footer -->

</div>
<!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
  <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
        <a class="btn btn-primary" href="<?= base_url('auth/logout'); ?>">Logout</a>
      </div>
    </div>
  </div>
</div>

<!-- Virtual Account Modal -->
<div class="modal fade" id="vaModal" tabindex="-1" role="dialog" aria-labelledby="vaModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="vaModalLabel">Virtual Account</h5>
      </div>
      <div class="modal-body">
        <!-- <form id="mutation_form" method="post" action="<?php echo base_url('admin/createDepositVA'); ?>">
            <div class="row col-lg-12"> 
                <div class="col-lg-12"> 
                    <label for="nominal">Nominal</label>
                    <input type="number" id="nominal" name="nominal" class="form-control" >
                </div>
            </div>
            &nbsp;
            <div class="row col-lg-12"> 
                <div class="col-lg-12"> 
                    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form> -->
        <h5>Saat ini fitur deposit belum tersedia</h5>
      </div>
    </div>
  </div>
</div>

<!-- QRIS Modal -->
<div class="modal fade" id="qrisModal" tabindex="-1" role="dialog" aria-labelledby="qrisModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="qrisModalLabel">QRIS</h5>
      </div>
      <div class="modal-body">
        <!-- <form id="mutation_form" method="post" action="<?php echo base_url('admin/VA_recurring'); ?>">
            <div class="row col-lg-12"> 
                <div class="col-lg-12"> 
                    <label for="nominal">Nominal:</label>
                    <input type="date" id="nominal" name="nominal" class="form-control" >
                </div>
            </div>
            &nbsp;
            <div class="row col-lg-12"> 
                <div class="col-lg-12"> 
                    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form> -->
        <h5>Saat ini fitur deposit belum tersedia</h5>
      </div>
    </div>
  </div>
</div>

<!-- Alfamart Modal -->
<div class="modal fade" id="alfamartModal" tabindex="-1" role="dialog" aria-labelledby="alfamartModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="alfamartModalLabel">Alfamart</h5>
      </div>
      <div class="modal-body">
        <h5>Saat ini fitur deposit belum tersedia</h5>
      </div>
    </div>
  </div>
</div>

<!-- Indomaret Modal -->
<div class="modal fade" id="indomaretModal" tabindex="-1" role="dialog" aria-labelledby="indomaretModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="indomaretModalLabel">Indomaret</h5>
      </div>
      <div class="modal-body">
        <h5>Saat ini fitur deposit belum tersedia</h5>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap core JavaScript-->
<script src="<?= base_url('assets/'); ?>vendor/jquery/jquery.min.js"></script>

<script src="<?= base_url('assets/'); ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="<?= base_url('assets/'); ?>vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="<?= base_url('assets/'); ?>js/sb-admin-2.min.js"></script>


<!-- Page level plugins -->
<script src="<?= base_url('assets/'); ?>vendor/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url('assets/'); ?>vendor/datatables/dataTables.bootstrap4.min.js"></script>

<!-- Page level custom scripts -->
<script src="<?= base_url('assets/'); ?>js/demo/datatables-demo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
<script>
  $('.custom-file-input').on('change', function() {
    let fileName = $(this).val().split('\\').pop();
    $(this).next('.custom-file-label').addClass("selected").html(fileName);
  });

  $('.form-check-input').on('click', function() {
    const menuId = $(this).data('menu');
    const roleId = $(this).data('role');

    $.ajax({
      url: "<?= base_url('admin/changeaccess'); ?>",
      type: 'post',
      data: {
        menuId: menuId,
        roleId: roleId
      },
      success: function() {
        document.location.href = "<?= base_url('admin/roleaccess/'); ?>" + roleId;
      }
    });

  });
</script>

<script>
  function getPulsaData() {
    var selectedProvider = $('#selectprovider').val();

    $.ajax({
      type: 'POST',
      url: '<?= base_url('admin/get_pulsa_data'); ?>',
      data: {
        provider: selectedProvider
      },
      success: function(response) {
        // Handle the response (you can display the data in the UI as needed)
        console.log(response);
      },
      error: function(error) {
        console.log('Error:', error);
      }
    });
  }
</script>

<script>
  function resetForm() {
    document.getElementById("searchForm").reset();
  }
</script>


<script>
  // Function to show the Virtual Account modal
  $('#vaButton').click(function() {
    $('#vaModal').modal('show');
  });

  // Function to show the QRIS modal
  $('#qrisButton').click(function() {
    $('#qrisModal').modal('show');
  });

  // Function to show the Alfamart modal
  $('#alfamartButton').click(function() {
    $('#alfamartModal').modal('show');
  });

  // Function to show the Indomaret modal
  $('#indomaretButton').click(function() {
    $('#indomaretModal').modal('show');
  });

  $(document).on('click', '#deleteRekening', function() {
    var id = $(this).data('id');
    var accountName = $(this).data('bank');
    var accountNo = $(this).data('no');

    $('#confirm-id').val(id);
    $('#confirm-account-name').text(accountName);
    $('#confirm-account-no').text(accountNo);

    $('#modal-confirm').modal('show');
  });

  $('#modal-confirm').on('hidden.bs.modal', function() {
    $('body').removeClass('modal-open'); // Hapus kelas yang mengunci scroll
    $('.modal-backdrop').remove(); // Hapus overlay jika masih ada
  });
  $(document).on('click', '.btn-otp', function() {
    var accountId = $(this).data('id');
    $('#otpAccountId').val(accountId);
  });
</script>
</script>
</body>

</html>