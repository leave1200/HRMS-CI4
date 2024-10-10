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
									<li class="breadcrumb-item"><a href="<?= route_to('admin.home')?>">Dashboard</a></li>
									<li class="breadcrumb-item active" aria-current="page">
										 Employee Report
									</li>
								</ol>
							</nav>
						</div>
					</div>
				</div>W
				<div class="pd-20 card-box mb-30">
					<div class="clearfix">
						<div class="pull-left">
							<h4 class="text-blue h4">Reports</h4>
						</div>
					</div>
						<form id="designationForm" action="" method="post">
								<?= csrf_field() ?>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label>Employee</label>
											<select type="text" class="form-control" style="width: 100%; height: 38px" required></select>
											<div class="col-md-6">
												<div class="form-group">
													<label for="comment">Comments :</label>
													<textarea class="form-control" id="comment" name="comment" style="width: 100%;"></textarea>
												</div>
											</div>
											<button type="submit" class="btn btn-outline-primary mt-2">Report</button>
										</div>
									</div>
								</div>
							</form>
						</div>

<?= $this->endSection()?>
