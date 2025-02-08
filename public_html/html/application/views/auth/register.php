<div class="card o-hidden border-0 shadow-lg my-5 col-lg-6 mx-auto">
	<div class="card-body p-0">
		<!-- Nested Row within Card Body -->
		<div class="row">
			<div class="col-lg">
				<div class="p-5">
					<div class="text-center">
						<h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
					</div>

					<?php
						if($this->session->flashdata('message'))
						{
						?>
							<div class="alert alert-danger">
								<?php
								echo $this->session->flashdata('message');
								?>
							</div>
						<?php
						}

						if($this->session->flashdata('success_message'))
						{
						?>
							<div class="alert alert-success">
								<?php
								echo $this->session->flashdata('success_message');
								?>
							</div>
						<?php
						}
						?>
					<form class="user" method="post" action="<?= base_url('auth/register'); ?>">

						<div class="form-group">
							<input type="text" class="form-control form-control-user" id="name" name="c_name" value="<?= set_value('c_name'); ?>" placeholder="Full Name" required>
							<?= form_error('c_name', '<small class="text-danger pl-3">', '</small>'); ?>
						</div>

						<div class="form-group">
							<input type="text" class="form-control form-control-user" id="c_phoneNumber" name="c_phoneNumber" value="<?= set_value('c_phoneNumber'); ?>" placeholder="Phone Number" required>
							<?= form_error('c_phoneNumber', '<small class="text-danger pl-3">', '</small>'); ?>
						</div>

						<div class="form-group">
							<input type="text" class="form-control form-control-user" id="c_email" name="c_email" value="<?= set_value('c_email'); ?>" placeholder="Email Address" required>
							<?= form_error('c_email', '<small class="text-danger pl-3">', '</small>'); ?>
						</div>

						<div class="form-group row">
							<div class="col-sm-6 mb-3 mb-sm-0">
								<input type="password" class="form-control form-control-user" id="c_password" name="c_password" placeholder="Password" required>
								<?= form_error('c_password', '<small class="text-danger pl-3">', '</small>'); ?>
							</div>
							<div class="col-sm-6">
								<input type="password" class="form-control form-control-user" id="password2" name="password2" placeholder="Repeat Password">
							</div>
						</div>

						<div class="form-group">
							<div class="g-recaptcha" data-sitekey="6LeFmMgpAAAAAPl_r3XAMe2BACzpsuG_KzgqIEWK"></div>
							<!-- <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response"> -->
						</div>

						<button type="submit" class="btn btn-primary btn-user btn-block">
							Register Account
						</button>
					</form>
					<hr>

					<div class="text-center">
						<a class="small" href="<?= base_url('auth/forgotPassword'); ?>">Forgot Password?</a>
					</div>
					<div class="text-center">
						<a class="small" href="<?= base_url('auth'); ?>">Already have an account? Login!</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
