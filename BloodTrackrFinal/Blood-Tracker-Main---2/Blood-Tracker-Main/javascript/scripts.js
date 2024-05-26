// Buttons
const userButton = document.querySelector("#user-btn");
const ratingButton = document.querySelector('#rating-btn');
const ratingExitButton = document.querySelector('#rating-exit');
const ratingDesc = document.querySelector('.rating-desc');
const radioInputs = document.querySelectorAll('.radio-input');

// Modals
const userPopModal = document.querySelector(".logged");
const ratingModal = document.querySelector('.rating-pop');

// Exit Buttons
ratingExitButton.addEventListener('click', () => {
    ratingModal.classList.remove('active');
});

// Pop up Modals
userButton.addEventListener('click', () => {
    userPopModal.classList.toggle('active');
});

ratingButton.addEventListener('click', () => {
    ratingModal.classList.toggle('active');
});  

// Star Clicks
radioInputs.forEach(input => {
    input.addEventListener('click', () => {
        switch(input.value) {
            case '5':
                ratingDesc.textContent = 'It was awesome!';
                break;
            case '4':
                ratingDesc.textContent = 'It was worth the money!';
                break;
            case '3':
                ratingDesc.textContent = 'It was just okay';
                break;
            case '2':
                ratingDesc.textContent = 'It was poor quality';
                break;
            case '1':
                ratingDesc.textContent = 'It was terrible!';
                break;
            default:
                ratingDesc.textContent = '';
        }
    });
});