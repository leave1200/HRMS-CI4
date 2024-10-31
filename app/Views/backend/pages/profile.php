
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
                            <li class="breadcrumb-item active" aria-current="page">
                                Profile
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-30">
                <div class="pd-20 card-box height-100-p">
                    <div class="profile-photo">
                        <a href="javascript:;" onclick="event.preventDefault();document.getElementById('user_profile_file').click();" class="edit-avatar"><i class="fa fa-pencil"></i></a>
                        <input type="file"  name="user_profile_file" id="user_profile_file" class="d-none" style="opacity: 0;">
                        <img src="<?= get_user()->picture == null ? '/images/users/userav-min.png' : '/images/users/'.get_user()->picture ?>" alt="" class="avatar-photo ci-avatar-photo">
                    </div>
                    <h5 class="text-center h5 mb-0 ci-user-name"><?= get_user()->name ?></h5>
                    <p class="text-center text-muted font-14 ci-user-email"><?= get_user()->email ?></p>
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
                                            <div class="alert alert-success">
                                                <?= session('success') ?>
                                            </div>
                                        <?php endif ?>

                                        <?php if (session()->has('error')): ?>
                                            <div class="alert alert-danger">
                                                <?= session('error') ?>
                                            </div>
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
                                                <textarea name="bio" id="" cols="30" rows="10" class="form-control" placeholder="Bio....."><?= old('bio', get_user()->bio) ?></textarea>
                                                <span class="text-danger error-text bio_error"></span>
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary">
                                                    Save changes
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="change_password" role="tabpanel">
                                    <div class="pd-20 profile-task-wrap">
                                    <?php if (session()->has('errors')): ?>
                                        <div class="alert alert-danger">
                                            <?php foreach (session('errors') as $error): ?>
                                                <p><?= $error ?></p>
                                            <?php endforeach ?>
                                        </div>
                                    <?php endif ?>

                                    <?php if (session()->has('success')): ?>
                                        <div class="alert alert-success">
                                            <?= session('success') ?>
                                        </div>
                                    <?php endif ?>

                                    <form action="<?= route_to('change-password') ?>" method="POST" id="change_password_form">
                                        <?= csrf_field(); ?>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">Current Password</label>
                                                    <input type="password" class="form-control" placeholder="Enter current password" name="current_password" value="<?= old('current_password') ?>">
                                                    <span class="text-danger error-text current_password_error"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">New Password</label>
                                                    <input type="password" class="form-control" placeholder="New password" name="new_password" value="<?= old('new_password') ?>">
                                                    <span class="text-danger error-text new_password_error"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">Confirm new Password</label>
                                                    <input type="password" class="form-control" placeholder="Retype new password" name="confirm_new_password" value="<?= old('confirm_new_password') ?>">
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
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    var cropper;
    var $image = $('#user_profile_file');
    var $preview = $('.ci-avatar-photo');

    // Handle file input change
    $('#user_profile_file').on('change', function(event) {
        var files = event.target.files;
        var done = function(url) {
            $image.attr('src', url).show();
            cropper = new Cropper($image[0], {
                aspectRatio: 1,
                viewMode: 1,
                preview: '.ci-avatar-photo'
            });
        };
        var reader, file;

        if (files && files.length > 0) {
            file = files[0];

            if (URL) {
                done(URL.createObjectURL(file));
            } else if (FileReader) {
                reader = new FileReader();
                reader.onload = function(e) {
                    done(e.target.result);
                };
                reader.readAsDataURL(file);
            }
        }
    });

    // Handle form submission for profile picture update
    $('#editProfilePictureForm').on('submit', function(e) {
        e.preventDefault();

        if (cropper) {
            var canvas = cropper.getCroppedCanvas({
                width: 500,
                height: 500,
            });

            canvas.toBlob(function(blob) {
                var formData = new FormData();
                formData.append('profile_picture', blob);
                formData.append('id', $('#update_employee_id_picture').val());

                $.ajax({
                    type: 'POST',
                    url: 'update_profile_picture', // Adjust URL as needed
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message,
                            }).then(() => {
                                location.reload(); // Refresh to show updated picture
                            });
                            $('#editProfilePictureModal').modal('hide');
                            $preview.attr('src', response.new_picture_url);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message,
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON ? xhr.responseJSON.message : 'An error occurred',
                        });
                    }
                });
            }, 'image/png');
        }
    });

    // Handle password change form submission
    $('#change_password_form').on('submit', function(e) {
        e.preventDefault();
        var csrfName = $('.ci_csrf_data').attr('name');
        var csrfHash = $('.ci_csrf_data').val();
        var form = this;
        var formData = new FormData(form);
        formData.append(csrfName, csrfHash);

        $.ajax({
            url: $(form).attr('action'),
            method: $(form).attr('method'),
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            beforeSend: function() {
                toastr.remove();
                $(form).find('span.error-text').text('');
            },
            success: function(response) {
                if (response.trim() === 'success') {
                    $(form)[0].reset();
                    toastr.success('Password has been changed successfully.');
                } else {
                    toastr.error(response);
                }
            },
            error: function(xhr, status, error) {
                toastr.error('An error occurred. Please try again.');
                console.error('Error:', error);
            }
        });
    });
});
</script>

<?= $this->endSection() ?> 

