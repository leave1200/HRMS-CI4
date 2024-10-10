$(document).ready(function() {
    $('#phone').on('input', function() {
        this.value = this.value.replace(/\D/g, '').substring(0, 11);
    });
    $(".tab-wizard").steps({
        headerTag: "h5",
        bodyTag: "section",
        transitionEffect: "fade",
        titleTemplate: '<span class="step">#index#</span> #title#',
        labels: {
            finish: "Submit"
        },
        onStepChanging: function(event, currentIndex, newIndex) {
            var form = $(this);

            // Validate current step
            if (currentIndex < newIndex) {
                var fields = form.find('section').eq(currentIndex).find('input, select, textarea');
                var isValid = true;

                // Check each field in the current step
                fields.each(function() {
                    if (!this.checkValidity()) {
                        $(this).addClass('is-invalid');
                        isValid = false;
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });

                return isValid;
            }

            return true; // Allow navigation if moving to previous step
        },
        onFinishing: function(event, currentIndex) {
            var form = $(this);
            var fields = form.find('input, select, textarea');
            var isValid = true;

            // Final step validation
            fields.each(function() {
                if (!this.checkValidity()) {
                    $(this).addClass('is-invalid');
                    isValid = false;
                } else {
                    $(this).removeClass('is-invalid');
                }
            });

            return isValid;
        },
        onFinished: function(event, currentIndex) {
            var form = $(this);

            // Submit form via AJAX
            $.ajax({
                url: form.attr('action'),
                method: form.attr('method'),
                data: form.serialize(),
                success: function(response) {
                    // Show success modal
                    $('#success-modal').modal('show');
                    // Optionally, reset form
                    form.trigger('reset');
                },
                error: function(xhr, status, error) {
                    // Handle error
                    console.error(xhr.responseText);
                    // Optionally, display error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to add employee. Please try again.',
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'OK'
                    });
                }
            });
        } 
    });
	$('.date-picker').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        orientation: 'bottom',
        language: 'en' // Set the language to English
    });

    $('.time-picker').timeDropper({
        format: 'h:mm a', // 12-hour format with AM/PM
        setCurrentTime: false, // do not set the current time automatically
        meridians: true, // show AM/PM
        primaryColor: "#4285F4", // primary color of the clock
        borderColor: "#4285F4", // border color of the clock
        backgroundColor: "#FFF", // background color of the clock
        init_animation: 'fadeIn' // initial animation (default)
    });




    // Validate on form submit (if not using AJAX submission)
    $('#addEmployeeForm').on('submit', function(event) {
        var form = $(this);
        var fields = form.find('input, select, textarea');
        var isValid = true;

        fields.each(function() {
            if (!this.checkValidity()) {
                $(this).addClass('is-invalid');
                isValid = false;
            } else {
                $(this).removeClass('is-invalid');
            }
        });

        if (!isValid) {
            event.preventDefault(); // Prevent form submission if validation fails
        }
    });
});
 