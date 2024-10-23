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
                    <div class="pd-20 card-box md-4">
								<div class="tab">
									<ul class="nav nav-tabs customtab" role="tablist">
										<li class="nav-item">
											<a class="nav-link active" data-toggle="tab" href="#home2" role="tab" aria-selected="true">Home</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" data-toggle="tab" href="#profile2" role="tab" aria-selected="false">Profile</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" data-toggle="tab" href="#contact2" role="tab" aria-selected="false">Contact</a>
										</li>
									</ul>
									<div class="tab-content">
										<div class="tab-pane fade show active" id="home2" role="tabpanel">
											<div class="pd-20">
												
											</div>
										</div>
										<div class="tab-pane fade" id="profile2" role="tabpanel">
											<div class="pd-20">
												
											</div>
										</div>
										<div class="tab-pane fade" id="contact2" role="tabpanel">
											<div class="pd-20">
												
											</div>
										</div>
									</div>
								</div>
							</div>

<?= $this->endSection()?>
