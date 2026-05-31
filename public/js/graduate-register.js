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
        if (lat >= 6.4 && lat <= 7.2 && lng >= 39.0 && lng <= 39.5) {
            return 'Dar es Salaam';
        }
        if (lat >= -3.5 && lat <= -2.0 && lng >= 36.4 && lng <= 37.2) {
            return 'Arusha';
        }
        if (lat >= -6.4 && lat <= -5.6 && lng >= 35.4 && lng <= 36.6) {
            return 'Dodoma';
        }
        if (lat >= -9.0 && lat <= -7.9 && lng >= 32.5 && lng <= 34.0) {
            return 'Mbeya';
        }
        if (lat >= -4.5 && lat <= -2.5 && lng >= 37.0 && lng <= 38.5) {
            return 'Kilimanjaro';
        }
        if (lat >= -7.3 && lat <= -5.5 && lng >= 37.0 && lng <= 38.7) {
            return 'Morogoro';
        }
        if (lat >= -3.5 && lat <= -1.0 && lng >= 31.0 && lng <= 33.0) {
            return 'Mwanza';
        }
        if (lat >= -6.0 && lat <= -4.0 && lng >= 37.5 && lng <= 39.5) {
            return 'Tanga';
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
                alert(data.message || 'Failed to send OTP');
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
