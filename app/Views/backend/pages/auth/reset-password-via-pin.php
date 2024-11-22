<?= $this->extend('backend/layout/auth-layout') ?>
<?= $this->section('content') ?>

<div class="login-box bg-white box-shadow border-radius-10">
    <div class="login-title">
        <h2 class="text-center text-primary">Reset Password via Pin</h2>
    </div>
    <h6 class="mb-20">Enter your email address to receive a pin code to reset your password</h6>

    <?php $validation = \Config\Services::validation(); ?>

    <form action="<?= route_to('send-pin-reset') ?>" method="POST">
        <?= csrf_field(); ?>

        <?php if(!empty(session()->getFlashdata('success'))):  ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success'); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <?php if(!empty(session()->getFlashdata('fail'))):  ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('fail'); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <div class="input-group custom">
            <input type="email" class="form-control form-control-lg" placeholder="Email" name="email" value="<?= set_value('email') ?>" required>
            <div class="input-group-append custom">
                <span class="input-group-text"><i class="fa fa-envelope-o" aria-hidden="true"></i></span>
            </div>
        </div>
        <?php if($validation->getError('email')): ?>
            <div class="d-block text-danger" style="margin-top: -25px;margin: bottom 15px;">
                <?= $validation->getError('email') ?>
            </div>
        <?php endif; ?>

        <div class="row align-items-center">
            <div class="col-12">
                <div class="input-group mb-0">
                    <input class="btn btn-primary btn-lg btn-block" type="submit" value="Send Pin">
                </div>
            </div>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
