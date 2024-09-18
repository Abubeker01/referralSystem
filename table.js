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

                const idCell = document.createElement("td");
                idCell.textContent = referral.ref_id;

                const pointsCell = document.createElement("td");
                pointsCell.textContent = referral.points_earned;

               
                row.appendChild(nameCell);
                row.appendChild(idCell);
                row.appendChild(pointsCell);

                
                tableBody.appendChild(row);
            });
        })
        .catch(error => console.error('Error fetching referrals:', error));
}


document.addEventListener("DOMContentLoaded", fetchReferrals);
