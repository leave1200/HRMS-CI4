<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">
        <div class="page-header">
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="title">
                        <h4>Profile</h4>
                    </div>
                    <nav aria-label="breadcrumb" role="navigation">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="<?= route_to('admin.home');?>">Home</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Profile</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-30">
                <div class="pd-20 card-box height-100-p">
                    <div class="profile-photo">
                        <a href="javascript:;" onclick="document.getElementById('user_profile_file').click();" class="edit-avatar">
                            <i class="fa fa-pencil"></i>
                        </a>
                        <input type="file" name="user_profile_file" id="user_profile_file" class="d-none" accept="image/*">
                        <img src="<?= get_user()->picture == null ? '/images/users/userav-min.png' : '/images/users/'.get_user()->picture ?>" id="profileImage" alt="" class="avatar-photo ci-avatar-photo">
                    </div>
                    <h5 class="text-center h5 mb-0 ci-user-name"><?= get_user()->name ?></h5>
                    <p class="text-center text-muted font-14 ci-user-email"><?= get_user()->email ?></p>
                    <button id="saveProfileButton" class="btn btn-primary mt-3">Save Profile Picture</button>
                </div>
            </div>
            <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 mb-30">
                <div class="card-box height-100-p overflow-hidden">
                    <div class="profile-tab height-100-p">
                        <div class="tab height-100-p">
                            <ul class="nav nav-tabs customtab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#personal_details" role="tab">Personal Details</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#change_password" role="tab">Change Password</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <!-- Personal Details Tab -->
                                <div class="tab-pane fade active show" id="personal_details" role="tabpanel">
                                    <div class="pd-20">
                                        <?php if (session()->has('errors')): ?>
                                            <div class="alert alert-danger">
                                                <?php foreach (session('errors') as $error): ?>
                                                    <p><?= $error ?></p>
                                                <?php endforeach ?>
                                            </div>
                                        <?php endif ?>

                                        <?php if (session()->has('success')): ?>
                                            <div class="alert alert-success"><?= session('success') ?></div>
                                        <?php endif ?>

                                        <?php if (session()->has('error')): ?>
                                            <div class="alert alert-danger"><?= session('error') ?></div>
                                        <?php endif ?>

                                        <form action="<?= route_to('update-personal-details'); ?>" method="POST" id="personal_details_form">
                                            <?= csrf_field(); ?>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="">Name</label>
                                                        <input type="text" name="name" class="form-control" placeholder="Enter full name" value="<?= old('name', get_user()->name) ?>">
                                                        <span class="text-danger error-text name_error" id="name"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="">Username</label>
                                                        <input type="text" name="username" class="form-control" placeholder="Enter Username" value="<?= old('username', get_user()->username) ?>">
                                                        <span class="text-danger error-text username_error" id="username"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Bio</label>
                                                <textarea name="bio" cols="30" rows="10" class="form-control" placeholder="Bio....."><?= old('bio', get_user()->bio) ?></textarea>
                                                <span class="text-danger error-text bio_error"></span>
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <!-- Change Password Tab -->
                                <div class="tab-pane fade" id="change_password" role="tabpanel">
                                    <div class="pd-20 profile-task-wrap">
                                        <form action="<?= route_to('change-password') ?>" method="POST" id="change_password_form">
                                            <?= csrf_field(); ?>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="">Current Password</label>
                                                        <input type="password" class="form-control" name="current_password" placeholder="Enter current password">
                                                        <span class="text-danger error-text current_password_error"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="">New Password</label>
                                                        <input type="password" class="form-control" name="new_password" placeholder="New password">
                                                        <span class="text-danger error-text new_password_error"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="">Confirm New Password</label>
                                                        <input type="password" class="form-control" name="confirm_new_password" placeholder="Retype new password">
                                                        <span class="text-danger error-text confirm_new_password_error"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary">Change password</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
<script>
$(document).ready(function() {
    $('#personal_details_form').on('submit', function(e) {
        var form = this;
        form.submit();
    });
});

let cropper;
const profileImage = document.getElementById('profileImage');
const userFileInput = document.getElementById('user_profile_file');

userFileInput.addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            profileImage.src = e.target.result;
            if (cropper) {
                cropper.destroy();
            }
            cropper = new Cropper(profileImage, {
                aspectRatio: 1,
                viewMode: 1,
                preview: '.ci-avatar-photo',
            });
        };
        reader.readAsDataURL(file);
    }
});

function uploadCroppedImage() {
    cropper.getCroppedCanvas().toBlob((blob) => {
        const formData = new FormData();
        formData.append('user_profile_file', blob);
        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

        $.ajax({
            url: '<?= route_to('update-profile-picture') ?>',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status == 1) {
                    toastr.success(response.msg);
                    profileImage.src = URL.createObjectURL(blob);
                } else {
                    toastr.error(response.msg);
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr);
            }
        });
    });
}

$('#saveProfileButton').on('click', uploadCroppedImage);
$('#change_password_form').on('submit', function(e) {
    e.preventDefault();
    const form = $(this);
    form.find('span.error-text').text('');

    $.ajax({
        url: form.attr('action'),
        method: form.attr('method'),
        data: form.serialize(),
        dataType: 'json',
        success: function(response) {
            if (response.status == 0) {
                $.each(response.errors, function(prefix, val) {
                    form.find(`span.${prefix}_error`).text(val[0]);
                });
            } else {
                form[0].reset();
                toastr.success(response.msg);
            }
        }
    });
});
</script>

<?= $this->endSection() ?>
