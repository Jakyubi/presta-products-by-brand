document.addEventListener('DOMContentLoaded', function(){
    const container = document.querySelector('.brands-container');
    const grid = document.querySelector('.brands-grid');
    const button = document.getElementById('toggle-brands');
    let showingAll = false;

    function initialVisibleCount(){
        const containerWidth = container.clientWidth;
        const gap = 10;
        const minItemWidth = 120;
        const columns = Math.floor((containerWidth + gap) / (minItemWidth + gap));
        const rowsToShow = 2;
        return columns * rowsToShow;
    }

    function adjustColumns(){
        const containerWidth = container.clientWidth;
        const gap = 10;
        const minItemWidth = 120;
        const columns = Math.floor((containerWidth + gap) / (minItemWidth + gap));
        grid.style.gridTemplateColumns = `repeat(${columns}, 1fr)`;
    }

    function updateVisibility(){
        const items = document.querySelectorAll('.brands-grid .brand-item');
        const visibleCount = initialVisibleCount();
        let hiddenCount = 0;

        items.forEach((el, index) => {
            if(!showingAll && index >= visibleCount){
                el.classList.add('hidden');
                hiddenCount++;
            }else{
                el.classList.remove('hidden');
            }
        });
        if(showingAll){
            button.textContent = 'Show less';
        } else {
            button.textContent = `See more (${hiddenCount})`;
        }
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
