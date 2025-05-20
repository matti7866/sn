/**
 * Residence Application - Passport Upload & MRZ Processing
 * Handles passport file upload, compression, and form auto-fill
 */

console.log('Passport processor loaded - initializing...');

// Ensure this script only runs once
if (window.passportProcessorInitialized) {
    console.log('Passport processor already initialized, skipping');
} else {
    window.passportProcessorInitialized = true;

    // Check if the file input exists
    const fileInput = document.getElementById('basicInfoFile');

    if (!fileInput) {
        console.error('Could not find file input element on page!');
    } else {
        console.log('Found file input element:', fileInput.id);
        
        // Use the existing processing indicator
        const processingIndicator = document.getElementById('passportProcessingIndicator');
        
        if (!processingIndicator) {
            console.error('Could not find processing indicator element on page!');
        }
        
        // Store the original form submission handler
        const form = fileInput.closest('form');
        let originalFormSubmitEvent = null;
        
        if (form) {
            // Save original submit handler
            const originalSubmit = form.onsubmit;
            form.onsubmit = function(e) {
                // If MRZ processing is still happening, prevent submission
                if (window.isProcessingPassport) {
                    e.preventDefault();
                    console.log('Form submission prevented - MRZ processing still in progress');
                    // Store event for later submission
                    originalFormSubmitEvent = e;
                    return false;
                } else if (originalSubmit) {
                    // Call the original handler if it exists
                    return originalSubmit.call(this, e);
                }
                // Let the submission proceed
                return true;
            };
        }
        
        // Remove any existing listeners by cloning and replacing the element
        const newFileInput = fileInput.cloneNode(true);
        fileInput.parentNode.replaceChild(newFileInput, fileInput);
        
        // Flag to prevent recursive change events
        window.isProcessingPassport = false;
        
        // Add event listener to file input
        newFileInput.addEventListener('change', function(e) {
            // Prevent multiple simultaneous uploads
            if (window.isProcessingPassport) {
                console.log('Already processing a passport file, ignoring new upload');
                return;
            }
            
            window.isProcessingPassport = true;
            console.log('File input change triggered');
            
            if (!this.files || !this.files[0]) {
                console.log('No file selected');
                window.isProcessingPassport = false;
                return;
            }
            
            const file = this.files[0];
            console.log('Selected file:', file.name, 'Size:', file.size, 'bytes');
            
            // Show processing indicator
            if (processingIndicator) {
                processingIndicator.style.display = 'inline-block';
            }
            
            // Create FormData
            const formData = new FormData();
            formData.append('basicInfoFile', file);
            
            // Debug log the FormData contents
            console.log('FormData contents:');
            for (let pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }
            
            // Create and configure XMLHttpRequest
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'processPassport.php', true);
            
            // Set up event handlers
            xhr.onload = function() {
                console.log('XHR Response Headers:', xhr.getAllResponseHeaders());
                console.log('XHR Status:', xhr.status);
                console.log('Raw Response Text:', xhr.responseText);
                
                // Hide processing indicator
                if (processingIndicator) {
                    processingIndicator.style.display = 'none';
                }
                
                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        console.log('Parsed response:', response);
                        
                        if (response.success) {
                            // Show development mode notice if applicable
                            if (response.development_mode) {
                                // Add a small notice explaining this is test data
                                const infoSpan = document.createElement('span');
                                infoSpan.style.fontSize = '12px';
                                infoSpan.style.color = '#856404';
                                infoSpan.style.backgroundColor = '#fff3cd';
                                infoSpan.style.padding = '5px';
                                infoSpan.style.borderRadius = '3px';
                                infoSpan.style.marginLeft = '10px';
                                infoSpan.textContent = "[TEST DATA - Not real passport information]";
                                
                                // Insert after processing indicator
                                if (processingIndicator && processingIndicator.parentNode) {
                                    processingIndicator.parentNode.appendChild(infoSpan);
                                    
                                    // Remove after 10 seconds
                                    setTimeout(() => {
                                        if (infoSpan.parentNode) {
                                            infoSpan.parentNode.removeChild(infoSpan);
                                        }
                                    }, 10000);
                                }
                            }
                            
                            // Auto-fill form with passport data
                            autoFillPassportData(response.data);
                            
                            // Log success but don't show alert
                            console.log('Passport processed successfully!');
                        } else {
                            // Log error but don't show alert
                            console.error('Error processing passport:', response.error);
                        }
                    } catch (e) {
                        console.error('Error parsing JSON response:', e, xhr.responseText);
                    }
                } else {
                    console.error('HTTP error:', xhr.status);
                }
                
                // Reset processing flag
                window.isProcessingPassport = false;
                
                // If form submission was attempted during processing, submit now
                if (originalFormSubmitEvent && form) {
                    console.log('Submitting form after MRZ processing completed');
                    setTimeout(() => {
                        form.submit();
                    }, 500); // Small delay to ensure all fields are updated
                    originalFormSubmitEvent = null;
                }
            };
            
            xhr.onerror = function() {
                // Hide processing indicator
                if (processingIndicator) {
                    processingIndicator.style.display = 'none';
                }
                console.error('Network error occurred');
                
                // Reset processing flag - important to allow form submission even if MRZ fails
                window.isProcessingPassport = false;
                
                // If form submission was attempted during processing, submit now
                if (originalFormSubmitEvent && form) {
                    console.log('Submitting form after MRZ processing error');
                    setTimeout(() => {
                        form.submit();
                    }, 500);
                    originalFormSubmitEvent = null;
                }
            };
            
            // Send the request
            xhr.send(formData);
            console.log('Request sent');
        });
        
        console.log('Passport upload handler initialized successfully');
    }
}

/**
 * Auto-fills form fields with extracted passport data
 * @param {Object} data - The passport data extracted from MRZ
 */
function autoFillPassportData(data) {
    console.log('Auto-filling form with passport data:', data);
    
    // Define the fields that should be populated with passport data
    const passportFields = {
        'passportNumber': data.passport_number || '',
        'passengerName': data.name ? formatPassportName(data.name) : '',
        'dob': data.date_of_birth || '',
        'passportExpiryDate': data.expiry_date || '',
    };
    
    // Only update these specific fields
    for (const [fieldId, value] of Object.entries(passportFields)) {
        const field = document.getElementById(fieldId);
        if (field && value) {
            field.value = value;
            
            // Manually trigger one change event without propagating to other fields
            console.log('Triggering change event for field:', fieldId);
            const event = new Event('change', { bubbles: false });
            field.dispatchEvent(event);
        }
    }
    
    // Handle nationality separately since it's a dropdown
    console.log("Raw nationality value from data:", data.nationality);
    if (data.nationality) {
        const nationalityField = document.getElementById('nationality');
        if (nationalityField) {
            // Convert country codes to full names
            const countryCodeMap = {
                'AFG': 'Afghanistan',
                'USA': 'United States of America',
                'GBR': 'United Kingdom',
                'CAN': 'Canada',
                'AUS': 'Australia',
                'FRA': 'France',
                'DEU': 'Germany',
                'ITA': 'Italy',
                'ESP': 'Spain',
                'JPN': 'Japan',
                'CHN': 'China',
                'IND': 'India',
                'PAK': 'Pakistan',
                'IRN': 'Iran',
                'RUS': 'Russia',
                'SAU': 'Saudi Arabia',
                'ARE': 'United Arab Emirates',
                // Add more mappings as needed
            };

            // Convert code to full name if available
            const fullCountryName = countryCodeMap[data.nationality] || data.nationality;
            console.log('Looking for nationality:', fullCountryName, '(Original code:', data.nationality + ')');
            
            // Log all available options for debugging
            const selectOptions = Array.from(nationalityField.options)
                .map(o => ({value: o.value, text: o.text}));
            console.log('Available nationality options (first 10):', selectOptions.slice(0, 10));
            
            // Try different matching strategies
            let foundMatch = false;
            
            // Try exact match first
            console.log('Attempting to match nationality...');
            for (let i = 0; i < nationalityField.options.length; i++) {
                const option = nationalityField.options[i];
                const optionText = option.text.toUpperCase();
                
                // Debug display
                if (i < 10) {
                    console.log(`Option ${i}: ${optionText}`);
                }
                
                // Try exact match with full country name
                if (optionText === fullCountryName.toUpperCase()) {
                    nationalityField.selectedIndex = i;
                    foundMatch = true;
                    console.log('Found exact nationality match:', optionText);
                    break;
                }
                
                // Try contains match with full country name
                if (optionText.includes(fullCountryName.toUpperCase()) || 
                    fullCountryName.toUpperCase().includes(optionText)) {
                    nationalityField.selectedIndex = i;
                    foundMatch = true;
                    console.log('Found partial nationality match:', optionText, 'for', fullCountryName);
                    break;
                }
                
                // Try with original code as fallback
                if (optionText.includes(data.nationality)) {
                    nationalityField.selectedIndex = i;
                    foundMatch = true;
                    console.log('Found code-based nationality match:', optionText, 'for', data.nationality);
                    break;
                }
                
                // Special case for Afghanistan
                if (data.nationality === 'AFG' && optionText.includes('AFGHANISTAN')) {
                    nationalityField.selectedIndex = i;
                    foundMatch = true;
                    console.log('Found special match for Afghanistan');
                    break;
                }
            }
            
            // Try a more generic approach - find any option containing part of the country name
            if (!foundMatch && fullCountryName.length >= 3) {
                const searchTerm = fullCountryName.substring(0, 3).toUpperCase(); // Use first 3 chars
                console.log('Trying generic match with:', searchTerm);
                
                for (let i = 0; i < nationalityField.options.length; i++) {
                    const optionText = nationalityField.options[i].text.toUpperCase();
                    if (optionText.includes(searchTerm)) {
                        nationalityField.selectedIndex = i;
                        foundMatch = true;
                        console.log('Found generic match:', optionText, 'using search term:', searchTerm);
                        break;
                    }
                }
            }
            
            // If a match was found, trigger change event
            if (foundMatch) {
                const event = new Event('change', { bubbles: false });
                nationalityField.dispatchEvent(event);
                console.log('Set nationality dropdown to index:', nationalityField.selectedIndex, 
                            'text:', nationalityField.options[nationalityField.selectedIndex].text);
            } else {
                console.log('Could not find nationality option matching:', fullCountryName, 'or', data.nationality);
            }
        }
    }

    // Handle gender field (which expects 'male' or 'female')
    console.log("Raw gender value from data:", data.gender);
    if (data.gender) {
        const genderField = document.getElementById('gender');
        if (genderField) {
            // Convert gender value to match dropdown options
            let genderValue = '';
            if (data.gender.toUpperCase() === 'M') {
                genderValue = 'male';
            } else if (data.gender.toUpperCase() === 'F') {
                genderValue = 'female';
            } else {
                // Try to match as is, in case it's already in the right format
                genderValue = data.gender.toLowerCase();
            }
            
            console.log("Converted gender to:", genderValue);
            console.log("Gender options available:", 
                Array.from(genderField.options).map(o => o.value + ':' + o.text).join(', '));
            
            // Set the value and verify it worked
            if (genderValue === 'male' || genderValue === 'female') {
                genderField.value = genderValue;
                console.log('Set gender to:', genderValue, 'Field now shows:', genderField.value);
                
                // Trigger change event
                const event = new Event('change', { bubbles: false });
                genderField.dispatchEvent(event);
                
                // Double-check if it worked
                setTimeout(() => {
                    console.log('Gender field value after change event:', genderField.value);
                }, 100);
            } else {
                console.log('Could not convert gender:', data.gender, 'to male/female format');
            }
        }
    }
}

/**
 * Format passport name from MRZ format to display format
 * @param {string} name - Name in MRZ format (LASTNAME, FIRSTNAME)
 * @return {string} Formatted name (Firstname Lastname)
 */
function formatPassportName(name) {
    const nameParts = name.split(',');
    const lastName = nameParts[0] ? nameParts[0].trim() : '';
    const firstName = nameParts[1] ? nameParts[1].trim() : '';
    
    if (firstName && lastName) {
        // Convert to title case (First Last instead of FIRST LAST)
        const formatName = str => str.charAt(0).toUpperCase() + 
                               str.slice(1).toLowerCase();
        
        return formatName(firstName) + ' ' + formatName(lastName);
    }
    
    return name;
}

/**
 * Format gender from MRZ format (M/F) to form format (male/female)
 * @param {string} gender - Gender in MRZ format
 * @return {string} Gender in form format
 */
function formatGender(gender) {
    return gender.toLowerCase() === 'm' ? 'male' : 
           gender.toLowerCase() === 'f' ? 'female' : '';
} 