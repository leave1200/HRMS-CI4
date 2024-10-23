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
											UI Tabs
										</li>
									</ol>
								</nav>
							</div>
						</div>
					</div>

<?= $this->endSection()?>
