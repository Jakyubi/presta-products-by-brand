document.addEventListener('DOMContentLoaded', function(){
    const grid = document.querySelector('.brands-grid');
    const button = document.getElementById('toggle-brands');
    let showingAll = false;

    function updateVisibility(){
        const items = Array.from(document.querySelectorAll('.brands-grid .brand-item'));
        const gridStyle = getComputedStyle(grid);
        const columns = gridStyle.gridTemplateColumns.split(' ').length;
        let visibleCount;

        if(window.innerWidth < 768){
            visibleCount = columns * 4;
            button.style.gridColumn = '1 / -1';
        }else{
            visibleCount = columns + (columns -2);
            button.style.gridColumn = `span 2`;
        }

        let hiddenCount = 0;

        items.forEach((el, index) => {
            if(el.id === 'toggle-brands') return;
            if(!showingAll && index >= visibleCount){
                el.classList.add('hidden');
                hiddenCount++;
            }else{
                el.classList.remove('hidden');
            }
        });

        if(!showingAll){
            if(window.innerWidth >= 768){
                const lastVisible = items[visibleCount - 1];
                if(lastVisible) grid.insertBefore(button, lastVisible.nextSibling);
            }else{
                grid.appendChild(button);
            }
        }else{
            grid.appendChild(button);
        }

        if(showingAll){
            button.textContent = 'Show less';
        } else {
            button.textContent = `See more (${hiddenCount})`;
        }
    }

  
    updateVisibility();

    button.addEventListener('click', function() {
        showingAll = !showingAll;
        updateVisibility();
    });

    window.addEventListener('resize', function(){

        if(!showingAll) updateVisibility();
    });
});
