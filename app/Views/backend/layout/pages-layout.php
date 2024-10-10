
<!DOCTYPE html>
<html>
	<head>
		<!-- Basic Page Info -->
		<meta charset="utf-8" />
		<meta name="csrf-token-name" content="<?= csrf_token() ?>">
		<title><?= isset($pageTitle) ? $pageTitle : 'New Page Title'; ?></title>

		<!-- Site favicon -->
		<link
			rel="apple-touch-icon"
			sizes="180x180"
			href="/backend/vendors/images/apple-touch-icon.png"
		/>
		<link
			rel="icon"
			type="image/png"
			sizes="32x32"
			href="/backend/vendors/images/favicon-32x32.png"
		/>
		<link
			rel="icon"
			type="image/png"
			sizes="16x16"
			href="/backend/vendors/images/favicon-16x16.png"
		/>

		<!-- Mobile Specific Metas -->
		<meta
			name="viewport"
			content="width=device-width, initial-scale=1, maximum-scale=1"
		/>
		<!-- CSS -->
		<link rel="stylesheet" type="text/css" href="/backend/vendors/styles/core.css" />
		<link rel="stylesheet" type="text/css" href="/backend/vendors/styles/icon-font.min.css"/>
		<link rel="stylesheet" type="text/css" href="/backend/src/plugins/jquery-steps/jquery.steps.css"/>
		<link rel="stylesheet" type="text/css" href="/backend/vendors/styles/style.css" />
		<link rel="stylesheet" type="text/css" href="/backend/src/plugins/datatables/css/dataTables.bootstrap4.min.css"/>
		<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jquery-knob@1.2.13/dist/jquery.knob.min.css"> -->
		<link rel="stylesheet" href="/backend/src/plugins/timedropper/timedropper.css">
		<link rel="stylesheet" href="/extra-assets/ijaboCropTool/ijaboCropTool.min.css">
		<?= $this->renderSection('stylesheets') ?>
	</head>
	<body>

<?php include('inc/header.php') ?>
<?php include('inc/right-sidebar.php') ?>
<?php include('inc/left-sidebar.php') ?>
<div class="mobile-menu-overlay">
</div>
<div class="main-container">
	<div class="pd-ltr-20 xs-pd-20-10">
		<div class="min-height-200px">
			<div>
				<?= $this->renderSection('content') ?>
			</div>
		</div>
		 <?php include('inc/footer.php') ?>
	</div>
</div>

		<!-- js -->
		<script src="/backend/vendors/scripts/core.js"></script>
		<script src="/backend/vendors/scripts/script.min.js"></script>
		<script src="/backend/vendors/scripts/process.js"></script>
		<script src="/backend/vendors/scripts/layout-settings.js"></script>
		<!-- <script src="/backend/src/plugins/apexcharts/apexcharts.min.js"></script> -->
		<script src="/backend/src/plugins/jquery-steps/jquery.steps.js"></script>
		<script src="/backend/vendors/scripts/steps-setting.js"></script>
		<script src="/backend/src/plugins/datatables/js/jquery.dataTables.min.js"></script>
		<script src="/backend/src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
		<script src="/backend/src/plugins/datatables/js/dataTables.responsive.min.js"></script>
		<script src="/backend/src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
		<!-- <script src="/backend/vendors/scripts/dashboard3.js"></script> -->
		<script src="/extra-assets/ijaboCropTool/ijaboCropTool.min.js"></script>
		<script src="/backend/src/plugins/sweetalert2/sweetalert2.all.js"></script>
		<script src="/backend/src/plugins/sweetalert2/sweet-alert.init.js"></script>
		<script src="/backend/sweetalert.min.js"></script>
		<script src="/backend/sweetalert2@11.js"></script>
		<script src="/backend/bootstrap.min.js"></script>
		<script src="/backend/src/plugins/timedropper/timedropper.js"></script>
		<script src="/backend/src/plugins/highcharts-6.0.7/code/highcharts.js"></script>
		<!-- <script src="/backend/src/plugins/jQuery-Knob-master/jquery.knob.min.js"></script> -->

		<?= $this->renderSection('scripts') ?>
	</body>
</html>
