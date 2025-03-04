document.getElementById('route').addEventListener('change', function() {
    const busNo = this.options[this.selectedIndex].dataset.busno;
    const stepCost = this.options[this.selectedIndex].dataset.cost;

    // Fetch available seats for the selected route
    fetch('fetch_seat.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ bus_no: busNo })
    })
    .then(response => response.json())
    .then(data => {
        const seatInput = document.getElementById('seatInput');
        seatInput.innerHTML = ''; // Clear previous options
        for (let i = 1; i <= 38; i++) {
            const option = document.createElement('option');
            option.value = i;
            option.text = i;
            if (data.includes(i.toString())) {
                option.disabled = true; // Disable booked seats
            }
            seatInput.appendChild(option);
        }
        // Set the amount based on quantity
        const quantity = document.getElementById('quantity').value;
        document.getElementById('bookAmount').value = '₱' + (stepCost * quantity); // Multiply by quantity
    });
});

// Update the amount when quantity changes
document.getElementById('quantity').addEventListener('input', function() {
    const stepCost = this.options[this.selectedIndex].dataset.cost; // Get the step cost again
    const quantity = this.value;
    document.getElementById('bookAmount').value = '₱' + (stepCost * quantity); // Update the total amount
});