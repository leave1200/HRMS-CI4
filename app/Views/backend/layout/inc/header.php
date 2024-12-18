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
    display: inline-flex;
    justify-content: center;
    align-items: center;
    width: 10px; /* Adjust size as needed */
    height: 10px;
    background-color: red;
    color: white;
    font-size: 8px;
    font-weight: bold;
    border-radius: 50%;
    position: relative;
    animation: heartbeat 2s ease-in-out infinite;
    text-align: center;
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
        <div class="running-text-container">
            <div class="scrolling-text" id="welcomeText">
              <marquee behavior="" direction=""> Welcome to the system HRMO <span id="userStatus"></span> <span id="userName"></span>!</marquee>
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
    <?php if (isset($userStatus) && $userStatus !== 'EMPLOYEE' && $userStatus !== 'STAFF'): ?> 
    <div class="user-notification">
      <div class="dropdown">
      <a class="dropdown-toggle no-arrow" href="#" role="button" data-toggle="dropdown">
    <i class="icon-copy dw dw-notification"></i>
    <?php if (!empty($pendingEmployees)): ?>
        <span class="heartbit"><?= count($pendingEmployees) ?></span> <!-- Add count inside heartbit -->
    <?php else: ?>
        <span class="heartbit"></span> <!-- Empty heartbit when no notifications -->
    <?php endif; ?>
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
                      <li class="list-group-item">No pending Employee.</li>
                  <?php endif; ?>
              </ul>
          </div>
      </div>
  </div>
  <?php endif; ?>




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
					<!-- <a class="dropdown-item" href="<?= route_to('setting'); ?>"><i class="dw dw-settings2"></i> Setting</a> -->
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
            url: '<?= route_to('admin.pending_results') ?>', // Ensure this URL is correct
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                console.log('Received data:', data);  // Log data to verify the response

                var notificationList = $('.notifications-dropdown .list-group');
                var heartbit = $('.heartbit');
                notificationList.empty();

                var totalNotifications = 0;  // To keep track of total notifications

                // Check and display pending employee results notifications
                if (data.employees && data.employees.length > 0) {
                    data.employees.forEach(function(notification) {
                        notificationList.append('<li class="list-group-item">' + 
                            notification.firstname + ' ' + notification.lastname + 
                            ' has a pending result.</li>');
                        totalNotifications++; // Increment the total notifications count
                    });
                } else {
                    notificationList.append('<li class="list-group-item">No pending Employee.</li>');
                }

                // Check and display pending leave applications notifications
                if (data.leave_applications && data.leave_applications.length > 0) {
                    data.leave_applications.forEach(function(application) {
                        notificationList.append('<li class="list-group-item">' +
                            application.la_name + ' has a pending ' + application.la_type + ' application.</li>');
                        totalNotifications++; // Increment the total notifications count
                    });
                } else {
                    notificationList.append('<li class="list-group-item">No pending leave applications.</li>');
                }

                // Default message if no notifications are found
                if (totalNotifications === 0) {
                    notificationList.append('<li class="list-group-item">No pending results or leave applications.</li>');
                }

                // Update the heartbit count based on the total number of notifications
                if (totalNotifications > 0) {
                    heartbit.text(totalNotifications).show();  // Show the heartbit and update with the count
                } else {
                    heartbit.text('').hide();  // Hide the heartbit if no notifications
                }
                
                console.log('Total Notifications:', totalNotifications);  // Log the total count for debugging
            },
            error: function(xhr, status, error) {
                console.error('Error fetching pending notifications:', error);
            }
        });
    }

    // Fetch notifications on page load
    fetchPendingNotifications();

    // Optionally, set an interval to refresh notifications every 30 seconds
    setInterval(fetchPendingNotifications, 30000);

    // Handle dropdown toggle for notifications
    $('.user-notification .dropdown-toggle').on('click', function(e) {
        e.preventDefault();
        $(this).next('.dropdown-menu').toggle(); // Toggle the dropdown menu
    });

    // Handle dropdown toggle for user info
    $('.user-info-dropdown .dropdown-toggle').on('click', function(e) {
        e.preventDefault();
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

