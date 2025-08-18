document.addEventListener('DOMContentLoaded', function(){
    const button = document.getElementById('toggle-brands');
    const hiddenItems = document.querySelectorAll('.brands-grid .brand-item.hidden:not(.toggle-btn)');
    
    button.addEventListener('click', function(){
        const isHidden = hiddenItems[0].classList.contains('hidden');

        hiddenItems.forEach(el => el.classList.toggle('hidden', !isHidden));
        button.textContent = isHidden ? 'Show less' : 'See more';
    });
});
