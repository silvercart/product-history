<div class="col-12 col-sm-6 col-lg-6 col-xl-4 col-xxl-3 mb-3 d-flex">
    <div class="card card-product clearfix w-100">
        <div class="thumbnail text-center">
        <% if $PriceIsLowerThanMsr || $isNewProduct %>
            <span class="position-absolute top-0 left-2">
            <% if $PriceIsLowerThanMsr %>
                <span class="badge badge-secondary" title="<%t SilverCart\Model\Product\Product.Sale 'Sale' %>"><i><%t SilverCart\Model\Product\Product.Sale 'Sale' %>!</i></span>
            <% end_if %>
            <% if $isNewProduct %>
                <span class="badge badge-primary" title="<%t SilverCart\Model\Product\Product.New 'New' %>"><i><%t SilverCart\Model\Product\Product.New 'New' %>!</i></span>
            <% end_if %>
            </span>
        <% end_if %>
        <% if $ListImage %>
            <a class="d-inline-block" href="{$Link}" title="<%t SilverCart\Model\Pages\Page.SHOW_DETAILS_FOR 'Show details for {title}' title=$Title %>">
                <img class="img-fluid lazyload" src="{$BaseHref}resources/vendor/silvercart/silvercart/client/img/loader-circle.gif" data-src="{$ListImage.Pad(400,240).URL}" alt="{$Title}" />
            </a>
        <% end_if %>
        </div>
        <div class="sc-product-title card-header py-1">
            <h2 class="card-title h5 mb-0">
                <a href="{$Link}" title="<%t SilverCart\Model\Pages\Page.SHOW_DETAILS_FOR 'Show details for {title}' title=$Title %>">{$Title.HTML}</a>
            </h2>
        </div>
        <div class="card-body py-0">
            <div class="sc-product-price product-price text-center">
                <span class="price">
                <% if $PriceIsLowerThanMsr %>
                    <span class="text-line-through">{$MSRPrice.Nice}</span>
                    <strong class="text-danger" id="product-price-{$ID}">{$PriceNice}</strong>
                <% else %>
                    <strong id="product-price-{$ID}">{$PriceNice}</strong>
                <% end_if %>
                </span>
            </div>
        </div>
        <div class="card-footer pt-1 text-center">
            <a class="btn btn-light" href="{$Link}" title="<%t SilverCart\Model\Pages\Page.SHOW_DETAILS_FOR 'Show details for {title}' title=$Title %>" data-placement="top" data-toggle="tooltip">
                <span class="fa fa-info-circle"></span> <%t SilverCart\Model\Pages\Page.DETAILS 'Details' %></a>
            <a class="btn btn-light" href="{$CurrentPage.RemoveFromHistoryLink($ID)}" title="<%t SilverCart\ProductHistory\Model\Product\ProductHistory.RemoveFrom 'Remove from history: {title}' title=$Title %>" data-placement="top" data-toggle="tooltip">
                <span class="fa fa-minus-circle"></span> <%t SilverCart\ProductHistory\Model\Product\ProductHistory.Remove 'Remove' %></a>
        </div>
    </div>
</div>