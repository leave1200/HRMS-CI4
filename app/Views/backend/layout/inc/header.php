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
              <span class="heartbit" style="display: none;">0</span> <!-- Default hidden -->
          </a>
          <div class="dropdown-menu dropdown-menu-right notifications-dropdown">
              <h6 class="dropdown-header">Notifications</h6>
              <ul class="list-group">
                  <li class="list-group-item">Loading...</li> <!-- Placeholder -->
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
$(document).ready(function () {
    function updateNotificationCount() {
        $.ajax({
            url: '<?= route_to("admin.notifications") ?>', // Your notifications route
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                // Update notification count
                if (data.pendingCount > 0) {
                    $('.heartbit').text(data.pendingCount).show(); // Show the count badge
                } else {
                    $('.heartbit').hide(); // Hide if no pending notifications
                }

                // Update the dropdown list with pending notifications
                var notificationList = $('.notifications-dropdown .list-group');
                notificationList.empty();
                if (data.pendingEmployees.length > 0) {
                    data.pendingEmployees.forEach(function (employee) {
                        notificationList.append(
                            '<li class="list-group-item">' +
                            '<a href="<?= route_to("admin.pendinglist") ?>">' +
                            employee.firstname +
                            ' ' +
                            employee.lastname +
                            '</a> has a pending result.' +
                            '</li>'
                        );
                    });
                } else {
                    notificationList.append('<li class="list-group-item">No pending results.</li>');
                }
            },
            error: function (xhr, status, error) {
                console.error('Error fetching notification count:', error);
            },
        });
    }

    // Call function on page load
    updateNotificationCount();

    // Optionally, refresh notifications every 30 seconds
    setInterval(updateNotificationCount, 30000); // Adjust interval as needed
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

