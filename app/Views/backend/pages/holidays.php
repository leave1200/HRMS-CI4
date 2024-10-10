<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<!-- Page Header -->
<div class="page-header">
	<div class="row">
		<div class="col-md-6 col-sm-12">
			<div class="title">
				<h4>Dashboard</h4>
			</div>
			<nav aria-label="breadcrumb" role="navigation">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?= base_url('index') ?>">Home</a></li>
					<li class="breadcrumb-item active" aria-current="page">Holidays</li>
				</ol>
			</nav>
		</div>
	</div>
</div>

<!-- Add New Holiday Form -->
<div class="container">
    <h2>Add New Holiday</h2>

    <form id="holidayForm" action="<?= route_to('admin.create_holidays') ?>" method="post">
        <?= csrf_field() ?>
        <div class="form-group">
            <label for="name">Holiday Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="date">Holiday Date</label>
            <input type="date" class="form-control" id="date" name="date" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Holiday</button>
    </form>
</div>

<!-- Update Holiday Modal -->
<div class="modal fade" id="updateHolidayModal" tabindex="-1" aria-labelledby="updateHolidayModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="updateHolidayModalLabel">Update Holiday</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="updateHolidayForm" action="<?= route_to('admin.update_holidays') ?>" method="post">
          <?= csrf_field() ?>
          <input type="hidden" id="updateHolidayId" name="id">
          <div class="form-group">
            <label for="updateName">Holiday Name</label>
            <input type="text" class="form-control" id="updateName" name="name" required>
          </div>
          <div class="form-group">
            <label for="updateDate">Holiday Date</label>
            <input type="date" class="form-control" id="updateDate" name="date" required>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Update Holiday</button>
            <!-- Cancel Holiday Button Inside Modal -->
            <button type="button" class="btn btn-danger" id="cancelHolidayBtn">Cancel Holiday</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Calendar Section -->
<div class="pd-20 card-box mb-30">
	<div class="clearfix">
		<div class="pull-left">
			<h4 class="text-blue h4">Holiday Calendar</h4>
		</div>
	</div>
	<div id="holidayCalendar"></div> <!-- Calendar will be rendered here -->
</div>

<!-- FullCalendar Styles and Script -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('holidayCalendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        events: [
            <?php foreach($holidays as $holiday): ?>
                {
                    title: '<?= addslashes($holiday['name']) ?>',
                    start: '<?= $holiday['date'] ?>',
                    id: '<?= $holiday['id'] ?>'
                },
            <?php endforeach; ?>
        ],
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        eventClick: function(info) {
            // When event (holiday) is clicked, open modal and populate data
            openUpdateModal(info.event.id, info.event.title, info.event.startStr);
        }
    });
    calendar.render();
});
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
// Function to open the modal and populate fields with holiday data for update
function openUpdateModal(id, name, date) {
    $('#updateHolidayId').val(id);
    $('#updateName').val(name);
    $('#updateDate').val(date);
    $('#updateHolidayModal').modal('show');
}

// Handle form submission for adding new holidays
$(document).ready(function() {
    $('#holidayForm').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            url: '<?= route_to('admin.create_holidays') ?>',
            method: 'post',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message,
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'There was an error processing your request: ' + error,
                });
            }
        });
    });

    // Handle form submission for updating holidays
    $('#updateHolidayForm').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            url: '<?= route_to('admin.update_holidays') ?>',
            method: 'post',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                    }).then(() => {
                        $('#updateHolidayModal').modal('hide');
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message,
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'There was an error processing your request: ' + error,
                });
            }
        });
    });

    // Handle holiday cancellation inside the update modal
    $('#cancelHolidayBtn').on('click', function() {
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you really want to cancel this holiday?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, cancel it!'
        }).then((result) => {
            if (result.isConfirmed) {
                var holidayId = $('#updateHolidayId').val();
                $.ajax({
                    url: '<?= route_to('admin.cancel_holidays') ?>',
                    method: 'post',
                    data: { id: holidayId, <?= csrf_token() ?>: '<?= csrf_hash() ?>' },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Cancelled',
                                text: 'Holiday has been cancelled.',
                            }).then(() => {
                                $('#updateHolidayModal').modal('hide');
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message,
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'There was an error processing your request: ' + error,
                        });
                    }
                });
            }
        });
    });
});
</script>

<?= $this->endSection() ?>
