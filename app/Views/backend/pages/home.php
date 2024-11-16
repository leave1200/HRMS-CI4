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
									<li class="breadcrumb-item">Home</li>
									<li class="breadcrumb-item active" aria-current="page">
										Dashboard
									</li>
								</ol>
							</nav>
						</div>
					</div>
				</div>
				<div class="row clearfix progress-box">
					<div class="col-lg-3 col-md-6 col-sm-12 mb-30">
						<div class="card-box pd-30 height-100-p" data-bgcolor="#F19EF7">
							<div class="progress-box text-center">
								<div style="display:inline;width:120px;height:120px;">
									<canvas width="150" height="150" style="width: 120px; height: 120px;"></canvas>
									<input type="text" class="knob dial1" value="<?= $designationCount ?>" data-width="120" data-height="120" data-linecap="round" data-thickness="0.12" data-bgcolor="#F19EF7" data-fgcolor="#1b00ff" data-angleoffset="180" readonly="readonly" style="width: 64px; height: 40px; position: absolute; vertical-align: middle; margin-top: 40px; margin-left: -92px; border: 0px; background: none rgb(255, 255, 255); font: bold 24px Arial; text-align: center; color: #fff; padding: 0px; appearance: none;font-size: 50px"></div>
								<h5 class="text-white padding-top-10 h5"><i class="icon-copy fa fa-bank" aria-hidden="true" style="font-size: 50px;"></i>Deparment</h5>
								
							</div>
						</div>
					</div>
					
					<div class="col-lg-3 col-md-6 col-sm-12 mb-30" >
						<div class="card-box pd-30 height-100-p" data-bgcolor="#0079FA">
							<div class="progress-box text-center">
								<div style="display:inline;width:120px;height:120px;">
									<canvas width="150" height="150" style="width: 120px; height: 120px;"></canvas>
									<input type="text" class="knob dial2" value="<?= $employeeCount ?>" data-width="120" data-height="120" data-linecap="round" data-thickness="0.12" data-bgcolor="#0079FA" data-fgcolor="#00e091" data-angleoffset="180" readonly="readonly" style="width: 64px; height: 40px; position: absolute; vertical-align: middle; margin-top: 40px; margin-left: -92px; border: 0px; background: none rgb(255, 255, 255); font: bold 24px Arial; text-align: center; color: white; padding: 0px; appearance: none;font-size: 50px">
								</div>
								<h5 class="text-white padding-top-10 h5"><i class="icon-copy fa fa-users" aria-hidden="true" style="font-size: 50px;"></i>
									Employee
								</h5>
							</div>
						</div>
					</div>

					<div class="col-lg-3 col-md-6 col-sm-12 mb-30">
						<div class="card-box pd-30 height-100-p" data-bgcolor="#F1975D">
							<div class="progress-box text-center">
								<div style="display:inline;width:120px;height:120px;"><canvas width="150" height="150" style="width: 120px; height: 120px;"></canvas><input type="text" class="knob dial3" value="<?= $approvedCount ?>" data-width="120" data-height="120" data-linecap="round" data-thickness="0.12" data-bgcolor="#F1975D" data-fgcolor="#f56767" data-angleoffset="180" readonly="readonly" style="width: 64px; height: 40px; position: absolute; vertical-align: middle; margin-top: 40px; margin-left: -92px; border: 0px; background: none rgb(255, 255, 255); font: bold 24px Arial; text-align: center; color: white; padding: 0px; appearance: none;font-size: 50px;">
							</div>
								<h5 class="text-white padding-top-10 h5"><i class="icon-copy fa fa-check-square-o" aria-hidden="true" style="font-size: 50px;"></i>
									Approved Leave Applications
								</h5>
							</div>
						</div>
					</div>
					<?php if (isset($userStatus) && $userStatus !== 'EMPLOYEE'): ?> 
					<div class="col-lg-3 col-md-6 col-sm-12 mb-30">
						<div class="card-box pd-30 height-100-p" data-bgcolor="#F15A9A" >
							<div class="progress-box text-center">
								<div style="display:inline;width:120px;height:120px;"><canvas width="150" height="150" style="width: 120px; height: 120px;"></canvas><input type="text" class="knob dial4" value="<?= $pendingCount ?>" data-width="120" data-height="120" data-linecap="round" data-thickness="0.12" data-bgcolor="#F15A9A" data-fgcolor="#a683eb" data-angleoffset="180" readonly="readonly" style="width: 64px; height: 40px; position: absolute; vertical-align: middle; margin-top: 40px; margin-left: -92px; border: 0px; background: none rgb(255, 255, 255); font: bold 24px Arial; text-align: center; color: white; padding: 0px; appearance: none;font-size: 50px;">
							</div>
								<h5 class="text-white padding-top-10 h5"><i class="icon-copy fa fa-question-circle" aria-hidden="true" style="font-size: 50px;"></i>
									Pending Leave Applications
								</h5>
							</div>
						</div>
					</div>
					<?php endif; ?>
					<div class="col-lg-3 col-md-6 col-sm-12 mb-30">
						<div class="card-box pd-30 height-100-p" data-bgcolor="#ff6347" >
							<div class="progress-box text-center">
								<div style="display:inline;width:120px;height:120px;"><canvas width="150" height="150" style="width: 120px; height: 120px;"></canvas><input type="text" class="knob dial4" value="<?= $positionCount ?>" data-width="120" data-height="120" data-linecap="round" data-thickness="0.12" data-bgcolor="#ff6347" data-fgcolor="#a683eb" data-angleoffset="180" readonly="readonly" style="width: 64px; height: 40px; position: absolute; vertical-align: middle; margin-top: 40px; margin-left: -92px; border: 0px; background: none rgb(255, 255, 255); font: bold 24px Arial; text-align: center; color: white; padding: 0px; appearance: none;font-size: 50px;">
							</div>
								<h5 class="text-white padding-top-10 h5"><i class="icon-copy fa fa-level-up" aria-hidden="true" style="font-size: 50px;"></i>
									Positions
								</h5>
							</div>
						</div>
					</div>
					<?php if (isset($userStatus) && $userStatus !== 'EMPLOYEE'): ?> 
					<div class="col-lg-3 col-md-6 col-sm-12 mb-30">
						<div class="card-box pd-30 height-100-p" data-bgcolor="#660099" >
							<div class="progress-box text-center">
								<div style="display:inline;width:120px;height:120px;"><canvas width="150" height="150" style="width: 120px; height: 120px;"></canvas><input type="text" class="knob dial4" value="<?= $employeeCounts ?>" data-width="120" data-height="120" data-linecap="round" data-thickness="0.12" data-bgcolor="#660099" data-fgcolor="#a683eb" data-angleoffset="180" readonly="readonly" style="width: 64px; height: 40px; position: absolute; vertical-align: middle; margin-top: 40px; margin-left: -92px; border: 0px; background: none rgb(255, 255, 255); font: bold 24px Arial; text-align: center; color: white; padding: 0px; appearance: none;font-size: 50px;">
							</div>
								<h5 class="text-white padding-top-10 h5"><i class="icon-copy fa fa-user-circle" aria-hidden="true" style="font-size: 50px;"></i>
									Pending Employees
								</h5>
							</div>
						</div>
					</div>
					<div class="col-lg-3 col-md-6 col-sm-12 mb-30">
						<div class="card-box pd-30 height-100-p" data-bgcolor="#FAD6A5" >
							<div class="progress-box text-center">
								<div style="display:inline;width:120px;height:120px;"><canvas width="150" height="150" style="width: 120px; height: 120px;"></canvas><input type="text" class="knob dial4" value="<?= $amAttendanceRecords ?>" data-width="120" data-height="120" data-linecap="round" data-thickness="0.12" data-bgcolor="#FAD6A5" data-fgcolor="#a683eb" data-angleoffset="180" readonly="readonly" style="width: 64px; height: 40px; position: absolute; vertical-align: middle; margin-top: 40px; margin-left: -92px; border: 0px; background: none rgb(255, 255, 255); font: bold 24px Arial; text-align: center; color: white; padding: 0px; appearance: none;font-size: 50px;">
							</div>
								<h5 class="text-white padding-top-10 h5"><i class="icon-copy fa fa-certificate" aria-hidden="true" style="font-size: 50px;"></i>
									Attendance AM
								</h5>
							</div>
						</div>
					</div>
					<div class="col-lg-3 col-md-6 col-sm-12 mb-30">
						<div class="card-box pd-30 height-100-p" data-bgcolor="#F15A9A" >
							<div class="progress-box text-center">
								<div style="display:inline;width:120px;height:120px;"><canvas width="150" height="150" style="width: 120px; height: 120px;"></canvas><input type="text" class="knob dial4" value="<?= $pmAttendanceRecords ?>" data-width="120" data-height="120" data-linecap="round" data-thickness="0.12" data-bgcolor="#F15A9A" data-fgcolor="#a683eb" data-angleoffset="180" readonly="readonly" style="width: 64px; height: 40px; position: absolute; vertical-align: middle; margin-top: 40px; margin-left: -92px; border: 0px; background: none rgb(255, 255, 255); font: bold 24px Arial; text-align: center; color: white; padding: 0px; appearance: none;font-size: 50px;">
							</div>
								<h5 class="text-white padding-top-10 h5"><i class="icon-copy fa fa-circle" aria-hidden="true" style="font-size: 50px;"></i>
									Attendance PM
								</h5>
							</div>
						</div>
					</div>
					<?php endif; ?>
				</div>
				<div class="card-box pb-8">
					<div class="col-md-15 mb-20">
						<div class="card-box height-100-p pd-20">
							<div class="d-flex flex-wrap justify-content-between align-items-center pb-0 pb-md-3">
								<div class="h5 mb-md-0">Employees</div>
							</div>
							<div id="employeeChart" style="width: 100%; height: 400px;"></div>
						</div>
					</div>
				</div>
				<!-- <div class="card-box pb-10">
					<div class="h5 pd-20 mb-0">Employee</div>
						<div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
							<div class="row">
								<div class="table-responsive">
									<table class="table table-stripped" id="DataTables_Table_0" role="grid">
										<thead>
											<tr role="row">
												<th>#</th>
												<th>Name</th>
												<th>Birth Date</th>
												<th>Address</th>
											</tr>
										</thead>
										<tbody>
											<?php if (!empty($employee)): ?>
												<?php foreach ($employee as $index => $emp): ?>
													<?php if ($emp['result'] !== 'Pending'): ?>
													<tr>
														<td><?= $index + 1 ?></td>
														<td><?= htmlspecialchars($emp['firstname'] . ' ' . $emp['lastname']) ?></td>
														<td><?= htmlspecialchars($emp['dob']) ?></td>
														<td><?= htmlspecialchars($emp['address']) ?></td>
													</tr>
													<?php endif; ?>
												<?php endforeach; ?>
											<?php else: ?>
												<tr>
													<td colspan="4">No employees found</td>
												</tr>
											<?php endif; ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div> -->
</div>
<!-- Terms and Conditions Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" role="dialog" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Welcome to the HRMS application. By accessing or using this system, you agree to the following terms and conditions:</p>
                
                <h6>1. Usage Policy</h6>
                <p>
                    - This system is intended for authorized users only. Unauthorized access or use is strictly prohibited and may lead to disciplinary action.<br>
                    - Users are expected to ensure the accuracy and confidentiality of the data they enter into the system.
                </p>

                <h6>2. Data Privacy</h6>
                <p>
                    - All data entered into the system is owned by the organization and is subject to its data protection policies.<br>
                    - Personal data will be collected, stored, and used in accordance with applicable laws and the organization's privacy policies.<br>
                    - Users must not share sensitive data with unauthorized individuals.
                </p>

                <h6>3. User Responsibility</h6>
                <p>
                    - Users are responsible for maintaining the confidentiality of their login credentials.<br>
                    - Any misuse or unauthorized activity under your account will be your responsibility and may lead to account suspension.
                </p>

                <h6>4. System Availability</h6>
                <p>
                    - The HRMS system is available during the organization’s working hours. Scheduled maintenance may cause temporary downtime.<br>
                    - The organization is not responsible for any data loss due to technical issues, but all efforts will be made to ensure data recovery.
                </p>

                <h6>5. Prohibited Activities</h6>
                <p>
                    - Misuse of the HRMS system, such as entering false information, unauthorized data manipulation, or attempting to breach security protocols, is strictly forbidden.<br>
                    - Any such activities may result in disciplinary action, including termination or legal proceedings.
                </p>

                <h6>6. Updates to Terms</h6>
                <p>
                    - The organization reserves the right to modify these terms and conditions at any time.<br>
                    - Users will be notified of any significant changes and are expected to review and accept them to continue using the system.
                </p>

                <h6>7. Contact Information</h6>
                <p>
                    - For any questions or concerns regarding these terms, please contact the HR department at <strong>hr@yourcompany.com</strong>.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="acceptTerms">Accept</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    fetch('<?= route_to('admin.gender') ?>')
        .then(response => response.json())
        .then(data => {
            console.log('Data fetched from server:', data); // Log the response data
            Highcharts.chart('employeeChart', {
                chart: {
                    type: 'bar'
                },
                title: {
                    text: 'Total Number of Male and Female Employees'
                },
                xAxis: {
                    categories: ['Male', 'Female'],
                    crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Number of Employees'
                    }
                },
                plotOptions: {
                    bar: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [{
                    name: 'Employees',
                    data: [data.Male || 0, data.Female || 0] // Ensure data structure matches
                }]
            });
        })
        .catch(error => console.error('Error fetching employee data:', error));
});

</script>


<script>
document.getElementById('acceptTerms').addEventListener('click', function () {
    const userId = document.getElementById('userId').value; // Assuming you have the user ID available

    fetch('/accept-terms', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ user_id: userId }),
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert(data.message);
                $('#termsModal').modal('hide');
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while accepting the terms.');
        });
});

</script>


<?= $this->endSection()?>
<?= $this->section('sidebar') ?>
    <?= view('backend/layout/inc/left-sidebar', ['userStatus' => $userStatus]) ?>
<?= $this->endSection() ?>