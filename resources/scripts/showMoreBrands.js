class BrandsGrid {
  constructor() {
    this.grid = document.querySelector('.brands-grid');
    if (!this.grid) return;
    this.desktopRows = parseInt(this.grid.dataset.desktopRows, 10);
    this.mobileRows = parseInt(this.grid.dataset.mobileRows, 10);
    this.button = document.getElementById('toggle-brands');
    this.showingAll = false;
    this.scrollY = window.scrollY;
    this.updateVisibility();

    if (this.button) {
      this.button.addEventListener('click', () => this.onButtonClick());
    }

    window.addEventListener('resize', () => {
      if (!this.showingAll) this.updateVisibility();
    });
  }

  updateVisibility() {
    const items = Array.from(
      document.querySelectorAll('.brands-grid .brand-item')
    );
    const gridStyle = getComputedStyle(this.grid);
    const columns = gridStyle.gridTemplateColumns.split(' ').length;
    let visibleCount;

    if (window.innerWidth < 768) {
      visibleCount = columns * this.mobileRows;
    } else {
      visibleCount = columns * this.desktopRows - 2;
    }

    let hiddenCount = 0;

    items.forEach((el, index) => {
      if (el.id === 'toggle-brands') return;
      if (!this.showingAll && index >= visibleCount) {
        el.classList.add('hidden');
        hiddenCount++;
      } else {
        el.classList.remove('hidden');
      }
    });

    if (!this.showingAll) {
      if (window.innerWidth >= 768) {
        const lastVisible = items[visibleCount - 1];
        if (lastVisible)
          this.grid.insertBefore(this.button, lastVisible.nextSibling);
      } else {
        this.grid.appendChild(this.button);
      }
    } else {
      this.grid.appendChild(this.button);
    }

    if (this.showingAll) {
      this.button.textContent = 'Show less';
    } else {
      this.button.textContent = `See more (${hiddenCount})`;
    }
  }

  onButtonClick() {
    this.showingAll = !this.showingAll;
    this.updateVisibility();

    window.scrollTo({ top: this.scrollY, behavior: 'smooth' });
    this.button.focus();
  }
}

document.addEventListener('DOMContentLoaded', () => {
  new BrandsGrid();
});
