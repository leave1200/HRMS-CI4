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
												<form action="" method="POST" id="general_setting_form">
                                                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" class="ci_csrf_data">
                                                  <div class="row">
                                                    <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="">Title</label>
                                                                <input type="text" class="form-control" name="title" placeholder="Enter a Title">
                                                                <span class="text-danger error-text title_error"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="">Email</label>
                                                                <input type="text" class="form-control" name="email" placeholder="Enter a email">
                                                                <span class="text-danger error-text email_error"></span>
                                                            </div>
                                                        </div>
                                                  </div>
                                                  <div class="row">
                                                    <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="">Phone</label>
                                                                <input type="text" class="form-control" name="phone" placeholder=" Enter phone #">
                                                                <span class="text-danger error-text phone_error"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="">Meta Keywords</label>
                                                                <input type="text" class="form-control" name="meta_keywords" placeholder="Enter Meta Keywords">
                                                                <span class="text-danger error-text meta_keywords_error"></span>
                                                            </div>
                                                        </div>
                                                  </div>
                                                  <div class="form-group">
                                                                <label for="">Description</label>
                                                                <textarea  name="description" id="" cols="4" rows="3" class="form-control"placeholder="Enter description"></textarea>
                                                                <span class="text-danger error-text description_error"></span>
                                                            </div>
                                                </form>
											</div>
										</div>
										<div class="tab-pane fade" id="logo_favicon" role="tabpane2">
											<div class="pd-20">
												logo
											</div>
										</div>
									</div>
								</div>
							</div>

<?= $this->endSection()?>
