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

           
            tableBody.innerHTML = '';

         
            referrals.forEach(referral => {
                const row = document.createElement("tr");

                
                const nameCell = document.createElement("td");
                nameCell.textContent = referral.ref_name;

                const emailCell = document.createElement("td");
                emailCell.textContent = referral.ref_email;

                const pointsCell = document.createElement("td");
                pointsCell.textContent = referral.points_earned;

               
                row.appendChild(nameCell);
                row.appendChild(emailCell);
                row.appendChild(pointsCell);

                
                tableBody.appendChild(row);
            });
        })
        .catch(error => console.error('Error fetching referrals:', error));
}


document.addEventListener("DOMContentLoaded", fetchReferrals);
