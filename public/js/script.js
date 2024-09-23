$.noConflict();
jQuery(document).ready(function ($) {
    $("#datepicker,#datepicker1").datepicker();
    
     $(document).on('click', ".close.btn", function() {
        $(".modal").removeClass('show');
        $('body').removeClass('modal-open');
        $('body').removeAttr('style');
        $('.modal-backdrop').remove();
    });
});

var ctx = document.getElementById('myPieChart').getContext('2d');

var data = {
    labels: ['Closed', 'Ontime', 'Cancelled'],
    datasets: [{
        label: 'My Dataset',
        data: [20, 15, 10],
        backgroundColor: [
            '#FE2D2D',
            '#C87B08',
            '#08C8BC'
        ],
        borderColor: [
            '#FE2D2D',
            '#C87B08',
            '#08C8BC'
        ],
        cutout: '90%',

    }]
};

var options = {
    plugins: {
        legend: {
            position: 'left', // Align legend to the left side
            display: true,
            labels: {
                boxWidth: 20, // Adjust the width of the legend colored boxes
                padding: 20, // Add padding between legend items if needed
                // margin: {
                //     left: -50, // Change this value to move the legend labels
                //     top: 0,
                //     right: 0,
                //     bottom: 0,
                // }
            }
        }
    }
};

var myPieChart = new Chart(ctx, {
    type: 'doughnut', // Set chart type to 'doughnut' for a donut chart
    data: data,
    options: options
});


// JavaScript functions to control the popup form
function openForm() {
    document.getElementById("popupForm").style.display = "block";
}

function closeForm() {
    document.getElementById("popupForm").style.display = "none";
}

// link activate
const menuItems = document.querySelectorAll('.nav-link');

// Loop through each menu item
menuItems.forEach(item => {
    // Add click event listener to each menu item
    item.addEventListener('click', function() {
        // Remove 'active' class from all menu items
        menuItems.forEach(item => {
            item.classList.remove('active');
        });

        // Add 'active' class to the clicked menu item
        this.classList.add('active');
    });
});

function validateForm() {
    var name = document.getElementById('name').value;
    var email = document.getElementById('email').value;
    var contact = document.getElementById('contact').value;
    var designation = document.getElementById('designation').value;
    var password = document.getElementById('password').value;
    var role = document.getElementById('role_id').value;

    // Clear previous error messages
    clearErrorMessages();

    var isValid = true;

    if (name === '') {
        displayError('name', 'Please enter a name');
        isValid = false;
    }

    if (email === '') {
        displayError('email', 'Please enter an email address');
        isValid = false;
    } else {
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            displayError('email', 'Please enter a valid email address');
            isValid = false;
        }
    }

    if (contact === '') {
        displayError('contact', 'Please enter a contact number');
        isValid = false;
    } else {
        var contactRegex = /^\d{3}-\d{3}-\d{4}$/;
        if (!contactRegex.test(contact)) {
            displayError('contact', 'Please enter a valid contact number (XXX-XXX-XXXX)');
            isValid = false;
        }
    }

    if (designation === '') {
        displayError('designation', 'Please enter a designation');
        isValid = false;
    }

    if (password === '') {
        displayError('password', 'Please enter a password');
        isValid = false;
    }

    if (role === '') {
        displayError('role_id', 'Please select a role');
        isValid = false;
    }

    return isValid;
}

function displayError(fieldId, errorMessage) {
    var errorDiv = document.createElement('div');
    errorDiv.className = 'alert alert-danger';
    errorDiv.textContent = errorMessage;

    var inputField = document.getElementById(fieldId);
    inputField.parentNode.appendChild(errorDiv);
}

function clearErrorMessages() {
    var errorMessages = document.querySelectorAll('.alert.alert-danger');
    errorMessages.forEach(function (errorMessage) {
        errorMessage.parentNode.removeChild(errorMessage);
    });
}
