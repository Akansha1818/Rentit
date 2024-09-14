document.addEventListener('DOMContentLoaded', function() {
    // Add event listeners to all delete buttons
    const deleteButtons = document.querySelectorAll('form button[name="delete"]');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent form submission

            // Find the row to be deleted
            const form = event.target.closest('form');
            const userId = form.querySelector('input[name="user_id"]').value;
            const row = document.getElementById(`row_${userId}`);

            // Confirm deletion
            const confirmDelete = confirm('Are you sure you want to delete this entry?');
            if (confirmDelete) {
                // Submit the form to the server to delete from the database
                form.submit();

                // Remove the row from the table
                row.remove();
            }
        });
    });
});
