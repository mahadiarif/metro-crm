<!-- Date Range Validation Snippet -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const reportForm = document.querySelector('form[action*="reports"]');
    if (reportForm) {
        reportForm.addEventListener('submit', function(e) {
            const startDate = this.querySelector('input[name="start_date"]').value;
            const endDate = this.querySelector('input[name="end_date"]').value;

            if (startDate && endDate && new Date(startDate) > new Date(endDate)) {
                e.preventDefault();
                // You can replace alert with a better UI toast if available
                alert('Error: Start Date cannot be greater than End Date.');
                return false;
            }
        });
    }
});
</script>
