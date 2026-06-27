document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('previewModal');
    if (!modal) {
        return;
    }

    const sendBtn = document.getElementById('sendOtpBtn');
    const verifyBtn = document.getElementById('verifyOtpBtn');
    const phoneInput = document.getElementById('phoneInput');
    const otpInput = document.getElementById('otpInput');
    const otpNotice = document.getElementById('otpNotice');
    const otpResult = document.getElementById('otpResult');
    const submitBtn = document.getElementById('submitBtn');
    const previewBtn = document.getElementById('previewBtn');
    const previewContent = document.getElementById('previewContent');
    const closePreview = document.getElementById('closePreview');
    const confirmSubmit = document.getElementById('confirmSubmit');
    const form = document.getElementById('regForm');
    const locationStatus = document.getElementById('locationStatus');
    const latitudeInput = document.getElementById('latitude');
    const longitudeInput = document.getElementById('longitude');
    const detectedRegionInput = document.getElementById('detected_region');
    const locationSourceInput = document.getElementById('location_source');
    const locationAccuracyInput = document.getElementById('location_accuracy');

    const sendUrl = modal.dataset.otpSendUrl;

    function setLocationStatus(message, isError = false) {
        if (!locationStatus) return;
        locationStatus.textContent = message;
        locationStatus.classList.toggle('text-red-600', isError);
        locationStatus.classList.toggle('text-slate-500', !isError);
    }

    function estimateRegion(lat, lng) {
        const regions = [
            { name: 'Arusha', lat: [-4.0, -3.0], lng: [36.0, 37.0] },
            { name: 'Dar es Salaam', lat: [-7.0, -6.5], lng: [39.0, 39.5] },
            { name: 'Dodoma', lat: [-6.5, -5.5], lng: [35.5, 36.5] },
            { name: 'Geita', lat: [-3.5, -2.5], lng: [32.0, 33.0] },
            { name: 'Iringa', lat: [-8.5, -7.5], lng: [35.0, 36.0] },
            { name: 'Kagera', lat: [-2.5, -1.0], lng: [31.0, 32.0] },
            { name: 'Katavi', lat: [-7.0, -6.0], lng: [31.0, 32.0] },
            { name: 'Kigoma', lat: [-5.5, -4.5], lng: [29.5, 30.5] },
            { name: 'Kilimanjaro', lat: [-4.0, -3.0], lng: [37.0, 38.0] },
            { name: 'Lindi', lat: [-10.0, -9.0], lng: [39.0, 40.0] },
            { name: 'Manyara', lat: [-5.0, -4.0], lng: [36.0, 37.0] },
            { name: 'Mara', lat: [-2.5, -1.5], lng: [34.0, 35.0] },
            { name: 'Mbeya', lat: [-9.5, -8.5], lng: [33.0, 34.0] },
            { name: 'Morogoro', lat: [-7.5, -6.5], lng: [37.0, 38.0] },
            { name: 'Mtwara', lat: [-11.0, -10.0], lng: [39.5, 40.5] },
            { name: 'Mwanza', lat: [-3.5, -2.5], lng: [32.5, 33.5] },
            { name: 'Njombe', lat: [-10.0, -9.0], lng: [34.5, 35.5] },
            { name: 'Pemba Kaskazini', lat: [-5.0, -4.9], lng: [39.7, 39.8] },
            { name: 'Pemba Kusini', lat: [-5.4, -5.3], lng: [39.7, 39.8] },
            { name: 'Pwani', lat: [-8.0, -7.0], lng: [38.5, 39.5] },
            { name: 'Rukwa', lat: [-9.0, -8.0], lng: [31.5, 32.5] },
            { name: 'Ruvuma', lat: [-11.5, -10.5], lng: [35.5, 36.5] },
            { name: 'Shinyanga', lat: [-4.5, -3.5], lng: [33.0, 34.0] },
            { name: 'Simiyu', lat: [-3.5, -2.5], lng: [34.0, 35.0] },
            { name: 'Singida', lat: [-6.0, -5.0], lng: [34.5, 35.5] },
            { name: 'Songwe', lat: [-10.0, -9.0], lng: [31.0, 32.0] },
            { name: 'Tabora', lat: [-6.0, -5.0], lng: [32.5, 33.5] },
            { name: 'Tanga', lat: [-6.0, -5.0], lng: [38.0, 39.0] },
            { name: 'Unguja Kaskazini', lat: [-6.0, -5.9], lng: [39.2, 39.3] },
            { name: 'Unguja Kusini', lat: [-6.2, -6.1], lng: [39.3, 39.4] },
            { name: 'Unguja Mjini', lat: [-6.2, -6.1], lng: [39.1, 39.2] }
        ];

        for (const region of regions) {
            if (lat >= region.lat[0] && lat <= region.lat[1] && lng >= region.lng[0] && lng <= region.lng[1]) {
                return region.name;
            }
        }

        return null;
    }

    function populateLocationFields(position) {
        const lat = position.coords.latitude;
        const lng = position.coords.longitude;
        const accuracy = position.coords.accuracy;
        const detectedRegion = estimateRegion(lat, lng);

        if (latitudeInput) latitudeInput.value = lat;
        if (longitudeInput) longitudeInput.value = lng;
        if (detectedRegionInput) detectedRegionInput.value = detectedRegion || '';
        if (locationSourceInput) locationSourceInput.value = 'browser';
        if (locationAccuracyInput) locationAccuracyInput.value = `${accuracy} meters`;

        if (detectedRegion) {
            setLocationStatus(`Detected location is ${detectedRegion}. Please make sure your selected region matches.`);
        } else {
            setLocationStatus('Location detected, but the region could not be matched to a known area. Please select the correct region manually.', true);
        }
    }

    function locateUser() {
        if (!navigator.geolocation) {
            setLocationStatus('Geolocation not available in this browser. Please choose your region manually.', true);
            return;
        }

        setLocationStatus('Detecting your location...');
        navigator.geolocation.getCurrentPosition(populateLocationFields, function (error) {
            const message = {
                1: 'Permission denied. Enable location services if you want automatic region detection.',
                2: 'Position unavailable. Please select your region manually.',
                3: 'Location request timed out. Please select your region manually.'
            }[error.code] || 'Unable to detect location. Please select your region manually.';
            setLocationStatus(message, true);
            if (locationSourceInput) locationSourceInput.value = 'browser_failed';
        }, {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 60000
        });
    }

    locateUser();
    const verifyUrl = modal.dataset.otpVerifyUrl;
    const csrfToken = modal.dataset.csrfToken;
    async function autoVerifyDevOtp(phone, code) {
        if (!verifyBtn || !otpResult || !submitBtn) return;

        verifyBtn.disabled = true;
        otpResult.textContent = 'Auto-verifying dev OTP...';
        otpResult.classList.remove('text-red-600', 'text-emerald-600');

        const response = await fetch(verifyUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ phone: phone, code: code })
        });

        const data = await response.json();

        if (data.status === 'ok') {
            otpResult.textContent = 'Phone verified ✓';
            otpResult.classList.add('text-emerald-600');
            submitBtn.disabled = false;
            if (otpInput) otpInput.disabled = true;
            if (sendBtn) sendBtn.disabled = true;
        } else {
            otpResult.textContent = 'Auto-verification failed: ' + (data.message || 'Unknown error');
            otpResult.classList.add('text-red-600');
            if (verifyBtn) verifyBtn.disabled = false; // Allow manual retry
        }
    }


    if (sendBtn && phoneInput && sendUrl) {
        sendBtn.addEventListener('click', async function () {
            const phone = phoneInput.value.trim();
            if (!phone) {
                return alert('Enter phone first');
            }
            sendBtn.disabled = true;
            const response = await fetch(sendUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ phone: phone })
            });
            let data;
            try {
                data = await response.json();
            } catch (error) {
                data = { status: 'error', message: 'Invalid response' };
            }
            sendBtn.disabled = false;

            if (response.status === 429) {
                alert(data.message || 'Too many requests. Please wait.');
                return;
            }
            if (data.status === 'ok') {
                // If in dev mode with a code, auto-verify it.
                if (data.dev && data.code) {
                    otpNotice.classList.remove('hidden');
                    otpNotice.textContent = 'Dev mode: OTP received, auto-verifying...';
                    autoVerifyDevOtp(phone, data.code);
                    return; // Stop further execution
                }
                if (otpInput) otpInput.disabled = false;
                if (verifyBtn) verifyBtn.disabled = false;
                if (otpResult) {
                    otpResult.textContent = '';
                    otpResult.classList.remove('text-red-600');
                    otpResult.classList.add('text-emerald-600');
                }
                if (otpNotice) {
                    otpNotice.classList.remove('hidden');
                    otpNotice.textContent = data.dev && data.code ? 'OTP sent (dev code: ' + data.code + ')' : 'OTP sent — check your SMS messages.';
                }
            } else {
                let errorMessage = data.message || 'Failed to send OTP';
                if (data.details) {
                    errorMessage += '\n' + JSON.stringify(data.details, null, 2);
                }
                if (otpResult) {
                    otpResult.textContent = errorMessage;
                    otpResult.classList.remove('text-emerald-600');
                    otpResult.classList.add('text-red-600');
                }
            }
        });
    }

    if (verifyBtn && phoneInput && otpInput && verifyUrl) {
        verifyBtn.addEventListener('click', async function () {
            const phone = phoneInput.value.trim();
            const code = otpInput.value.trim();
            if (!phone || !code) {
                return alert('Provide phone and code');
            }
            verifyBtn.disabled = true;
            const response = await fetch(verifyUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ phone: phone, code: code })
            });
            let data;
            try {
                data = await response.json();
            } catch (error) {
                data = { status: 'error' };
            }
            verifyBtn.disabled = false;

            if (response.status === 429) {
                if (otpResult) {
                    otpResult.textContent = data.message || 'Too many attempts. Request a new code.';
                    otpResult.classList.remove('text-emerald-600');
                    otpResult.classList.add('text-red-600');
                }
                return;
            }
            if (data.status === 'ok') {
                if (otpResult) {
                    otpResult.textContent = 'Phone verified ✓';
                    otpResult.classList.remove('text-red-600');
                    otpResult.classList.add('text-emerald-600');
                }
                if (submitBtn) submitBtn.disabled = false;
                verifyBtn.disabled = true;
                otpInput.disabled = true;
                sendBtn.disabled = true;
            } else {
                if (otpResult) {
                    otpResult.textContent = data.message || 'Invalid code';
                    otpResult.classList.remove('text-emerald-600');
                    otpResult.classList.add('text-red-600');
                }
            }
        });
    }

    if (previewBtn && previewContent && closePreview && confirmSubmit && form) {
        previewBtn.addEventListener('click', function () {
            const formData = new FormData(form);
            const fields = [
                'first_name', 'last_name', 'email', 'phone', 'national_id', 'university',
                'course', 'degree', 'graduation_date', 'gpa', 'region', 'employment_status',
                'job_title', 'experience_years', 'skills', 'languages'
            ];
            let html = '';
            fields.forEach(function (key) {
                const value = formData.get(key) || '';
                html += '<div class="mb-2"><strong>' + key.replace(/_/g, ' ') + ':</strong> ' + String(value) + '</div>';
            });
            previewContent.innerHTML = html;
            previewModal.classList.remove('hidden');
            previewModal.classList.add('flex');
        });

        closePreview.addEventListener('click', function () {
            previewModal.classList.add('hidden');
            previewModal.classList.remove('flex');
        });

        confirmSubmit.addEventListener('click', function () {
            form.submit();
        });
    }
});
