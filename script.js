document.getElementById('ajaxForm').addEventListener('submit', function(e) {
    e.preventDefault(); // Prevent default form submission
    
    // Simple debugging step
    console.log('Submit button clicked'); // Check this message in the console

    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'process.php', true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            console.log('Success: ', xhr.responseText); // Debug response
            document.getElementById('message').textContent = 'Form submitted successfully!';
        } else {
            console.error('Failed to submit: ', xhr.status); // Catch the error
            document.getElementById('message').textContent = 'Error processing the request.';
        }
    };
    xhr.onerror = function() {
        console.error('Request error');
        document.getElementById('message').textContent = 'There was an error processing the request.';
    };
    xhr.send(new FormData(document.getElementById('ajaxForm')));
});
