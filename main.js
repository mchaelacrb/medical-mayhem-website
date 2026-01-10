// Select all sliders
let sliders = document.querySelectorAll('.slider');

// Loop over each slider to set up independent controls
sliders.forEach((slider, index) => {
    let list = slider.querySelector('.list');
    let items = slider.querySelectorAll('.item');
    let dots = slider.querySelectorAll('.dots li');
    let prev = slider.querySelector('#prev');
    let next = slider.querySelector('#next');

    let active = 0;
    let lengthItems = items.length - 1;

    next.onclick = function () {
        active = (active + 1 > lengthItems) ? 0 : active + 1;
        reloadSlider();
    };
    prev.onclick = function () {
        active = (active - 1 < 0) ? lengthItems : active - 1;
        reloadSlider();
    };
    
    let refreshSlider = setInterval(() => { next.click() }, 3000);

    function reloadSlider() {
        let checkLeft = items[active].offsetLeft;
        list.style.left = -checkLeft + 'px';

        let lastActiveDot = slider.querySelector('.dots li.active');
        lastActiveDot.classList.remove('active');
        dots[active].classList.add('active');
        
        clearInterval(refreshSlider);
        refreshSlider = setInterval(() => { next.click() }, 5000);
    }

    dots.forEach((li, key) => {
        li.addEventListener('click', function () {
            active = key;
            reloadSlider();
        });
    });
});