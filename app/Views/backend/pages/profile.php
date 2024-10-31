
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
                        <img src="<?= get_user()->picture ? base_url('images/users/' . htmlspecialchars(get_user()->picture)) : base_url('images/users/userav-min.png') ?>" 
                            alt="Profile Photo" 
                            class="avatar-photo ci-avatar-photo" 
                            style="width: 150px; height: 150px; border-radius: 30%;">
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
    </div>
</div>

<!-- Edit Profile Picture Modal -->
<div class="modal fade" id="editProfilePictureModal" tabindex="-1" role="dialog" aria-labelledby="editProfilePictureModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProfilePictureModalLabel">Edit Profile Picture</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="file" id="profile_picture_input" accept="image/*">
                <img id="profile_picture" src="" alt="Profile Picture" style="max-width: 100%; display:none;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="uploadProfilePicture" class="btn btn-primary">Upload</button>
            </div>
        </div>
    </div>
</div>


<link rel="stylesheet" href="https://unpkg.com/cropperjs/dist/cropper.min.css">
<script src="https://unpkg.com/cropperjs/dist/cropper.min.js"></script>


<script>
$(document).ready(function() {
    var cropper;

    // Handle edit button clicks for profile picture
    $('.edit-profile-picture-btn').on('click', function() {
        var id = $(this).data('id');
        $('#update_user_id_picture').val(id); // Store the user ID in the appropriate input
        $('#editProfilePictureModal').modal('show');
    });

    // Handle the file input change event to load and crop the image
    $('#profile_picture_input').on('change', function(event) {
        var files = event.target.files;
        var done = function(url) {
            $('#profile_picture').attr('src', url);
        };

        if (files && files.length > 0) {
            var reader = new FileReader();
            reader.onload = function(e) {
                done(reader.result);
            };
            reader.readAsDataURL(files[0]);

            // Once the image is loaded, initialize the cropper
            $('#profile_picture').on('load', function() {
                cropper = new Cropper(this, {
                    aspectRatio: 1,
                    viewMode: 1,
                });
            });
        }
    });

    $('#uploadProfilePicture').on('click', function() {
        var formData = new FormData();
        var userId = $('#update_user_id_picture').val();
        var fileInput = $('#profile_picture')[0];

        if (fileInput.files.length > 0) {
            formData.append('profile_picture', fileInput.files[0]);
            formData.append('id', userId); // Add user ID to the form data

            $.ajax({
                type: 'POST',
                url: '<?= route_to('update-profile-picture') ?>',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log('Response:', response); // Log the response
                    if (response.status == 1) {
                        // Update the profile picture displayed on the page
                        $('.avatar-photo').attr('src', '/images/users/' + response.new_picture_name + '?t=' + new Date().getTime()); // Append timestamp to avoid caching
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.msg,
                        }).then(() => {
                            $('#editProfilePictureModal').modal('hide');
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.msg,
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON ? xhr.responseJSON.message : 'An error occurred',
                    });
                }
            });
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Warning',
                text: 'Please select a file to upload.',
            });
        }
    });
});

</script>





<script>
    /$('#change_password_form').on('submit', function(e){
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
        beforeSend: function(){
            toastr.remove();
            $(form).find('span.error-text').text('');
        },
        success: function(response){
            if (response.trim() === 'success') {
                $(form)[0].reset();
                toastr.success('Password has been changed successfully.');
            } else {
                // If the response contains an error message, display it
                toastr.error(response);
            }
        },
        error: function(xhr, status, error){
            toastr.error('An error occurred. Please try again.');
            console.error('Error:', error);
        }
    });
});
</script>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<?= $this->endSection() ?> 

