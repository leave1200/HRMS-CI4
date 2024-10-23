<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="page-header">
						<div class="row">
							<div class="col-md-12 col-sm-12">
								<div class="title">
									<h4>Setting</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="<?= route_to('admin.home') ?>">Dashboard</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Setting
										</li>
									</ol>
								</nav>
							</div>
						</div>
					</div>
                    <div class="pd-20 card-box md-4">
								<div class="tab">
									<ul class="nav nav-tabs customtab" role="tablist">
										<li class="nav-item">
											<a class="nav-link active" data-toggle="tab" href="#general_setting" role="tab" aria-selected="true">General Setting</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" data-toggle="tab" href="#logo_favicon" role="tab" aria-selected="false">Logo & Favicon</a>
										</li>
									</ul>
									<div class="tab-content">
										<div class="tab-pane fade show active" id="general_setting" role="tabpanel">
											<div class="pd-20">
												general setting
											</div>
										</div>
										<div class="tab-pane fade" id="profile2" role="logo_favicon">
											<div class="pd-20">
												logo
											</div>
										</div>
									</div>
								</div>
							</div>

<?= $this->endSection()?>
