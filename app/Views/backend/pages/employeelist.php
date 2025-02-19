// app/Views/backend/pages/home.php

<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<div class="page-header">
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="title">
                <h4>Employee List</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= route_to('admin.home'); ?>">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Employee List
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<button onclick="printTable()" class="btn btn-primary"><i class="icon-copy fi-print">Print</i></i></button>
<button onclick="exportToCSV()" class="btn btn-primary"><i class="icon-copy fi-page-csv">CSV</i></button>
<button class="btn btn-primary" onclick="exportToExcel()"><i class="icon-copy fi-page-export-doc">Excel</i></button>
<div class="pd-20 card-box mb-30">
    <div class="clearfix mb-20">
        <div class="pull-left">
            <h4 class="text-blue h4">Employee List</h4>
        </div>
        <!-- <div class="mb-10 pull-right">
            <input type="text" id="searchInput" placeholder="Search by Name" onkeyup="filterTable()" class="form-control">
        </div> -->
    </div>
    <div class="table-responsive">
        <table class="table table-striped" id="DataTables_Table_0">
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
                        <?php if ($emp['result'] !== 'Pending'): ?>
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
                                    <button class="btn btn-info view-btn" data-id="<?= $emp['id'] ?>">View</button>
                                    <button class="btn btn-primary edit-employee-btn"
                                        data-id="<?= $emp['id'] ?>"
                                        data-firstname="<?= htmlspecialchars($emp['firstname']) ?>"
                                        data-lastname="<?= htmlspecialchars($emp['lastname']) ?>"
                                        data-phone="<?= htmlspecialchars($emp['phone']) ?>"
                                        data-dob="<?= htmlspecialchars($emp['dob']) ?>"
                                        data-age="<?= htmlspecialchars($emp['age']) ?>"
                                        data-sex="<?= htmlspecialchars($emp['sex']) ?>"
                                        data-address="<?= htmlspecialchars($emp['address']) ?>"
                                        data-p-school="<?= htmlspecialchars($emp['p_school']) ?>"
                                        data-s-school="<?= htmlspecialchars($emp['s_school']) ?>"
                                        data-t-school="<?= htmlspecialchars($emp['t_school']) ?>"
                                        data-interview-for="<?= htmlspecialchars($emp['interview_for']) ?>"
                                        data-interview-type="<?= htmlspecialchars($emp['interview_type']) ?>"
                                        data-interview-date="<?= htmlspecialchars($emp['interview_date']) ?>"
                                        data-interview-time="<?= htmlspecialchars($emp['interview_time']) ?>"
                                        data-behaviour="<?= htmlspecialchars($emp['behaviour']) ?>"
                                        data-result="<?= htmlspecialchars($emp['result']) ?>"
                                        data-comment="<?= htmlspecialchars($emp['comment']) ?>"
                                        data-toggle="modal"
                                        data-target="#editEmployeeModal">
                                        Edit
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="deleteEmployee(<?= $emp['id'] ?>)">Delete</button>
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
                                                </div>
                                                <div class="form-group">
                                                            <label for="view_age">Age</label>
                                                            <input type="text" id="view_age" class="form-control" readonly>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="view_sex">Sex</label>
                                                            <input type="text" id="view_sex" name="sex" class="form-control" readonly>
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
                                            <input type="text" id="edit_firstname" name="firstname" class="form-control" oninput="validateDesignation(this)" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="edit_lastname">Last Name</label>
                                            <input type="text" id="edit_lastname" name="lastname" class="form-control" oninput="validateDesignation1(this)" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="edit_phone">Phone Number</label>
                                            <input type="text" id="edit_phone" name="phone" class="form-control" pattern="\d*" maxlength="11" required title="Phone number should be numeric and up to 11 digits">
                                        </div>
                                        <div class="form-group">
                                            <label for="edit_dob">Date of Birth</label>
                                            <input type="date" id="edit_dob" name="dob" class="form-control dob-input" placeholder="Select Date" onchange="calculateAge()" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                                <label for="edit_age" class="col-sm-12 col-md-2 col-form-label">Age</label>
                                                    <input class="form-control" type="text" id="edit_age" name="age" readonly required>
                                            </div>
                                <div class="form-group">
                                    <label for="edit_sex">Sex :</label>
                                    <input type="text" id="edit_sex" name="sex" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="edit_address">Address</label>
                                    <input type="text" id="edit_address" name="address" class="form-control" oninput="validateDesignation2(this)" required>
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
                                    <input type="text" id="edit_p_school" name="p_school" class="form-control" oninput="validateDesignation(this)" required>
                                </div>
                                <div class="form-group">
                                    <label for="edit_s_school">Secondary School Attended</label>
                                    <input type="text" id="edit_s_school" name="s_school" class="form-control" oninput="validateDesignation(this)" required>
                                </div>
                                <div class="form-group">
                                    <label for="edit_t_school">Tertiary School Attended</label>
                                    <input type="text" id="edit_t_school" name="t_school" class="form-control" oninput="validateDesignation(this)" required>
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
                                    <input type="text" id="edit_interview_for" name="interview_for" class="form-control" oninput="validateDesignation(this)" required>
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
                                    <input type="text" id="edit_behaviour" name="behaviour" class="form-control" oninput="validateDesignation(this)" required>
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
                                    <textarea id="edit_comment" name="comment" class="form-control" rows="3" oninput="validateDesignation(this)" required></textarea>
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
                        <input type="file" class="form-control" id="profile_picture" name="profile_picture" accept="image/*" required>
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
<script>
document.getElementById('profile_picture').addEventListener('change', function() {
    const fileInput = this;
    const file = fileInput.files[0];
    const maxSize = 1 * 1024 * 1024; // 10 MB

    // Check if a file is selected
    if (file) {
        // Validate file type (only images)
        const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!validTypes.includes(file.type)) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid file type',
                text: 'Please select an image file (JPEG, PNG, GIF, WEBP).',
            });
            fileInput.value = ''; // Clear the input
            return;
        }

        // Validate file size
        if (file.size > maxSize) {
            Swal.fire({
                icon: 'error',
                title: 'File too large',
                text: 'The file size must not exceed 1MB.',
            });
            fileInput.value = ''; // Clear the input
            return;
        }
    }
});
</script>

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
	$(document).ready(function() {
    // Handle view button clicks
    $('.view-btn').on('click', function() {
        var id = $(this).data('id');

        $.ajax({
            type: 'POST',
            url: '<?= route_to('employee_view') ?>',
			data: { id: id },
            dataType: 'json',
            success: function(response) {
				if(response) {
				$('#view_picture').attr('src', response.picture ? '<?= base_url('backend/images/users/') ?>' + response.picture : '<?= base_url('backend/images/users/employee.png') ?>');
                $('#view_firstname').val(response.firstname);
				$('#view_lastname').val(response.lastname);
                $('#view_phone').val(response.phone);
                $('#view_email').val(response.email);
                $('#view_dob').val(response.dob);
                $('#view_age').val(response.age);
                $('#view_sex').val(response.sex);
                $('#view_address').val(response.address);
                $('#view_p_school').val(response.p_school);
                $('#view_s_school').val(response.s_school);
                $('#view_t_school').val(response.t_school);
                $('#view_interview_for').val(response.interview_for);
                $('#view_interview_type').val(response.interview_type);
                $('#view_interview_date').val(response.interview_date);
                $('#view_interview_time').val(response.interview_time);
                $('#view_behaviour').val(response.behaviour);
                $('#view_result').val(response.result);
                $('#view_comment').val(response.comment);
                $('#view_sex').val(response.sex);
            
                $('#viewEmployeeModal').modal('show');
			} else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No data found for this employee.'
                        });
                    }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while fetching the employee data.'
                });
            }
        });
    });
});
</script>   
<script>
function printTable() {
    // Open a new window or tab for printing
    var printWindow = window.open('', '', 'height=600,width=800');

    // Clone the table without the pagination controls and search bar
    var table = document.querySelector('.table-responsive').innerHTML;
    var clonedTable = document.createElement('div');
    clonedTable.innerHTML = table;

    // Remove the Action column from the cloned table
    var headers = clonedTable.querySelectorAll('thead th');
    var rows = clonedTable.querySelectorAll('tbody tr');

    if (headers.length > 0) {
        headers[headers.length - 1].style.display = 'none'; // Hide the Action header
    }

    rows.forEach(row => {
        row.cells[row.cells.length - 1].style.display = 'none'; // Hide the Action cell
    });

    // Remove pagination controls (if any)
    var pagination = clonedTable.querySelector('.dataTables_paginate');
    if (pagination) {
        pagination.style.display = 'none'; // Hide pagination
    }

    // Remove the entries info and search bar
    var entriesInfo = clonedTable.querySelector('.dataTables_info');
    var searchBox = clonedTable.querySelector('.dataTables_filter');

    if (entriesInfo) {
        entriesInfo.style.display = 'none'; // Hide entries information
    }

    if (searchBox) {
        searchBox.style.display = 'none'; // Hide search box
    }

    // Write the modified content to the new window
    printWindow.document.write('<html><head><title>Print Employee Table</title>');
    printWindow.document.write('<style>body{font-family: Arial, sans-serif;}');
    printWindow.document.write('table{width: 100%; border-collapse: collapse;}');
    printWindow.document.write('th, td{border: 1px solid #ddd; padding: 8px;}');
    printWindow.document.write('th{background-color: #f2f2f2;}');
    
    // Add print-specific styles to handle page breaks and prevent table cut-off
    printWindow.document.write('@media print {');
    printWindow.document.write('  body { margin: 0; padding: 0; }');
    printWindow.document.write('  table { page-break-before: auto; page-break-after: auto; page-break-inside: auto; }');
    printWindow.document.write('  tr { page-break-inside: avoid; page-break-after: auto; }');
    printWindow.document.write('  thead { display: table-header-group; }');
    printWindow.document.write('  tfoot { display: table-footer-group; }');
    printWindow.document.write('}');
    printWindow.document.write('</style>');
    printWindow.document.write('</head><body>');
    printWindow.document.write(clonedTable.innerHTML);
    printWindow.document.write('</body></html>');

    // Close the document and trigger the print action
    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
}
</script>


<script>
function exportToCSV() {
    var table = document.querySelector('.table-responsive table');
    var csv = [];
    var rows = table.querySelectorAll('tr');

    // Loop through rows and cells to create CSV
    for (var i = 0; i < rows.length; i++) {
        var row = [], cols = rows[i].querySelectorAll('td, th');

        for (var j = 0; j < cols.length; j++) {
            // Exclude the last column (Action column)
            if (j !== cols.length - 1) {
                row.push('"' + cols[j].innerText.replace(/"/g, '""') + '"');
            }
        }

        csv.push(row.join(','));
    }

    // Create a CSV Blob and trigger download
    var csvFile = new Blob([csv.join('\n')], { type: 'text/csv' });
    var downloadLink = document.createElement('a');
    downloadLink.download = 'employees.csv';
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.click();
}
</script>
<script>
function exportToExcel() {
    var table = document.querySelector('.table-responsive table');
    
    // Remove the "Action" column header and data
    var headers = table.querySelectorAll('thead th');
    var rows = table.querySelectorAll('tbody tr');

    // Find the index of the "Action" column (assuming it's the last column)
    var actionIndex = headers.length - 1;

    // Find the index of the "Profile" column (assuming it's the second last column)
    var profileIndex = headers.length - 6;

    // Remove "Action" column from header and body
    headers[actionIndex].remove();  // Remove Action column from header
    rows.forEach(function(row) {
        row.deleteCell(actionIndex); // Remove Action column from each row
    });

    // Remove "Profile" column from header and body
    headers[profileIndex].remove();  // Remove Profile column from header
    rows.forEach(function(row) {
        row.deleteCell(profileIndex); // Remove Profile column from each row
    });

    // Convert the table to a workbook and export to Excel
    var wb = XLSX.utils.table_to_book(table, { sheet: "Sheet1" });

    // Convert the workbook to binary and create a downloadable link
    var wbout = XLSX.write(wb, { bookType: 'xlsx', type: 'array' });
    var blob = new Blob([wbout], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
    var url = URL.createObjectURL(blob);

    // Create a download link and trigger the download
    var a = document.createElement('a');
    a.href = url;
    a.download = 'employees.xlsx';
    a.click();
    URL.revokeObjectURL(url);

    // Optionally, restore the columns' visibility (in case you want to retain them for the webpage)
    // This step is optional, but you can restore the columns if needed
    // headers[actionIndex].style.display = '';
    // rows.forEach(function(row) {
    //     row.cells[actionIndex].style.display = '';
    // });
    // headers[profileIndex].style.display = '';
    // rows.forEach(function(row) {
    //     row.cells[profileIndex].style.display = '';
    // });
}
</script>


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
                    url: 'update_profile_picture', // Ensure this URL matches your route
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
});


</script>
<script>
    $(document).ready(function() {
        // Populate modal with employee data
        $(document).on('click', '.edit-employee-btn', function() {
            let employeeId = $(this).data('id');
            $('#update_employee_id_personal').val(employeeId);
            $('#update_employee_id_edu').val(employeeId);
            $('#update_employee_id_interview').val(employeeId);
            $('#update_employee_id_remarks').val(employeeId);

            // Personal Details
            $('#edit_firstname').val($(this).data('firstname'));
            $('#edit_lastname').val($(this).data('lastname'));
            $('#edit_phone').val($(this).data('phone'));
            $('#edit_dob').val($(this).data('dob'));
            $('#edit_age').val($(this).data('age'));
            $('#edit_sex').val($(this).data('sex'));
            $('#edit_address').val($(this).data('address'));

            // Educational Background
            $('#edit_p_school').val($(this).data('p-school'));
            $('#edit_s_school').val($(this).data('s-school'));
            $('#edit_t_school').val($(this).data('t-school'));

            // Interview
            $('#edit_interview_for').val($(this).data('interview-for'));
            $('#edit_interview_type').val($(this).data('interview-type'));
            $('#edit_interview_date').val($(this).data('interview-date'));
            $('#edit_interview_time').val($(this).data('interview-time'));

            // Remarks
            $('#edit_behaviour').val($(this).data('behaviour'));
            $('#edit_result').val($(this).data('result'));
            $('#edit_comment').val($(this).data('comment'));
        });

        // Function to handle form submissions
        function handleFormSubmission(formId, url, successMessage) {
            $(formId).on('submit', function(event) {
                event.preventDefault();

                let formData = $(this).serialize();

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: successMessage,
                            }).then(() => {
                                $('#editEmployeeModal').modal('hide'); // Close modal on success
                                location.reload(); // Reload the page to reflect changes
                            });
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
                            text: xhr.responseJSON ? xhr.responseJSON.message : 'An error occurred while processing your request.',
                        });
                    }
                });
            });
        }

        // Initialize form submission handlers
        handleFormSubmission('#editPersonalDetailsForm', '<?= route_to('update_personal_details') ?>', 'Personal details updated successfully.');
        handleFormSubmission('#editEducationalBackgroundForm', '<?= route_to('update_educational_background') ?>', 'Educational background updated successfully.');
        handleFormSubmission('#editInterviewForm', '<?= route_to('update_interview') ?>', 'Interview details updated successfully.');
        handleFormSubmission('#editRemarksForm', '<?= route_to('update_remarks') ?>', 'Remarks updated successfully.');

        // Date picker initialization
        $('.date-picker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });

        // Time picker initialization
        $('.time-picker').timepicker({
            showMeridian: false,
            showSeconds: true,
            defaultTime: false
        });
    });
</script>
<script>
function filterTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const rows = document.querySelectorAll('#DataTables_Table_0 tbody tr');

    rows.forEach(row => {
        const nameCell = row.cells[2]; // Assuming the Name is the third column
        if (nameCell) {
            const txtValue = nameCell.textContent || nameCell.innerText;
            row.style.display = txtValue.toLowerCase().includes(filter) ? "" : "none";
        }
    });
}
</script>

<script>
function calculateAge() {
    const dobInput = document.getElementById("edit_dob").value;

    if (!dobInput) {
        document.getElementById("edit_age").value = ""; // Clear age if no date is selected
        return;
    }

    const dob = new Date(dobInput);
    const today = new Date();

    let age = today.getFullYear() - dob.getFullYear();
    const monthDifference = today.getMonth() - dob.getMonth();

    if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < dob.getDate())) {
        age--;
    }

    document.getElementById("edit_age").value = age;
}
</script>
<script>
    // Get the current date
    const today = new Date();
    const currentYear = today.getFullYear();
    
    // Set the minimum and maximum dates
    document.getElementById('edit_dob').setAttribute('min', '1985-01-01');
    document.getElementById('edit_dob').setAttribute('max', '2002-12-31');
</script>
<script>
function validateDesignation(input) {
    // Replace invalid characters
    input.value = input.value.replace(/[^A-Za-z\s]/g, '');

    // Trim whitespace and check if input is empty
    if (input.value.trim() === '') {
        input.setCustomValidity('Please enter a valid text.'); // Set custom validity message
    } else {
        input.setCustomValidity(''); // Clear the custom validity message
    }
}
</script>
<script>
function validateDesignation2(input) {
    // Replace invalid characters
    input.value = input.value.replace(/[^A-Za-z0-9\s,.]/g, '');

    // Trim whitespace and check if input is empty
    if (input.value.trim() === '') {
        input.setCustomValidity('Please enter a valid text.'); // Set custom validity message
    } else {
        input.setCustomValidity(''); // Clear the custom validity message
    }
}
</script>
<script>
function validateDesignation1(input) {
    // Replace invalid characters
    input.value = input.value.replace(/[^A-Za-z\s,.]/g, '');

    // Trim whitespace and check if input is empty
    if (input.value.trim() === '') {
        input.setCustomValidity('Please enter a valid text.'); // Set custom validity message
    } else {
        input.setCustomValidity(''); // Clear the custom validity message
    }
}
</script>
<script>
    // Set the minimum date to today
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date();
        const formattedDate = today.toISOString().split('T')[0]; // Format the date to YYYY-MM-DD
        document.getElementById('interview_date').setAttribute('min', formattedDate);
    });
</script>
<script>
    $(document).ready(function () {
        $('#DataTables_Table_0').DataTable({
            "pageLength": 10, // Show 10 entries per page
            "lengthChange": false, // Disable the option to change page size
            "searching": true, // Enable search functionality
            "order": [[0, "asc"]] // Order by the first column (ID) ascending
        });
    });
</script>


<?= $this->endSection() ?>
