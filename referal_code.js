// Fetch the referral link for the logged-in user
fetch('referal_link.php')  // Ensure the correct path to fetch referral data
    .then(response => response.json())  // Parse JSON response
    .then(data => {
        if (data.error) {
            console.error(data.error);
            return;
        }

        // Construct the referral link (assuming your signup page is signup.php)
        const referralLink = `localhost/refSys/form/signup.php?referral=${data.referral_code}`;

        // Store the referral link in the hidden span (but keep it hidden)
        const referralLinkField = document.querySelector('#referralLink');
        referralLinkField.textContent = referralLink;

        // Copy functionality for the button
        const copyButton = document.querySelector('#copy-link');
        if (copyButton) {
            copyButton.addEventListener('click', () => {
                // Copy the referral link to clipboard without displaying it
                navigator.clipboard.writeText(referralLinkField.textContent)
                    .then(() => alert('Referral link copied to clipboard!'))
                    .catch(err => console.error('Could not copy text: ', err));
            });
        } else {
            console.error('Copy button not found.');
        }
    })
    .catch(error => {
        console.error('Error fetching referral code:', error);
    });
