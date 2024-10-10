<div class="left-side-bar">
    <div class="brand-logo">
        <a href="<?= route_to('admin.home') ?>">
            <img src="" alt="" class="dark-logo" />
            <img src="" alt="" class="light-logo" />
        </a>
        <div class="close-sidebar" data-toggle="left-sidebar-close">
            <i class="ion-close-round"></i>
        </div>
    </div>
    <div class="menu-block customscroll">
        <div class="sidebar-menu">
            <ul id="accordion-menu">
                <li>
                    <a href="<?= route_to('admin.home') ?>" class="dropdown-toggle no-arrow">
                        <span class="micon dw dw-home"></span>
                        <span class="mtext">HOME</span>
                    </a>
                </li>

				<?php if (isset($userStatus) && $userStatus !== 'EMPLOYEE'): ?>
                    <li>
                        <a href="javascript:;" class="dropdown-toggle">
                            <span class="micon bi bi-building"></span>
                            <span class="mtext">Designation</span>
                        </a>
                        <ul class="submenu">
                            <li><a href="<?= route_to('admin.designation'); ?>">Department</a></li>
                            <li><a href="<?= route_to('admin.position'); ?>">Position</a></li>
                        </ul>
                    </li>
                    <?php endif; ?>
                    <li class="dropdown">
                        <a href="javascript:;" class="dropdown-toggle">
                            <span class="micon dw dw-user"></span>
                            <span class="mtext">Employee</span>
                        </a>
                        <ul class="submenu">
                        <?php if (isset($userStatus) && $userStatus !== 'EMPLOYEE'): ?>
                            <li><a href="<?= route_to('admin.employee'); ?>">Add Employee</a></li>
                            <?php endif; ?>
                            <li><a href="<?= route_to('admin.employeelist'); ?>">Employee List</a></li>
                            <li><a href="<?= route_to('user.upload'); ?>">Employee Upload</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="javascript:;" class="dropdown-toggle">
                            <span class="micon fa fa-user-plus"></span>
                            <span class="mtext">Attendance</span>
                        </a>
                        <ul class="submenu">
                            <li><a href="<?= route_to('admin.attendance') ;?>">Add Attendance</a></li>
                            <li><a href="<?= route_to('admin.Report') ;?>">Attendance Reports</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:;" class="dropdown-toggle">
                            <span class="micon fa fa-user-times"></span>
                            <span class="mtext">Leave</span>
                        </a>
                        <ul class="submenu">
                            <li><a href="<?= route_to('admin.leave_application') ;?>">Leave Application</a></li>
                            <li><a href="<?= route_to('admin.leave_type') ;?>">Leave Type</a></li>
                            <li><a href="<?= route_to('admin.holidays') ;?>">Holidays</a></li>
                        </ul>
                    </li>
					<?php if (isset($userStatus) && $userStatus !== 'EMPLOYEE'): ?>
                    <li class="dropdown">
                        <a href="javascript:;" class="dropdown-toggle">
                            <span class="micon fa fa-user-plus"></span>
                            <span class="mtext">Account Center</span>
                        </a>
                        <ul class="submenu">
                            <li><a href="<?= route_to('user.add') ;?>">Add User</a></li>
                            <li><a href="<?= route_to('user.list') ;?>">Users List</a></li>
                        </ul>
                    </li>
                <?php endif; ?>

                <li class="dropdown">
                    <a href="<?= route_to('setting'); ?>" class="dropdown-toggle no-arrow">
                        <span class="micon fa fa-gear"></span>
                        <span class="mtext"> Settings </span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>