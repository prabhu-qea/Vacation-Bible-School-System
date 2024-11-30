document.addEventListener('DOMContentLoaded', function() {
    var form = document.getElementById('registrationForm');
    var dobInput = document.getElementById('dob');
    var ageInput = document.getElementById('age');    
    


function calculateAge() {
        const dob = document.getElementById('dob').value;
        if (dob) {
            const dobDate = new Date(dob);
            const diffMs = Date.now() - dobDate.getTime();
            const ageDt = new Date(diffMs);
            const age = Math.abs(ageDt.getUTCFullYear() - 1970);
            document.getElementById('age').value = age;

            const ageWarning = document.getElementById('ageWarning');
            if (age > 15) {
                ageWarning.style.display = 'block';
            } else {
                ageWarning.style.display = 'none';
            }
        }
    }

    form.addEventListener('submit', function(event) {
        event.preventDefault();
        
        const phoneNumber = document.getElementById('phone').value;
        const isValid = validateUKPhoneNumber(phoneNumber);       
        
        if (isValid) {
            //alert('Phone number is valid!');
            // Proceed with form submission or other actions
            form.submit();            
        } else {
            alert('Please enter a valid UK mobile number.');
        }
        
        
    });   

    function validateUKPhoneNumber(phoneNumber) {
        // UK mobile numbers: 07XXX XXXXXX
        const mobileRegex = /^07\d{9}$/;
    
        // UK landline numbers: 01XXX XXXXXX, 02XXX XXXXXX, 03XXX XXXXXX
        const landlineRegex = /^(01|02|03)\d{8,9}$/;
    
        // Remove spaces, hyphens, or other non-numeric characters
        const cleanedNumber = phoneNumber.replace(/[\s-]/g, '');
    
        //return mobileRegex.test(cleanedNumber) || landlineRegex.test(cleanedNumber);
        return mobileRegex.test(cleanedNumber);
    }

});