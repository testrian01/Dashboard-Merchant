<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" style="font-size: 10px;" id="accordionSidebar">

   <!-- Sidebar - Brand -->
   <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
      <div class="sidebar-brand-icon rotate-n-15">
        <img src="<?= base_url('public/image/icon.png'); ?>" alt="Logo" width="50">
      </div>
      <div class="sidebar-brand-text mx-3"></div>
   </a>

   <!-- Divider -->
   <hr class="sidebar-divider my-0">
            <li class="nav-item ">
                <a class="nav-link" href="<?= base_url('admin'); ?>">
                    <i class="fas fa-fw fa-tachometer-alt icon"></i>
                    <span class="nav-text">Dashboard</span></a>
            </li>

            <li class="nav-item ">
                <a class="nav-link" href="<?= base_url('admin/deposit'); ?>">
                    <i class="far fa-money-bill-alt icon"></i>
                    <span class="nav-text">Deposit</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseproduk"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-shopping-cart icon"></i>
                    <span class="nav-text">Purchase</span>
                </a>
                <div id="collapseproduk" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="<?= base_url('admin/pulsareguler'); ?>">Pulsa Reguler</a>
                        <a class="collapse-item" href="<?= base_url('admin/paketdata'); ?>">Paket Data</a>
                        <a class="collapse-item" href="<?= base_url('admin/tokenlistrik'); ?>">Token Listrik</a>
                        <a class="collapse-item" href="<?= base_url('admin/topupgopay'); ?>">Topup Gopay</a>
                        <a class="collapse-item" href="<?= base_url('admin/topupdana'); ?>">Topup Dana</a>
                        <a class="collapse-item" href="<?= base_url('admin/topupovo'); ?>">Topup OVO</a>
                        <a class="collapse-item" href="<?= base_url('admin/googleplay'); ?>">Google Play</a>
                        <a class="collapse-item" href="<?= base_url('admin/freefire'); ?>">Free Fire</a>
                        <!-- <a class="collapse-item" href="<?= base_url('admin/garena'); ?>">Garena AOV</a> -->
                        <a class="collapse-item" href="<?= base_url('admin/hago'); ?>">Hago</a>
                        <a class="collapse-item" href="<?= base_url('admin/mobilelegend'); ?>">Mobile Legend</a>
                        <a class="collapse-item" href="<?= base_url('admin/pubgmobile'); ?>">PUBG Mobile</a>
                    </div>
                </div>
            </li>
           
            <!-- <li class="nav-item ">
                <a class="nav-link" href="<?= base_url('admin/submerchant'); ?>">
                    <i class="fas fa-wallet"></i>
                    <span class="nav-text">Sub Merchant</span></a>
            </li> -->

            <li class="nav-item ">
                <a class="nav-link" href="<?= base_url('admin/mutation'); ?>">
                    <i class="fas fa-book icon"></i>
                    <span class="nav-text">Mutation</span></a>
            </li>
            <?php
                $merchantOpenapiStatus = $merchant['c_openapiStatus']; 

                if ($merchantOpenapiStatus == "Active") {
            ?>
            <!-- <li class="nav-item ">
                <a class="nav-link" href="<?= base_url('admin/settlement'); ?>">
                    <i class="fas fa-money-bill-alt icon"></i>
                    <span class="nav-text">Settlement</span></a>
            </li> -->
            <?php
                }
            ?>
            
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsehistory"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-history icon"></i>
                    <span class="nav-text">History Transaction</span>
                </a>
                <div id="collapsehistory" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="<?= base_url('admin/history'); ?>">Purchase</a>
                        <?php
                            $merchantOpenapiStatus = $merchant['c_openapiStatus']; 

                            if ($merchantOpenapiStatus == "Active") {
                        ?>
                        <a class="collapse-item" href="<?= base_url('admin/virtual_account'); ?>">Virtual Account</a>
                        <a class="collapse-item" href="<?= base_url('admin/ewallet'); ?>">Ewallet</a>
                        <a class="collapse-item" href="<?= base_url('admin/qris'); ?>">QRIS</a>
                        <a class="collapse-item" href="<?= base_url('admin/bi_fast'); ?>">Disbursement</a>
                        <?php
                            } 
                        ?>
                    </div>
                </div>
            </li>
            <!-- <li class="nav-item">
                <a class="nav-link" href="<?= base_url('admin/history'); ?>">
                    <i class="fas fa-history"></i>
                    <span class="nav-text">History Transaction</span>
                </a>
            </li> -->

            <?php
            $merchantOpenapiStatus = $merchant['c_openapiStatus']; 

            if ($merchantOpenapiStatus == "Active") {
                ?>

            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsevadynamic"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-money-check icon"></i>
                    <span class="nav-text">Virtual Account Dynamic</span>
                </a>
                <div id="collapsevadynamic" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="<?= base_url('admin/Va_dynamic'); ?>">History VA Dynamic</a>
                        
                    </div>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsevarecurring"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-money-check icon"></i>
                    <span class="nav-text">Virtual Account Recurring</span>
                </a>
                <div id="collapsevarecurring" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="<?= base_url('admin/VA_recurring'); ?>">History VA Recurring</a>
                        
                    </div>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseewalletdynamic"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-money-check icon"></i>
                    <span class="nav-text">Ewallet Dynamic</span>
                </a>
                <div id="collapseewalletdynamic" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="<?= base_url('admin/ewallet_dynamic'); ?>">History Ewallet Dynamic</a>
                        
                    </div>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseqrisdynamic"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-certificate icon"></i>
                    <span class="nav-text">QRIS Dynamic</span>
                </a>
                <div id="collapseqrisdynamic" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="<?= base_url('admin/qris_dynamic'); ?>">History QRIS Dynamic</a>
                        
                    </div>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseqrisrecurring"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-certificate icon"></i>
                    <span class="nav-text">QRIS Recurring</span>
                </a>
                <div id="collapseqrisrecurring" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="<?= base_url('admin/qris_recurring'); ?>">History QRIS Recurring</a>
                        
                    </div>
                </div>
            </li>

            <?php if ($merchant['c_allowTransferFromDashboard']) {  ?>
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsesettlement"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-money-check icon"></i>
                    <span class="nav-text">Settlement</span>
                </a>
                <div id="collapsesettlement" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="<?= base_url('admin/createSettlement'); ?>">Create Settlement</a>
                        <a class="collapse-item" href="<?= base_url('admin/merchantBankAccount'); ?>">Bank Account</a>
                    </div>
                </div>
            </li>
            <?php } ?>

               
                <?php
            } 
        ?>
        <li class="nav-item ">
                <a class="nav-link" href="<?= base_url('admin/report'); ?>">
                    <i class="far fa-save icon"></i>
                    <span class="nav-text"> Report</span></a>
            </li>
            
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url('auth/logout'); ?>" data-toggle="modal" data-target="#logoutModal">
                <i class="fas fa-fw fa-sign-out-alt icon"></i>
                <span class="nav-text">Logout</span></a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider d-none d-md-block">

        <!-- Sidebar Toggler (Sidebar) -->
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>

</ul>