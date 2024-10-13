<style>
	.notification-active {
		background-color: #f00;
		border-radius: 50%;
		padding: 2px 6px;
		font-size: 12px;
		color: white;
	}
	.dropdown-menu.notifications-dropdown {
		width: 300px; /* Adjust width of the dropdown */
		max-height: 300px; /* Control height and enable scroll */
		overflow-y: auto;
	}
</style>

<div class="header">
	<div class="header-left">
		<div class="menu-icon bi bi-list"></div>
		<div class="search-toggle-icon bi bi-search" data-toggle="header_search"></div>
		<div class="header-search"></div>
	</div>
	<div class="header-right">
		<div class="dashboard-setting user-notification">
			<div class="dropdown">
				<a class="dropdown-toggle no-arrow" href="javascript:;" data-toggle="right-sidebar">
					<i class="dw dw-settings2"></i>
				</a>
			</div>
		</div>
        <div class="user-notification">
            <div class="dropdown">
                <a class="dropdown-toggle no-arrow" href="#" role="button" data-toggle="dropdown">
                    <i class="icon-copy dw dw-notification"></i>
                    <span class="badge notification-active"><?= isset($pendingApplications) ? count($pendingApplications) : 0 ?></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right notifications-dropdown">
                    <h6 class="dropdown-header">Notifications</h6>
                    <ul class="list-group">
                        <?php if (!empty($pendingEmployees)): ?>
                            <?php foreach ($pendingEmployees as $employee): ?>
                                <li class="list-group-item">
                                    <a href="<?= route_to('admin.pending_employee_detail', $employee['id']) ?>">
                                        <?= htmlspecialchars($employee['firstname'] . ' ' . $employee['lastname']) ?>
                                    </a> has a pending result.
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li class="list-group-item">No pending results.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>

</div>


		<div class="user-info-dropdown">
			<div class="dropdown">
				<a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
					<span class="user-icon ci-photo">
						<img src="<?= get_user()->picture == null ? '/images/users/userav-min.png' : '/images/users/'.get_user()->picture ?>" alt="" />
					</span>
					<span class="user-name ci-name"><?= get_user()->name ?></span>
				</a>
				<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
					<a class="dropdown-item" href="<?= route_to('admin.profile'); ?>"><i class="dw dw-user1"></i> Profile</a>
					<a class="dropdown-item" href="<?= route_to('setting'); ?>"><i class="dw dw-settings2"></i> Setting</a>
					<a class="dropdown-item" href="faq.html"><i class="dw dw-help"></i> Help</a>
					<a class="dropdown-item" href="<?= route_to('admin.logout') ?>"><i class="dw dw-logout"></i> Log Out</a>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    function fetchPendingNotifications() {
        $.ajax({
            url: '<?= route_to('admin.pending_results') ?>', // Uses the named route defined earlier
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                var notificationList = $('.notifications-dropdown .list-group');
                notificationList.empty();

                if (data.length > 0) {
                    data.forEach(function(notification) {
                        notificationList.append('<li class="list-group-item">' + 
                            notification.firstname + ' ' + notification.lastname + 
                            ' has a pending result.</li>');
                    });
                } else {
                    notificationList.append('<li class="list-group-item">No pending results.</li>');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching pending notifications:', error);
            }
        });

    }

    // Fetch notifications on page load
    fetchPendingNotifications();

    // Optionally, you can set an interval to refresh notifications
    setInterval(fetchPendingNotifications, 30000); // Every 30 seconds
});

</script>