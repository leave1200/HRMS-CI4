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
    .heartbit {
      display: inline-block;
      width: 5px;
      height: 5px;
      background-color: blue;
      border-radius: 50%;
      position: relative;
      animation: heartbeat 2s ease-in-out infinite;
    }

    @keyframes heartbeat {
      0% {
        transform: scale(1);
      }
      5% {
        transform: scale(1.1);
      }
      10% {
        transform: scale(1.2);
      }
      15% {
        transform: scale(1.3);
      }
      20% {
        transform: scale(1.4);
      }
      25% {
        transform: scale(1.5);
      }
      30% {
        transform: scale(1.6);
      }
      35% {
        transform: scale(1.7);
      }
      40% {
        transform: scale(1.8);
      }
      45% {
        transform: scale(1.9);
      }
      50% {
        transform: scale(2);
      }
      55% {
        transform: scale(1.9);
      }
      60% {
        transform: scale(1.8);
      }
      65% {
        transform: scale(1.7);
      }
      70% {
        transform: scale(1.6);
      }
      75% {
        transform: scale(1.5);
      }
      80% {
        transform: scale(1.4);
      }
      85% {
        transform: scale(1.3);
      }
      90% {
        transform: scale(1.2);
      }
      95% {
        transform: scale(1.1);
      }
      100% {
        transform: scale(1);
      }
    }

</style>

<div class="header">
<div class="header-left">
    <div class="menu-icon bi bi-list"></div>
    <div class="header-search">
        <div class="running-text-container">
            <marquee id="welcomeText" style="font-size: 16px; font-weight: bold;">
                Welcome to the system HRMO <span id="userStatus"></span> <span id="userName"></span>!
            </marquee>
        </div>
    </div>
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
              <?php if (!empty($pendingCount)): ?>
                  <span class="notification-active">
                      <?= $pendingCount ?>
                  </span>
              <?php endif; ?>
              <span class="heartbit"></span>
          </a>
          <div class="dropdown-menu dropdown-menu-right notifications-dropdown">
              <h6 class="dropdown-header">Notifications</h6>
              <ul class="list-group">
                  <?php if (!empty($pendingEmployees)): ?>
                      <?php foreach ($pendingEmployees as $employee): ?>
                          <li class="list-group-item">
                              <a href="<?= route_to('admin.pendinglist') ?>">
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
					<a class="dropdown-item" href="<?= route_to('admin.terms'); ?>"><i class="dw dw-help"></i> Terms and Conditions</a>
					<a class="dropdown-item" href="<?= route_to('admin.logout') ?>"><i class="dw dw-logout"></i> Log Out</a>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
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
                            ' Waiting for Approval.</li>');
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

    // Optionally, set an interval to refresh notifications
    setInterval(fetchPendingNotifications, 30000); // Every 30 seconds

    // Handle dropdown toggle for notifications
    $('.user-notification .dropdown-toggle').on('click', function(e) {
        e.preventDefault(); // Prevent default anchor behavior
        $(this).next('.dropdown-menu').toggle(); // Toggle the dropdown menu
    });

    // Handle dropdown toggle for user info
    $('.user-info-dropdown .dropdown-toggle').on('click', function(e) {
        e.preventDefault(); // Prevent default anchor behavior
        $(this).next('.dropdown-menu').toggle(); // Toggle the dropdown menu
    });

    // Close dropdowns when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.user-notification').length) {
            $('.notifications-dropdown').hide(); // Close notifications dropdown
        }
        if (!$(e.target).closest('.user-info-dropdown').length) {
            $('.user-info-dropdown .dropdown-menu').hide(); // Close user dropdown
        }
    });

    // Prevent closing dropdown when clicking inside them
    $('.notifications-dropdown, .user-info-dropdown .dropdown-menu').on('click', function(e) {
        e.stopPropagation(); // Prevent click from bubbling up
    });
});

</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fetch the user data from the server
        fetch('/getUserInfo') // Update the route to match your backend
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Dynamically set the user status and name
                    document.getElementById('userStatus').textContent = data.status;
                    document.getElementById('userName').textContent = data.name;

                    // Optionally, set the color dynamically (can be set as per your preference)
                    document.getElementById('welcomeText').style.color = 'green'; // Set color here
                } else {
                    console.error(data.message || 'Failed to fetch user data.');
                }
            })
            .catch(error => {
                console.error('Error fetching user data:', error);
            });
    });
</script>

