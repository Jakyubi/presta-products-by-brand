<div>
<table>
    {foreach $brands as $brand}
        <tr>
        <a href="{$link->getManufacturerLink($brand.id_manufacturer, $brand.link_rewrite)}">
            <img src="{$link->getBaseLink()}/img/m/{$brand.id_manufacturer}.jpg" alt="{$brand.name}" />
        </a>
        </tr>
    {/foreach}
</table>
</div>
