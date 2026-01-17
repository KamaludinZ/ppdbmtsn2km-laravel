import './bootstrap';

// Alpine.js Countdown function
window.countdown = (expiryDate) => {
    return {
        expiry: new Date(expiryDate).getTime(),
        days: '00',
        hours: '00',
        minutes: '00',
        seconds: '00',
        init() {
            this.update();
            setInterval(() => {
                this.update();
            }, 1000);
        },
        update() {
            const now = new Date().getTime();
            const distance = this.expiry - now;

            if (distance < 0) {
                this.days = '00';
                this.hours = '00';
                this.minutes = '00';
                this.seconds = '00';
                return;
            }

            this.days = Math.floor(distance / (1000 * 60 * 60 * 24)).toString().padStart(2, '0');
            this.hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)).toString().padStart(2, '0');
            this.minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60)).toString().padStart(2, '0');
            this.seconds = Math.floor((distance % (1000 * 60)) / 1000).toString().padStart(2, '0');
        }
    }
}
