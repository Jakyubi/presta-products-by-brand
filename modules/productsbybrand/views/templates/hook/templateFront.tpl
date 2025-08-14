

<div class="brands-container">
    <div class="brands-grid">
        {foreach $brands as $brand name=brandLoop}
            <div class="brand-item">
            <div class="brand-image-wrapper">
                <a href="{$link->getManufacturerLink($brand.id_manufacturer, $brand.link_rewrite)}">
                    <img src="{$link->getBaseLink()}/img/m/{$brand.id_manufacturer}.jpg" class="brand-image mb-1"alt="{$brand.name}" />
                </a>
            </div>
            </div>
        {/foreach}

            <button id="toggle-brands" class="btn btn-primary brand-item toggle-btn">See more</button>
    </div>
</div>


</div>
