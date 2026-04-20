<div class="container">

  <!-- Outer Row -->
  <div class="row justify-content-center">

      <div class="col-xl-5 col-lg-5 col-md-0">

        <div class="card o-hidden border-0 shadow-lg my-2 login-card mt-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">

              <div class="p-5">
                <div class="text-center mb-3">
                    <h1 class="login-title">Financial Accounting</h1>
                    <h5>Business Oriented Software System</h5>
                </div>
                <div class="text-center">
                  <br /><br />
                  
                  <img src="<?php echo base_url(); ?>assets/img/logo.png" style="max-width:35%; margin-top:-10px; margin-bottom:10px; z-index: 9; position: relative;">
                  <hr />
                  <div class="text-center">
                    <p><b>© Aviskara Perdana Inovasi</b></p>
                  </div>
                </div>

                <?= $this->session->flashdata('message'); ?>

                <form class="user" method="post" action="<?= base_url('auth'); ?>">
                  <div class="form-group">
                    <input type="text" class="form-control form-control-user" id="email" name="email" placeholder="Enter Email Address..." value="<?= set_value('email'); ?>">
                    <?= form_error('email', '<small class="text-danger pl-3">', '</small>'); ?>
                  </div>
                  <div class="form-group">
                    <input type="password" class="form-control form-control-user" id="password" name="password" placeholder="Password">
                    <?= form_error('password', '<small class="text-danger pl-3">', '</small>'); ?>
                  </div>
                  <button type="submit" class="btn btn-theme btn-user btn-block">
                    Login
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>

  </div>

</div>