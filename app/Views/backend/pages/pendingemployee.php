// app/Views/backend/pages/home.php

<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= route_to('admin.home'); ?>">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Employee Approval List
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<div class="pd-20 card-box mb-30">
    <div class="clearfix mb-20">
        <div class="pull-left">
            <h4 class="text-blue h4">Employee Pending List</h4>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Profile</th>
                    <th scope="col">Name</th>
                    <th scope="col">Address</th>
                    <th scope="col">Birthdate</th>
                    <th scope="col">Email</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($employee)): ?>
                    <?php foreach ($employee as $index => $emp): ?>
                        <?php if ($emp['result'] !== 'Hired'): // Skip employees with status 'Pending' ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td>
                                <a href="#" class="edit-profile-picture-btn" data-id="<?= $emp['id'] ?>">
                                    <img src="<?= $emp['picture'] ? base_url('backend/images/users/' . htmlspecialchars($emp['picture'])) : base_url('backend/images/users/userav-min.png') ?>" alt="Profile Picture" class="avatar-photo ci-avatar-photo" style="width: 50px; height: 50px; border-radius: 50%;">
                                    <i class="icon-copy dw dw-edit-1"></i>
                                </a>
                            </td>

                            <td><?= htmlspecialchars($emp['firstname'] . ' ' . $emp['lastname']) ?></td>
                            <td><?= htmlspecialchars($emp['address']) ?></td>
                            <td><?= htmlspecialchars($emp['dob']) ?></td>
                            <td><?= htmlspecialchars($emp['email']) ?></td>
                            <td>
                            <?php if ($emp['result'] === 'Pending'): ?>
                                <button type="button" class="btn btn-sm btn-success" onclick="updateEmployeeStatus(<?= $emp['id'] ?>)">Hire</button>
                            <?php endif; ?>

									  <button type="button" class="btn btn-sm btn-danger" onclick="deleteEmployee(<?= $emp['id'] ?>)">Reject</button>
                            </td>
                        </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No employees found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<!-- modal -->
 <!-- Employee Modal View -->
 <div class="modal fade" id="viewEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="viewEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewEmployeeModalLabel">View Employee</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-30">
                        <div class="pd-20 card-box height-100-p" style="margin-top: 60px;height: 240px">
                            <div class="profile-photo">
                                <img src="" alt="Employee Picture" id="view_picture" class="avatar-photo ci-avatar-photo" style="width: 150px; height: 150px; border-radius: 50%;">
                            </div>
                            <h5 class="text-center h5 mb-0 ci-user-name" id="view_name"></h5>
                            <p class="text-center text-muted font-14 ci-user-email" id="view_email"></p>
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
                                            <a class="nav-link" data-toggle="tab" href="#educational_background" role="tab">Educational Background</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#interview" role="tab">Interview</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#remarks" role="tab">Remarks</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane fade active show" id="personal_details" role="tabpanel">
                                            <div class="pd-20">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="view_firstname">First Name</label>
                                                            <input type="text" id="view_firstname" class="form-control" readonly>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="view_lastname">Last Name</label>
                                                            <input type="text" id="view_lastname" class="form-control" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="view_phone">Phone Number</label>
                                                            <input type="text" id="view_phone" class="form-control" readonly>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="view_dob">Date of Birth</label>
                                                            <input type="text" id="view_dob" class="form-control" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="view_sex">Sex</label>
                                                            <input type="text" id="view_sex" name="sex" class="form-control" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                            <label for="view_address">Address</label>
                                                            <input type="text" id="view_address" class="form-control" readonly>
                                                        </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="educational_background" role="tabpanel">
                                            <div class="pd-20">
                                                <div class="form-group">
                                                    <label for="view_p_school">Primary School</label>
                                                    <input type="text" id="view_p_school" class="form-control" readonly>
                                                </div>
                                                <div class="form-group">
                                                    <label for="view_s_school">Secondary School</label>
                                                    <input type="text" id="view_s_school" class="form-control" readonly>
                                                </div>
                                                <div class="form-group">
                                                    <label for="view_t_school">Tertiary School</label>
                                                    <input type="text" id="view_t_school" class="form-control" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="interview" role="tabpanel">
                                            <div class="pd-20">
                                                <div class="form-group">
                                                    <label for="view_interview_for">Interview For</label>
                                                    <input type="text" id="view_interview_for" class="form-control" readonly>
                                                </div>
                                                <div class="form-group">
                                                    <label for="view_interview_type">Interview Type</label>
                                                    <input type="text" id="view_interview_type" class="form-control" readonly>
                                                </div>
                                                <div class="form-group">
                                                    <label for="view_interview_date">Interview Date</label>
                                                    <input type="text" id="view_interview_date" class="form-control" readonly>
                                                </div>
                                                <div class="form-group">
                                                    <label for="view_interview_time">Interview Time</label>
                                                    <input type="text" id="view_interview_time" class="form-control" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="remarks" role="tabpanel">
                                            <div class="pd-20">
                                                <div class="form-group">
                                                    <label for="view_behaviour">Behaviour</label>
                                                    <textarea id="view_behaviour" class="form-control" rows="3" readonly></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="view_result">Result</label>
                                                    <input type="text" id="view_result" class="form-control" readonly>
                                                </div>
                                                <div class="form-group">
                                                    <label for="view_comment">Comments</label>
                                                    <textarea id="view_comment" class="form-control" rows="3" readonly></textarea>
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
        </div>
    </div>
</div>

<!-- Update Employee Modal -->
<div class="modal fade" id="editEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="editEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editEmployeeModalLabel">Edit Employee</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="personal-details-tab" data-toggle="tab" href="#edit_personal_details" role="tab" aria-controls="personal-details" aria-selected="true">Personal Details</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="educational-background-tab" data-toggle="tab" href="#edit_educational_background" role="tab" aria-controls="educational-background" aria-selected="false">Educational Background</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="interview-tab" data-toggle="tab" href="#edit_interview" role="tab" aria-controls="interview" aria-selected="false">Interview</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="remarks-tab" data-toggle="tab" href="#edit_remarks" role="tab" aria-controls="remarks" aria-selected="false">Remarks</a>
                    </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <!-- Personal Details Form -->
                    <div class="tab-pane fade show active" id="edit_personal_details" role="tabpanel" aria-labelledby="personal-details-tab">
                        <form id="editPersonalDetailsForm">
                        <?= csrf_field() ?>
                            <input type="hidden" id="update_employee_id_personal" name="id">
                            <div class="pd-20">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="edit_firstname">First Name</label>
                                            <input type="text" id="edit_firstname" name="firstname" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="edit_lastname">Last Name</label>
                                            <input type="text" id="edit_lastname" name="lastname" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="edit_phone">Phone Number</label>
                                            <input type="text" id="edit_phone" name="phone" class="form-control" pattern="\d*" maxlength="11" required title="Phone number should be numeric and up to 11 digits">
                                        </div>
                                        <div class="form-group">
                                            <label for="edit_dob">Date of Birth</label>
                                            <input type="text" id="edit_dob" name="dob" class="form-control date-picker" placeholder="Select Date" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="edit_sex">Sex :</label>
                                    <input type="text" id="edit_sex" name="sex" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="edit_address">Address</label>
                                    <input type="text" id="edit_address" name="address" class="form-control" required>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary" id="updatePersonalDetailsBtn">Update Personal Details</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Educational Background Form -->
                    <div class="tab-pane fade" id="edit_educational_background" role="tabpanel" aria-labelledby="educational-background-tab">
                        <form id="editEducationalBackgroundForm">
                        <?= csrf_field() ?>
                            <input type="hidden" id="update_employee_id_edu" name="id">
                            <div class="pd-20">
                                <div class="form-group">
                                    <label for="edit_p_school">Primary School Attended</label>
                                    <input type="text" id="edit_p_school" name="p_school" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="edit_s_school">Secondary School Attended</label>
                                    <input type="text" id="edit_s_school" name="s_school" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="edit_t_school">Tertiary School Attended</label>
                                    <input type="text" id="edit_t_school" name="t_school" class="form-control" required>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary" id="updateEducationalBackgroundBtn">Update Educational Background</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Interview Form -->
                    <div class="tab-pane fade" id="edit_interview" role="tabpanel" aria-labelledby="interview-tab">
                        <form id="editInterviewForm">
                        <?= csrf_field() ?>
                            <input type="hidden" id="update_employee_id_interview" name="id">
                            <div class="pd-20">
                                <div class="form-group">
                                    <label for="edit_interview_for">Interview For</label>
                                    <input type="text" id="edit_interview_for" name="interview_for" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="edit_interview_type">Interview Type</label>
                                    <select id="edit_interview_type" name="interview_type" class="form-control" required>
                                        <option value="Normal">Normal</option>
                                        <option value="Difficult">Difficult</option>
                                        <option value="Hard">Hard</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="edit_interview_date">Interview Date</label>
                                    <input type="text" id="edit_interview_date" name="interview_date" class="form-control date-picker" placeholder="Select Date" required>
                                </div>
                                <div class="form-group">
                                    <label for="edit_interview_time">Interview Time</label>
                                    <input type="text" id="edit_interview_time" name="interview_time" class="form-control time-picker" placeholder="Select Time" required>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary" id="updateInterviewBtn">Update Interview</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Remarks Form -->
                    <div class="tab-pane fade" id="edit_remarks" role="tabpanel" aria-labelledby="remarks-tab">
                        <form id="editRemarksForm">
                        <?= csrf_field() ?>
                            <input type="hidden" id="update_employee_id_remarks" name="id">
                            <div class="pd-20">
                                <div class="form-group">
                                    <label for="edit_behaviour">Behaviour</label>
                                    <input type="text" id="edit_behaviour" name="behaviour" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="edit_result">Result</label>
                                    <select id="edit_result" name="result" class="form-control" required>
                                        <option value="">Select Result</option>
                                        <option value="Pending">Pending</option>
                                        <option value="Hired">Hired</option>
                                        <option value="Rejected">Rejected</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="edit_comment">Comment</label>
                                    <textarea id="edit_comment" name="comment" class="form-control" rows="3" required></textarea>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary" id="updateRemarksBtn">Update Remarks</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </form>
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
            <form id="editProfilePictureForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" id="update_employee_id_picture" name="id" value="">
                    <div class="form-group">
                        <label for="profile_picture">Upload Profile Picture</label>
                        <input type="file" class="form-control" id="profile_picture" name="profile_picture">
                        <img id="image" style="display:none;"/>
                        <div class="preview" id="preview"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>








<!-- Cropper CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">

<!-- Cropper JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  
<script>
function deleteEmployee(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'You will not be able to recover this employee!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: 'POST',
                url: '<?= route_to('delete_employee') ?>',
                data: {
                    id: id,
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>' // Add CSRF token for CodeIgniter 4
                },
                dataType: 'json',
                success: function(response) {
                    console.log('Success response:', response); // Log the success response

                    if (response.status === 'success') {
                        Swal.fire({
                            title: 'Deleted!',
                            text: response.message,
                            icon: 'success'
                        }).then(() => {
                            location.reload(); // Reload page or update table
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: response.message, // Display the error message from response
                            icon: 'error'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', xhr.responseText); // Log the error response text
                    Swal.fire({
                        title: 'Error!',
                        text: 'An error occurred while processing your request. ' + xhr.responseText,
                        icon: 'error'
                    });
                }
            });
        }
    });
}
</script>
<script>
   function updateEmployeeStatus(employeeId) {
    if (confirm('Are you sure you want to hire this employee?')) {
        fetch(`<?= base_url('admin/hire_employee') ?>/${employeeId}`, {
            method: 'PUT', // Or POST, depending on your setup
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (response.ok) {
                location.reload(); // Reload to see updated list
            } else {
                alert('Failed to update status.');
            }
        })
        .catch(error => console.error('Error updating employee status:', error));
    }
}
</script>




<?= $this->endSection() ?>
