
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
                    <!-- Trigger Button -->
                    <div class="profile-photo">
                        <a href="javascript:;" class="edit-profile-picture-btn" data-id="<?= get_user()->id ?>">
                            <img src="<?= empty(get_user()->picture) ? '/images/users/userav-min.png' : '/images/users/' . get_user()->picture ?>" alt="Profile Photo" class="avatar-photo ci-avatar-photo" style="width: 150px; height: 150px; border-radius: 30%;">
                            <i class="fa fa-pencil edit-icon" aria-hidden="true"></i>
                        </a>
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
                                                        <label for="name">Name</label>
                                                        <input type="text" name="name" class="form-control" placeholder="Enter full name" value="<?= old('name', get_user()->name) ?>">
                                                        <span class="text-danger error-text name_error" id="name"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="username">Username</label>
                                                        <input type="text" name="username" class="form-control" placeholder="Enter Username" value="<?= old('username', get_user()->username) ?>">
                                                        <span class="text-danger error-text username_error" id="username"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="bio">Bio</label>
                                                <textarea name="bio" id="bio" cols="30" rows="10" class="form-control" placeholder="Bio....."><?= old('bio', get_user()->bio) ?></textarea>
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
                                                        <label for="current_password">Current Password</label>
                                                        <input type="password" class="form-control" placeholder="Enter current password" name="current_password" value="<?= old('current_password') ?>">
                                                        <span class="text-danger error-text current_password_error"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="new_password">New Password</label>
                                                        <input type="password" class="form-control" placeholder="New password" name="new_password" value="<?= old('new_password') ?>">
                                                        <span class="text-danger error-text new_password_error"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="confirm_new_password">Confirm New Password</label>
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
    </div>
</div>


<div class="modal fade" id="editProfilePictureModal" tabindex="-1" role="dialog" aria-labelledby="editProfilePictureModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="editProfilePictureForm" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProfilePictureModalLabel">Edit Profile Picture</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="update_user_id_picture" name="id" value="<?= get_user()->id ?>">
                    <input type="file" id="profile_picture" name="profile_picture" accept="image/*" required>
                    <div class="preview" style="width: 100%; overflow: hidden; display:none;">
                        <img id="image" src="" alt="Preview" style="max-width: 100%;"/>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Cropper CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">

<!-- Cropper JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>

<script>
$(document).ready(function() {
    let cropper;
    
    // Show modal and initialize cropper when a profile picture is chosen
    $('#profile_picture').change(function(event) {
        const files = event.target.files;
        const done = (url) => {
            $('#image').attr('src', url);
        };
        
        if (files && files.length > 0) {
            const reader = new FileReader();
            reader.onload = function(e) {
                done(e.target.result);
            };
            reader.readAsDataURL(files[0]);
        }
        
        $('.preview').show();
        
        if (cropper) {
            cropper.destroy();
        }
        
        const image = document.getElementById('image');
        cropper = new Cropper(image, {
            aspectRatio: 1,
            viewMode: 1,
            autoCropArea: 1,
            responsive: true,
            background: false,
            modal: true,
        });
    });

    $('#editProfilePictureForm').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        // Get cropped canvas data
        const canvas = cropper.getCroppedCanvas({
            width: 150,
            height: 150,
        });

        // Convert canvas to blob
        canvas.toBlob(function(blob) {
            const formData = new FormData();
            formData.append('id', $('#update_user_id_picture').val());
            formData.append('profile_picture', blob, 'profile.jpg'); // Change the file name if needed

            $.ajax({
                url: '<?= route_to('update-profile-picture') ?>',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    var res = JSON.parse(response);
                    if (res.status === 1) {
                        alert(res.msg);
                        // Update the profile picture on the main page if needed
                        $('.avatar-photo').attr('src', '<?= empty(get_user()->picture) ? "/images/users/userav-min.png" : "/images/users/" . get_user()->picture ?>' + res.new_picture_name);
                    } else {
                        alert(res.msg);
                    }
                },
                error: function() {
                    alert('An error occurred while updating the profile picture.');
                }
            });
        });
    });
});
</script>
<script>

    // Handle change password form submission
    $('#change_password_form').on('submit', function(e) {
        e.preventDefault();
        
        // CSRF hash
        var csrfName = $('.ci_csrf_data').attr('name');
        var csrfHash = $('.ci_csrf_data').val();
        var form = this;
        var formdata = new FormData(form);
        formdata.append(csrfName, csrfHash);

        $.ajax({
            url: $(form).attr('action'),
            method: $(form).attr('method'),
            data: formdata,
            processData: false,
            contentType: false,
            cache: false,
            beforeSend: function() {
                toastr.remove();
                $(form).find('span.error-text').text(''); // Clear previous errors
            },
            success: function(response) {
                if (response.trim() === 'success') {
                    $(form)[0].reset();
                    toastr.success('Password has been changed successfully.');
                } else {
                    // If the response contains an error message, display it
                    toastr.error(response);
                }
            },
            error: function(xhr, status, error) {
                toastr.error('An error occurred. Please try again.');
                console.error('Error:', error);
            }
        });
    });
</script>


<?= $this->endSection() ?> 

