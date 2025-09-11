
{if $gridEnabled}
<div class="brands-container">
<div class="grid-title">Search for products by brand</div>
    <div class="brands-grid"
        data-desktop-rows="{$desktopRows}"
        data-mobile-rows="{$mobileRows}">
        {foreach $brands as $brand}
        <div class="brand-item">
            <a href="{$link->getManufacturerLink($brand.id_manufacturer, $brand.link_rewrite)}">
                <div class="brand-image-wrapper">
                    <img src="{$link->getManufacturerImageLink($brand.id_manufacturer)}" 
                    class="brand-image mb-1" alt="{$brand.name}" />
                </div>
            </a>
        </div>
        {/foreach}

        <button id="toggle-brands" class="btn brand-item toggle-btn">See more</button>
    </div>
</div>
{/if}

{if $listEnabled}
<div class="brands-by-letter-container">
    <div class="brands-by-letter-columns">
        {foreach $grouped_brands as $letter => $gBrands}
            <div class="brand-by-letter-group">
                <div class="letter-header">{$letter}</div>
                <ul class="brand-list list-unstyled">
                    {foreach $gBrands as $gBrand}
                        <li class="brand-list-item">
                            <a href="{$link->getManufacturerLink($gBrand.id_manufacturer, $gBrand.link_rewrite)}">
                            {$gBrand.name}
                            </a>
                        </li>
                    {/foreach}
                </ul>
            </div>
        {/foreach}
    </div>
</div>
{/if}
