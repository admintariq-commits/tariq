document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    
    if (!form) return;

    window.verificationForm = function () {
        return {
            form: {
                indexNumber: '',
                registrationNumber: '',
            },
            loading: false,
            statusMessage: '',
            statusClass: '',
            statusIcon: '',

            async submitVerification() {
                if (!this.form.indexNumber.trim()) {
                    this.setError('Please enter your index number');
                    return;
                }

                this.loading = true;
                this.statusMessage = '';

                try {
                    const response = await fetch('{{ route("verify-academic") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        },
                        body: JSON.stringify({
                            index_number: this.form.indexNumber.trim(),
                            registration_number: this.form.registrationNumber.trim() || null,
                        }),
                    });

                    const data = await response.json();

                    if (response.ok) {
                        this.setSuccess(data.message || 'Verification submitted successfully!');
                        setTimeout(() => {
                            window.location.href = '{{ route("graduate.register") }}?verified=' + this.form.indexNumber;
                        }, 2000);
                    } else {
                        this.setError(data.message || 'Verification failed. Please try again.');
                    }
                } catch (error) {
                    this.setError('Network error. Please check your connection and try again.');
                    console.error(error);
                } finally {
                    this.loading = false;
                }
            },

            setSuccess(message) {
                this.statusMessage = message;
                this.statusClass = 'bg-green-50 border-l-4 border-green-500 text-green-900';
                this.statusIcon = 'fas fa-check-circle text-green-600';
            },

            setError(message) {
                this.statusMessage = message;
                this.statusClass = 'bg-red-50 border-l-4 border-red-500 text-red-900';
                this.statusIcon = 'fas fa-exclamation-circle text-red-600';
            },
        };
    };
});
