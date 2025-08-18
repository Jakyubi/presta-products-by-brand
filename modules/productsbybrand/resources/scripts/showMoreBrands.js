document.addEventListener('DOMContentLoaded', function(){
    const container = document.querySelector('.brands-container');
    const grid = document.querySelector('.brands-grid');
    const button = document.getElementById('toggle-brands');
    let showingAll = false;

    function initialVisibleCount(){
        const width = window.innerWidth;
        if (width <= 360) return 3;
        if (width <= 576) return 4;
        if (width <= 991) return 6;
        return 8;
    }

    function adjustColumns(){
        const containerWidth = container.clientWidth;
        const gap = 10;
        const minItemWidth = 120;
        const columns = Math.floor((containerWidth + gap) / (minItemWidth + gap));
        grid.style.gridTemplateColumns = `repeat(${columns}, 1fr)`;
    }

    function updateVisibility(){
        const items = document.querySelectorAll('.brands-grid .brand-item:not(.toggle-btn)');
        const visibleCount = initialVisibleCount();
        items.forEach((el, index) => {
            if(!showingAll && index >= visibleCount){
                el.classList.add('hidden');
            }else{
                el.classList.remove('hidden');
            }
        });
        button.textContent = showingAll ? 'Show less' : 'See more';
    }

    adjustColumns();
    updateVisibility();

    button.addEventListener('click', function() {
        showingAll = !showingAll;
        updateVisibility();
    });

    window.addEventListener('resize', function(){
        adjustColumns();
        if(!showingAll) updateVisibility();
    });
});
