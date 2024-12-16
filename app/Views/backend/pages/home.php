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
				<?php if (isset($userStatus) && $userStatus !== 'EMPLOYEE'): ?>
				<div class="row clearfix progress-box">
					<div class="col-lg-3 col-md-6 col-sm-12 mb-30">
						<div class="card-box pd-20 height-50-p" data-bgcolor="#F19EF7">
							<div class="progress-box text-center">
								<div style="display:inline;width:120px;height:120px;">
									<canvas width="150" height="150" style="width: 120px; height: 120px;"></canvas>
									<input type="text" class="knob dial1" value="<?= $designationCount ?>" data-width="120" data-height="120" data-linecap="round" data-thickness="0.12" data-bgcolor="#F19EF7" data-fgcolor="#1b00ff" data-angleoffset="180" readonly="readonly" style="width: 64px; height: 40px; position: absolute; vertical-align: middle; margin-top: 40px; margin-left: -92px; border: 0px; background: none rgb(255, 255, 255); font: bold 24px Arial; text-align: center; color: #fff; padding: 0px; appearance: none;font-size: 50px"></div>
								<h5 class="text-white padding-top-10 h5"><i class="icon-copy fa fa-bank" aria-hidden="true" style="font-size: 50px;"></i>Deparment</h5>
								
							</div>
						</div>
					</div>
					<div class="col-lg-3 col-md-6 col-sm-12 mb-30">
						<div class="card-box pd-20 height-50-p" data-bgcolor="#ff6347" >
							<div class="progress-box text-center">
								<div style="display:inline;width:120px;height:120px;"><canvas width="150" height="150" style="width: 120px; height: 120px;"></canvas><input type="text" class="knob dial4" value="<?= $positionCount ?>" data-width="120" data-height="120" data-linecap="round" data-thickness="0.12" data-bgcolor="#ff6347" data-fgcolor="#a683eb" data-angleoffset="180" readonly="readonly" style="width: 64px; height: 40px; position: absolute; vertical-align: middle; margin-top: 40px; margin-left: -92px; border: 0px; background: none rgb(255, 255, 255); font: bold 24px Arial; text-align: center; color: white; padding: 0px; appearance: none;font-size: 50px;">
							</div>
								<h5 class="text-white padding-top-10 h5"><i class="icon-copy fa fa-level-up" aria-hidden="true" style="font-size: 50px;"></i>
									Positions
								</h5>
							</div>
						</div>
					</div>
					<div class="col-lg-3 col-md-6 col-sm-12 mb-30" >
						<div class="card-box pd-20 height-50-p" data-bgcolor="#0079FA">
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
						<div class="card-box pd-20 height-50-p" data-bgcolor="#F1975D">
							<div class="progress-box text-center">
								<div style="display:inline;width:120px;height:120px;"><canvas width="150" height="150" style="width: 120px; height: 120px;"></canvas><input type="text" class="knob dial3" value="<?= $approvedCount ?>" data-width="120" data-height="120" data-linecap="round" data-thickness="0.12" data-bgcolor="#F1975D" data-fgcolor="#f56767" data-angleoffset="180" readonly="readonly" style="width: 64px; height: 40px; position: absolute; vertical-align: middle; margin-top: 40px; margin-left: -92px; border: 0px; background: none rgb(255, 255, 255); font: bold 24px Arial; text-align: center; color: white; padding: 0px; appearance: none;font-size: 50px;">
							</div>
								<h5 class="text-white padding-top-10 h5"><i class="icon-copy fa fa-check-square-o" aria-hidden="true" style="font-size: 20px;"></i>
									Approved Leave Applications
								</h5>
							</div>
						</div>
					</div> 
					<div class="col-lg-3 col-md-6 col-sm-12 mb-30">
						<div class="card-box pd-20 height-50-p" data-bgcolor="#F15A9A" >
							<div class="progress-box text-center">
								<div style="display:inline;width:120px;height:120px;"><canvas width="150" height="150" style="width: 120px; height: 120px;"></canvas><input type="text" class="knob dial4" value="<?= $pendingCount ?>" data-width="120" data-height="120" data-linecap="round" data-thickness="0.12" data-bgcolor="#F15A9A" data-fgcolor="#a683eb" data-angleoffset="180" readonly="readonly" style="width: 64px; height: 40px; position: absolute; vertical-align: middle; margin-top: 40px; margin-left: -92px; border: 0px; background: none rgb(255, 255, 255); font: bold 24px Arial; text-align: center; color: white; padding: 0px; appearance: none;font-size: 50px;">
							</div>
								<h5 class="text-white padding-top-10 h5"><i class="icon-copy fa fa-question-circle" aria-hidden="true" style="font-size: 20px;"></i>
									Pending Leave Applications
								</h5>
							</div>
						</div>
					</div>
					
					
					<?php if (isset($userStatus) && $userStatus !== 'EMPLOYEE'): ?> 
					<div class="col-lg-3 col-md-6 col-sm-12 mb-30">
						<div class="card-box pd-20 height-50-p" data-bgcolor="#660099" >
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
						<div class="card-box pd-20 height-50-p" data-bgcolor="#FAD6A5" >
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
						<div class="card-box pd-20 height-50-p" data-bgcolor="#F15A9A" >
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
			</div>
				<?php endif; ?>
				<!-- this area is for the dashboard of employee not available for admin  -->
				<?php if (isset($userStatus) && $userStatus !== 'ADMIN'): ?>
					<div class="card-box pb-8">
					<div class="col-md-15 mb-20">
						<div class="card-box height-100-p pd-20">
							<div class="d-flex flex-wrap justify-content-between align-items-center pb-0 pb-md-3">
								<div class="h5 mb-md-0"><?= get_user()->name ?></div>
							</div>
							<div id="fileUploadsChart" style="width:100%; height:400px;"></div>
						</div>
					</div>
				</div>
					
					<script>
					document.addEventListener('DOMContentLoaded', function () {
						fetch('/getUserFileUploads') // Update this route to match your backend setup
							.then(response => response.json())
							.then(data => {
								// Extract data for Highcharts
								const categories = data.map(item => item.upload_date); // X-axis (dates)
								const seriesData = data.map(item => parseInt(item.file_count)); // Y-axis (file counts)

								// Render the chart
								Highcharts.chart('fileUploadsChart', {
									chart: {
										type: 'column'
									},
									title: {
										text: 'Your File Upload Activity'
									},
									xAxis: {
										categories: categories,
										title: {
											text: 'Upload Dates'
										}
									},
									yAxis: {
										title: {
											text: 'Number of Files Uploaded'
										}
									},
									series: [{
										name: 'Files Uploaded',
										data: seriesData
									}]
								});
							})
							.catch(error => console.error('Error fetching file upload data:', error));
					});
					</script>
					<div class="card-box pb-8">
					<div class="col-md-15 mb-20">
						<div class="card-box height-100-p pd-20">
							<div class="d-flex flex-wrap justify-content-between align-items-center pb-0 pb-md-3">
								<div class="h5 mb-md-0"><?= get_user()->name ?></div>
							</div>
							<div id="leaveApplicationsChart" style="width:100%; height:400px;"></div>
						</div>
					</div>
				</div>
				<script>
						document.addEventListener('DOMContentLoaded', function () {
							fetch('/getUserLeaveApplications') // Update this route to match your setup
								.then(response => response.json())
								.then(response => {
									if (!response.success || !response.data || response.data.length === 0) {
										console.warn(response.message || 'No data returned from API.');
										Highcharts.chart('leaveApplicationsChart', {
											chart: { type: 'column' },
											title: { text: 'No Leave Applications Data Available' },
											xAxis: { categories: [] },
											yAxis: { title: { text: 'Number of Leave Applications' } },
											series: []
										});
										return;
									}

									const data = response.data;

									// Prepare unique dates and statuses
									const dates = [...new Set(data.map(item => item.leave_date))];
									const statuses = [...new Set(data.map(item => item.status))];

									// Prepare series data for each status
									const series = statuses.map(status => {
										const statusData = dates.map(date => {
											const entry = data.find(item => item.leave_date === date && item.status === status);
											return entry ? parseInt(entry.leave_count) : 0; // Fill missing data with 0
										});

										return {
											name: status,
											data: statusData
										};
									});

									// Render the chart
									Highcharts.chart('leaveApplicationsChart', {
										chart: {
											type: 'column'
										},
										title: {
											text: 'Your Leave Applications by Status'
										},
										xAxis: {
											categories: dates,
											title: {
												text: 'Leave Dates'
											}
										},
										yAxis: {
											min: 0,
											title: {
												text: 'Number of Applications'
											}
										},
										series: series
									});
								})
								.catch(error => {
									console.error('Error fetching leave application data:', error);
									Highcharts.chart('leaveApplicationsChart', {
										chart: { type: 'column' },
										title: { text: 'Error Loading Data' },
										xAxis: { categories: [] },
										yAxis: { title: { text: 'Number of Leave Applications' } },
										series: []
									});
								});
						});


				</script>

					<?php endif; ?>
					<?php if (isset($userStatus) && $userStatus !== 'ADMIN'): ?>
						<div class="card-box pb-8">
							<div class="col-md-15 mb-20">
								<div class="card-box height-100-p pd-20">
									<div class="d-flex flex-wrap justify-content-between align-items-center pb-0 pb-md-3">
										<div class="h5 mb-md-0"><?= get_user()->name ?></div>
									</div>
									<div id="attendanceChart" style="width:100%; height:400px;"></div>
								</div>
							</div>
						</div>
						<script>
							document.addEventListener('DOMContentLoaded', function () {
								fetch('/getUserAttendances') // Ensure this matches your defined backend route
									.then(response => response.json())
									.then(response => {
										if (!response.success || !response.data || response.data.length === 0) {
											console.warn(response.message || 'No data returned from API.');
											Highcharts.chart('attendanceChart', {
												chart: { type: 'bar' },
												title: { text: 'No Attendance Data Available' },
												xAxis: { categories: [] },
												yAxis: { title: { text: 'Attendance Status Count' } },
												series: []
											});
											return;
										}

										const data = response.data;

										// Extract unique dates
										const dates = [...new Set(data.map(item => item.date))];

										// Define attendance statuses to be displayed
										const statuses = ['AM Sign-In', 'PM Sign-In'];

										// Prepare series data for each status
										const series = statuses.map(status => {
											const statusData = dates.map(date => {
												const entry = data.find(item => item.date === date && item.status === status);
												return entry ? parseInt(entry.count) : 0; // Fill missing data with 0
											});

											return {
												name: status,
												data: statusData
											};
										});

										// Render the chart
										Highcharts.chart('attendanceChart', {
											chart: { type: 'bar' },
											title: { text: 'Your Attendance Records' },
											xAxis: {
												categories: dates,
												title: { text: 'Dates' }
											},
											yAxis: {
												min: 0,
												title: { text: 'Attendance Status Count' }
											},
											tooltip: {
												shared: true,
												crosshairs: true
											},
											series: series
										});
									})
									.catch(error => {
										console.error('Error fetching attendance data:', error);
										Highcharts.chart('attendanceChart', {
											chart: { type: 'bar' },
											title: { text: 'Error Loading Data' },
											xAxis: { categories: [] },
											yAxis: { title: { text: 'Attendance Status Count' } },
											series: []
										});
									});
							});
						</script>



						<?php endif; ?>
						<?php if (isset($userStatus) && $userStatus == 'ADMIN'): ?>
							<!-- Section for All Users' Attendance -->
								<div class="card-box pb-8">
									<div class="col-md-15 mb-20">
										<div class="card-box height-100-p pd-20">
											<div class="d-flex flex-wrap justify-content-between align-items-center pb-0 pb-md-3">
												<div class="h5 mb-md-0">All Users' Attendance</div>
											</div>
											<div id="allAttendanceChart" style="width:100%; height:400px;"></div>
										</div>
									</div>
								</div>
							<?php endif; ?>
							<script>
								document.addEventListener('DOMContentLoaded', function () {
									// Fetch the attendance data for all users
									fetch('/getAllAttendances') // Adjust the route to match your backend method for all users' attendance data
										.then(response => response.json())
										.then(response => {
											if (!response.success || !response.data || response.data.length === 0) {
												console.warn(response.message || 'No data returned from API.');
												Highcharts.chart('allAttendanceChart', {
													chart: { type: 'bar' },
													title: { text: 'No Attendance Data Available' },
													xAxis: { categories: [] },
													yAxis: { title: { text: 'Attendance Status Count' } },
													series: []
												});
												return;
											}

											const data = response.data;

											// Extract unique dates
											const dates = [...new Set(data.map(item => item.date))];

											// Define attendance statuses to be displayed
											const statuses = ['AM Sign-In', 'PM Sign-In'];

											// Prepare series data for each status
											const series = statuses.map(status => {
												const statusData = dates.map(date => {
													const entry = data.find(item => item.date === date && item.status === status);
													return entry ? parseInt(entry.count) : 0; // Fill missing data with 0
												});

												return {
													name: status,
													data: statusData
												};
											});

											// Render the chart for all users
											Highcharts.chart('allAttendanceChart', {
												chart: { type: 'bar' },
												title: { text: 'All Users Attendance Records' },
												xAxis: {
													categories: dates,
													title: { text: 'Dates' }
												},
												yAxis: {
													min: 0,
													title: { text: 'Attendance Status Count' }
												},
												tooltip: {
													shared: true,
													crosshairs: true
												},
												series: series
											});
										})
										.catch(error => {
											console.error('Error fetching all users attendance data:', error);
											Highcharts.chart('allAttendanceChart', {
												chart: { type: 'bar' },
												title: { text: 'Error Loading Data' },
												xAxis: { categories: [] },
												yAxis: { title: { text: 'Attendance Status Count' } },
												series: []
											});
										});
								});
							</script>



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





<?= $this->endSection()?>
<?= $this->section('sidebar') ?>
    <?= view('backend/layout/inc/left-sidebar', ['userStatus' => $userStatus]) ?>
<?= $this->endSection() ?>