<div class="container">

   <!-- Outer Row -->
   <div class="row justify-content-center">

      <div class="col-lg-7">

         <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
               <!-- Nested Row within Card Body -->
               <div class="row">
                  <div class="col-lg">
                     <div class="p-5">
                        <div class="text-center">
                           <h1 class="h4 text-gray-900 mb-4">Forgot your password ?</h1>
                        </div>

                        <?= $this->session->flashdata('message'); ?>

                        <form class="user" method="post" action="<?= base_url('auth/forgotpassword'); ?>">
                           <div class="form-group">
                              <input type="text" class="form-control form-control-user" id="c_email" name="c_email" placeholder="Enter Email Address..." value="<?= set_value('email'); ?>">
                              <?= form_error('c_email', '<small class="text-danger pl-3">', '</small>'); ?>
                           </div>
                           
                           <div class="form-group">
                              <div class="g-recaptcha" data-sitekey="6LeFmMgpAAAAAPl_r3XAMe2BACzpsuG_KzgqIEWK"></div>
                              <!-- <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response"> -->
                           </div>
                           <button type="submit" class="btn btn-primary btn-user btn-block">
                              Reset Password
                           </button>
                        </form>
                        <hr>
                        <div class="text-center">
                           <a class="small" href="<?= base_url('auth'); ?>">Back to login</a>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>

      </div>

   </div>

</div>
