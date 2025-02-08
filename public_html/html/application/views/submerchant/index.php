<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="card shadow border-left-info">
        <div class="card-header">
            <h3>Sub Merchant</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-4"> 
                    <form id="mutation_form" method="post" action="<?php echo base_url('admin/submerchant'); ?>">
                        <div class="row mb-4"> 
                            <div class="col-md-12"> 
                                <label for="search_name_submerchant_submerchant">Name Submerchant:</label>
                                <input type="text" id="search_name_submerchant" name="search_name_submerchant" class="form-control" value="<?php echo $this->input->post('search_name_submerchant'); ?>" >
                            </div>
                        </div>
                        <div class="row mb-4"> 
                            <div class="col-md-6"> 
                                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                            </div>
                            <div class="col-md-6"> 
                                <a href="<?php echo base_url('admin/resetsubmerchant'); ?>" type="button" name="reset" class="btn btn-warning">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="card-body">       
            <div class="table-responsive">       
                <table class="table table-hover" style="margin-top:20px; font-size: 14px; width: 150%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>QRIS MPM</th>
                            <th>VA BCA</th>
                            <th>VA BNI</th>
                            <th>VA CIMB</th>
                            <th>VA Permata</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if(empty($submerchants)): ?>
                        <tr>
                            <td colspan="4" align="center">No data in display</td>
                        </tr>
                    <?php else: ?>
                    <?php foreach ($submerchants as $mutation): ?>
                        <tr>
                            <td><?php echo ++$start ?></td>
                            <td><?php echo $mutation->id; ?></td>
                            <td><?php echo $mutation->c_name; ?></td>
                            <td><?php echo $mutation->c_email; ?></td>
                            <td><?php echo $mutation->c_gvconnectStaticQrisRaw; ?></td>
                            <td><?php echo $mutation->c_gvconnectStaticVaBca; ?></td>
                            <td><?php echo $mutation->c_gvconnectStaticVaBni; ?></td>
                            <td><?php echo $mutation->c_gvconnectStaticVaCimb; ?></td>
                            <td><?php echo $mutation->c_gvconnectStaticVaPermata; ?></td>
                            <td><?php echo $mutation->c_status; ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4" style= "font-size: 14px;">
                <div class="pagination-links">
                    <?php echo $pagination; ?>
                </div>
            </div>
        </div>


</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->