// Function to fetch referrals and update the table
function fetchReferrals() {
    fetch('table.php')
        .then(response => response.json())
        .then(referrals => {
            if (referrals.error) {
                console.error(referrals.error);
                return;
            }

            const tableBody = document.querySelector(".ref-table tbody");

            // Clear existing table rows
            tableBody.innerHTML = '';

            // Check if there are referrals
            if (referrals.length > 0) {
                referrals.forEach(referral => {
                    const row = document.createElement("tr");

                    // Create table cells and set content
                    const nameCell = document.createElement("td");
                    nameCell.textContent = referral.username; // Corrected key name

                    const emailCell = document.createElement("td");
                    emailCell.textContent = referral.email; // Corrected key name

                    const pointsCell = document.createElement("td");
                    pointsCell.textContent = referral.points; // Corrected key name

                    // Append the cells to the row
                    row.appendChild(nameCell);
                    row.appendChild(emailCell);
                    row.appendChild(pointsCell);

                    // Append the row to the table body
                    tableBody.appendChild(row);
                });
            } else {
                // If no referrals are found, display a message
                const noDataRow = document.createElement("tr");
                noDataRow.innerHTML = '<td colspan="3">No referrals found</td>';
                tableBody.appendChild(noDataRow);
            }
        })
        .catch(error => console.error('Error fetching referrals:', error));
}

// Call the fetch function once the DOM content is fully loaded
document.addEventListener("DOMContentLoaded", fetchReferrals);