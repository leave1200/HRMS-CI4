
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
                    <a href="javascript:;" class="edit-avatar edit-profile-picture-btn" data-id="<?= get_user()->id; ?>">
                    <a href="javascript:;" class="edit-avatar edit-personal-picture-btn">
                        <i class="fa fa-pencil"></i>
                    </a>
                    </a>
                    <input type="file" name="profile_picture" id="profile_picture" class="d-none" accept="image/*">
                    <img src="<?= get_user()->picture == null ? '/images/users/userav-min.png' : '/images/users/'.get_user()->picture ?>" alt="" class="avatar-photo ci-avatar-photo" id="image">
                </div>

                    <!-- <div class="profile-photo">
                        <a href="javascript:;" onclick="event.preventDefault();document.getElementById('user_profile_file').click();" class="edit-avatar"><i class="fa fa-pencil"></i></a>
                        <input type="file"  name="user_profile_file" id="user_profile_file" class="d-none" style="opacity: 0;">
                        <img src="<?= get_user()->picture == null ? '/images/users/userav-min.png' : '/images/users/'.get_user()->picture ?>" alt="" class="avatar-photo ci-avatar-photo">
                    </div> -->
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

<!-- Modal for Editing Personal Picture -->
<div class="modal fade" id="editPersonalPictureModal" tabindex="-1" aria-labelledby="editPersonalPictureModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPersonalPictureModalLabel">Edit Personal Picture</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- File input for uploading the image -->
                <input type="file" name="user_profile_file" id="user_profile_file" accept="image/*" style="display: block;">
                <!-- Preview of the selected image -->
                <img id="personal-image-preview" src="" style="max-width: 100%; display: none;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="savePersonalPicture">Save changes</button>
            </div>
        </div>
    </div>
</div>
        <script>
$(document).ready(function() {
    var cropper;
    var $image = $('#image');
    var $preview = $('#preview');

    // Handle edit button clicks for profile picture
    $('.edit-profile-picture-btn').on('click', function() {
        var id = $(this).data('id');
        $('#update_employee_id_picture').val(id);
        $('#editProfilePictureModal').modal('show');
    });

    // Handle file input change
    $('#profile_picture').on('change', function(event) {
        var files = event.target.files;
        var done = function(url) {
            $image.attr('src', url).show();
            $preview.show();
            cropper = new Cropper($image[0], {
                aspectRatio: 1,
                viewMode: 1,
                preview: '.preview'
            });
        };
        var reader;
        var file;

        if (files && files.length > 0) {
            file = files[0];

            if (URL) {
                done(URL.createObjectURL(file));
            } else if (FileReader) {
                reader = new FileReader();
                reader.onload = function() {
                    done(reader.result);
                };
                reader.readAsDataURL(file);
            }
        }
    });

    // Handle form submission
    $('#editProfilePictureForm').on('submit', function(e) {
        e.preventDefault();

        var canvas;
        var croppedImage;

        if (cropper) {
            canvas = cropper.getCroppedCanvas({
                width: 500,
                height: 500,
            });

            canvas.toBlob(function(blob) {
                var formData = new FormData();
                formData.append('profile_picture', blob);
                formData.append('id', $('#update_employee_id_picture').val());

                $.ajax({
                    type: 'POST',
                    url: '<?= route_to('update-profile-picture') ?>', // Ensure this URL matches your route
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
                                location.reload(); // Reload page or update table
                            });
                            $('#editProfilePictureModal').modal('hide');
                            $('.avatar-photo').attr('src', response.new_picture_url);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message,
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
            }, 'image/png');
        }
    });
});
</script>


<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
           $('#personal_details_from').on('submit', function(e) {
            e.preventDefault();
            var form = this;
            var formdata = new FormData(form);

            // Perform validation if needed
           $ajax({
                url:$(form).attr('action'),
                method:$(form).attr('method'),
                data:formdatata,
                processData:false,
                dataType:'json',
                ontentType:false,
                beforeSend:function(){
                    toastr.remove();
                    $(form).find('span.error-text').text('');
                },
                success:function(response){
                    if( $.isEmptyObject(response.error) ){
                        if( response.status == 1 ){
                            $('.ci-user-name').each(function(){
                                $(this).html(response.user_info.name);
                            });
                            toastr.success(response.msg);
                    }else{
                        toastr.error(response.msg);
                    }
                    }else{
                        $.each(response.error, function(prefix, val){
                            $(form).find('span.'+prefix+'_error').text(val);
                        });
                    }
                }
            });
        });


    // $('#user_profile_file').ijaboCropTool({
    //     preview: '.ci-avatar-photo',
    //     setRatio: 1,
    //     allowedExtensions: ['jpg', 'jpeg', 'png'],
    //     processUrl:'<?= route_to('update-profile-picture') ?>',
    //     withCSRF: ['<?= csrf_token() ?>', '<?= csrf_hash() ?>'],
    //     onSuccess:function(responseText, element, status) {
    //         if( status == 1 ) {
    //             toastr.success('message');
    //         } else {
    //             toastr.error('message');
    //         }
    //     },
    //     onError: function(message, element, status) {
    //         alert(message);
    //     }
    // });

$('#change_password_form').on('submit', function(e){
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

