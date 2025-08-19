

<div class="brands-container">
<div class="grid-title">Search for products by brand</div>
    <div class="brands-grid">
        {foreach $brands as $brand name=brands}
        <div class="brand-item">
            <a href="{$link->getManufacturerLink($brand.id_manufacturer, $brand.link_rewrite)}">
                <div class="brand-image-wrapper">
                    <img src="{$link->getManufacturerImageLink($brand.id_manufacturer)}" 
                    class="brand-image mb-1" alt="{$brand.name}" />
                </div>
            </a>
            </div>
        {/foreach}

        <button id="toggle-brands" class="btn btn-primary brand-item toggle-btn">See more</button>
        </div>
</div>



<div class="container brands-by-letter">
    <div class="brands-by-letter-grid">
        {foreach $grouped_brands as $letter => $gBrands}
            <div class="letter-block">
                <div class="letter-header">{$letter}</div>
                <ul class="list-unstyled">
                    {foreach $gBrands as $gBrand}
                        <li>{$gBrand.name}</li>
                    {/foreach}
                </ul>
            </div>
        {/foreach}
    </div>
</div>
