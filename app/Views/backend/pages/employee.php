<?= $this->extend('backend/layout/pages-layout') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="title">
                <h4>Dashboard</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= route_to('admin.home') ?>">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add Employee</li>
                </ol>
            </nav>
        </div>
    </div>
</div>
    <div class="min-height-200px">
        <div class="pd-20 card-box mb-30">
            <div class="clearfix">
                <h4 class="text-blue h4">Employee</h4>
                <p class="mb-30">Application Form</p>
            </div>
            <div class="wizard-content">
                <form class="tab-wizard wizard-circle wizard" id="addEmployeeForm" action="<?= route_to('employee_save') ?>" method="POST">
                    <?= csrf_field() ?>
                    <h5>Personal Info</h5>
                    <section>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="firstname">First Name :</label>
                                    <input type="text" class="form-control" id="firstname" name="firstname" oninput="validateDesignation(this)" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="lastname">Last Name :</label>
                                    <input type="text" class="form-control" id="lastname" name="lastname" oninput="validateDesignation(this)" required/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email Address :</label>
                                    <input type="email" class="form-control" id="email" name="email" required/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Phone Number :</label>
                                    <input type="text" class="form-control" id="phone" name="phone" oninput="this.value = this.value.replace(/[^0-9]/g, '')" maxlength="11" title="Please enter a valid phone number (only digits, up to 11 digits)" style="-webkit-spin-button: none;" required/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                <label for="address">Address :</label>
                                <input type="text" class="form-control" id="address" name="address" oninput="validateDesignation2(this)" required/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="dob">Date of Birth :</label>
                                    <input type="date" class="form-control dob-input" id="dob" name="dob" 
                                    placeholder="Select Date" onchange="calculateAge()" required />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sex">Sex :</label>
                                    <select class="form-control" id="sex" name="sex">
                                        <option value="">Sex</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 col-md-2 col-form-label">Age</label>
                                        <input class="form-control" type="text" id="age" name="age" readonly required>
                                </div>
                            </div>
                        </div>
                    </section>
                    <h5>Educational Background</h5>
                    <section>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="p_school">Primary School Attended:</label>
                                    <input type="text"class="form-control" id="p_school" name="p_school" oninput="validateDesignation(this)" required/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="s_school">Secondary School Attended :</label>
                                    <input type="text" class="form-control" id="s_school" name="s_school" oninput="validateDesignation(this)" required/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="t_school">Tertiary School Attended:</label>
                                    <input type="text" class="form-control" id="t_school" name="t_school" oninput="validateDesignation(this)" required/>
                                </div>
                            </div>
                        </div>
                    </section>
                    <h5>Interview</h5>
                    <section>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="interview_for">Interview For :</label>
                                    <input type="text" class="form-control" id="interview_for" name="interview_for" oninput="validateDesignation(this)" required/>
                                </div>
                                <div class="form-group">
                                    <label for="interview_type">Interview Type :</label>
                                    <select class="form-control" id="interview_type" name="interview_type">
                                        <option value="Normal">Normal</option>
                                        <option value="Difficult">Difficult</option>
                                        <option value="Hard">Hard</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="interview_date">Interview Date :</label>
                                    <input type="date" class="form-control" id="interview_date" name="interview_date" placeholder="Select Date" required/>
                                </div>
                                <div class="form-group">
												<label>Interview Time :</label>
												<input
													class="form-control time-picker"
													placeholder="Select time"
													type="text"
                                                    name="interview_time"
												required />
											</div>
                            </div>
                        </div>
                    </section>
                    <h5>Remark</h5>
                    <section>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="behaviour">Behaviour :</label>
                                    <input type="text" class="form-control" id="behaviour" name="behaviour" oninput="validateDesignation(this)" required/>
                                </div>
                                <div class="form-group">
                                    <label for="result">Result :</label>
                                    <select class="form-control" id="result" name="result">
                                        <option value="">Select Result</option>
                                        <option value="Pending">Pending</option>
                                        <option value="Hired">Hired</option>
                                        <option value="Rejected">Rejected</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="comment">Comments :</label>
                                    <textarea class="form-control" id="comment" name="comment" oninput="validateDesignation(this)" required></textarea>
                                </div>
                            </div>
                        </div>
                    </section>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- success Popup html Start -->
<div
						class="modal fade"
						id="success-modal"
						tabindex="-1"
						role="dialog"
						aria-labelledby="exampleModalCenterTitle"
						aria-hidden="true"
					>
						<div class="modal-dialog modal-dialog-centered" role="document">
							<div class="modal-content">
								<div class="modal-body text-center font-18">
									<h3 class="mb-20">Employee Added!</h3>
									<div class="mb-30 text-center">
										<img src="/backend/vendors/images/success.png" />
									</div>
                                    successfully!!
								</div>
								<div class="modal-footer justify-content-center">
									<button
										type="button"
										class="btn btn-primary"
										data-dismiss="modal"
									>
										Done
									</button>
								</div>
							</div>
						</div>
                    </div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function calculateAge() {
    const dobInput = document.getElementById("dob").value;

    if (!dobInput) {
        document.getElementById("age").value = ""; // Clear age if no date is selected
        return;
    }

    const dob = new Date(dobInput);
    const today = new Date();

    let age = today.getFullYear() - dob.getFullYear();
    const monthDifference = today.getMonth() - dob.getMonth();

    if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < dob.getDate())) {
        age--;
    }

    document.getElementById("age").value = age;
}
</script>

<!-- <script>
    $(document).ready(function() {
    // Define the default date (January 1, 2002)
    var defaultDate = new Date(2002, 0, 1); // January is month 0

    $(".date-picker").datepicker({
        dateFormat: 'yy-mm-dd', // Use the ISO standard date format
        changeMonth: true,
        changeYear: true,
        yearRange: "1900:2002", // Allow years from 1900 to 2002
        maxDate: defaultDate, // Disable dates after 2002
        defaultDate: defaultDate, // Set default date to January 1, 2002
        onClose: function(selectedDate) {
            var date = $(this).datepicker('getDate');
            if (!date) {k
                $(this).datepicker('setDate', defaultDate); // Set default date if no date is selected
            }
        }
    });

    // Set default date if field is empty on page load
    $(".date-picker").each(function() {
        if ($(this).val() === "") {
            $(this).datepicker('setDate', defaultDate);
        }
    });
});
</script> -->
<script>
    // Get the current date
    const today = new Date();
    const currentYear = today.getFullYear();
    
    // Set the minimum and maximum dates
    document.getElementById('dob').setAttribute('min', '1985-01-01');
    document.getElementById('dob').setAttribute('max', '2002-12-31');
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
<!-- <script>
    window.onload = function() {
        document.getElementById('dob').addEventListener('change', function() {
            const dob = new Date(this.value);  // Date of Birth
            const today = new Date();          // Current Date

            // Check if dob is a valid date
            if (isNaN(dob)) {
                document.getElementById('age').value = "";
                return;
            }
            
            // Calculate age
            let age = today.getFullYear() - dob.getFullYear();
            const monthDifference = today.getMonth() - dob.getMonth();
            
            // Adjust age if the birthday hasn't occurred yet this year
            if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < dob.getDate())) {
                age--;
            }

            document.getElementById('age').value = age;
        });
    };
</script> -->
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
        const interviewDateInput = document.getElementById('interview_date');

        // You can also set the input type to "date" if you want a date picker
        interviewDateInput.setAttribute('min', formattedDate);
        
        // If using a date picker library, initialize it here
        // Example for jQuery UI Datepicker:
        $(interviewDateInput).datepicker({
            minDate: 0 // Prevent selection of past dates
        });
    });
</script>
<?= $this->endSection() ?>

