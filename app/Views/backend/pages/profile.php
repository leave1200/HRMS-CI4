
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

                                    <form action="<?= route_to('update-personal-details'); ?>" method="POST" id="personal_details_form" enctype="multipart/form-data">
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
                                            <label for="">Profile Picture</label>
                                            <input type="file" name="picture" id="user_profile_file" class="form-control" accept=".jpg, .jpeg, .png">
                                            <span class="text-danger error-text picture_error"></span>
                                        </div>

                                        <div class="form-group">
                                            <label>Preview:</label>
                                            <div class="ci-avatar-photo">
                                                <img id="preview_image" src="<?= get_user()->picture ? base_url(get_user()->picture) : 'path/to/default/image.jpg' ?>" alt="Profile Picture" style="width: 100px; height: 100px; object-fit: cover;"/>
                                            </div>
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
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="backend/extra-assets/ijaboCropTool/ijaboCropTool.min.js"></script>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        $('#personal_details_form').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission
        var form = this;

        // Perform validation if needed
        var valid = true;

        // Example validation
        var name = $('input[name="name"]').val().trim();
        var username = $('input[name="username"]').val().trim();

        if (!name) {
            valid = false;
            $('.name_error').text('Name is required.');
        } else {
            $('.name_error').text('');
        }

        if (!username) {
            valid = false;
            $('.username_error').text('Username is required.');
        } else {
            $('.username_error').text('');
        }

        // Validate if an image file is selected
        var pictureFile = $('input[name="picture"]').val();
        if (!pictureFile) {
            valid = false;
            $('.picture_error').text('Please upload a profile picture.');
        } else {
            $('.picture_error').text('');
        }

        if (valid) {
            // Create a FormData object to handle the form submission
            var formData = new FormData(form);

            // Use AJAX to submit the form
            $.ajax({
                url: $(form).attr('action'), // URL to send the request
                type: $(form).attr('method'), // HTTP method
                data: formData, // Form data
                contentType: false, // Do not set any content header
                processData: false, // Do not process the data
                success: function(response) {
                    // Handle success (you can modify this based on your response structure)
                    if (response.success) {
                        toastr.success(response.message);
                        // Optionally, refresh or redirect the page
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    // Handle error
                    toastr.error('An error occurred while submitting the form.');
                }
            });
        }
    });
    });


    $('#user_profile_file').ijaboCropTool({
    preview: '.ci-avatar-photo',
    setRatio: 1,
    allowedExtensions: ['jpg', 'jpeg', 'png'],
    processUrl: '<?= route_to('update-profile-picture') ?>',
    withCSRF: ['<?= csrf_token() ?>', '<?= csrf_hash() ?>'],
    onSuccess:function(responseText, element, status) {
        if( status == 1 ) {
            toastr.success('message');
        } else {
            toastr.error('message');
        }
    },
    onError: function(message, element, status) {
        alert(message);
    }
});

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

