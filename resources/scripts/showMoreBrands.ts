class BrandsGridToggle {
  private grid: HTMLElement | null = null;
  private desktopRows: number = 0;
  private mobileRows: number = 0;
  private button: HTMLButtonElement | null = null;
  private showingAll: boolean = false;

  private static readonly DESKTOP_BREAKPOINT = 768;
  private static readonly DESKTOP_VISIBLE_OFFSET = 2;

  constructor() {
    const grid = document.querySelector('.brands-grid');
    if (!grid) return;
    this.grid = grid as HTMLElement;

    this.desktopRows = parseInt(this.grid.dataset.desktopRows || '0', 10);
    this.mobileRows = parseInt(this.grid.dataset.mobileRows || '0', 10);

    const btn = document.getElementById('toggle-brands');
    if (btn instanceof HTMLButtonElement) {
      this.button = btn;
      this.button.addEventListener('click', () => this.onButtonClick());
    }

    this.updateVisibility();

    window.addEventListener('resize', () => {
      if (!this.showingAll) this.updateVisibility();
    });
  }

  private updateVisibility() {
    if (!this.grid) return;
    if (!this.button) return;

    const items = Array.from(
      document.querySelectorAll('.brands-grid .brand-item')
    );
    const gridStyle = getComputedStyle(this.grid);
    const columns = gridStyle.gridTemplateColumns.split(' ').length;

    let visibleCount =
      window.innerWidth < BrandsGridToggle.DESKTOP_BREAKPOINT
        ? columns * this.mobileRows
        : columns * this.desktopRows - BrandsGridToggle.DESKTOP_VISIBLE_OFFSET;

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

    if (
      !this.showingAll &&
      window.innerWidth >= BrandsGridToggle.DESKTOP_BREAKPOINT
    ) {
      const lastVisible = items[visibleCount - 1];
      if (this.grid && this.button && lastVisible) {
        this.grid.insertBefore(this.button, lastVisible.nextSibling);
      }
    } else if (this.grid && this.button) {
      this.grid.appendChild(this.button);
    }

    this.updateButtonLabel(hiddenCount);
  }

  private onButtonClick() {
    this.showingAll = !this.showingAll;
    this.updateVisibility();
    window.scrollTo({ top: window.scrollY, behavior: 'smooth' });
    this.button?.focus();
  }

  private updateButtonLabel(hiddenCount: number) {
    if (!this.button) return;
    this.button.textContent = this.showingAll
      ? 'Show less'
      : `See more (${hiddenCount})`;
  }
}

document.addEventListener('DOMContentLoaded', () => new BrandsGridToggle());
