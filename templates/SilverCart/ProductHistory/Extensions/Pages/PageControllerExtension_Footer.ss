<% if $ProductHistory %>
<section class="mt-5 mb-0 pt-4 px-4 border-top widget">
    <h3 class="d-inline-block"><%t SilverCart\ProductHistory\Model\Product\ProductHistory.RecentlyViewed 'Recently viewed' %></h3>
    <a href="{$ProductHistoryLink}" class="d-inline-block ml-2"><%t SilverCart\ProductHistory\Model\Product\ProductHistory.ShowAll 'Edit or show history' %></a>
    <div class="sly-container">
        <div id="widget-sly-product-history">
            <ul class="slider">
        <% loop $ProductHistory %>
            <% if $Product %>
                <% with $Product %>
                    <% include SilverCart\View\GroupView\ProductSliderSmallEntry %>
                <% end_with %>
            <% end_if %>
        <% end_loop %>
            </ul>
        </div>
        <div class="scrollbar"><div class="handle"><div class="mousearea"></div></div></div>
        <button class="btn prev"><span class="fa fa-chevron-left"></span><span class="sr-only"> prev</span></button>
        <button class="btn next"><span class="sr-only">next </span><span class="fa fa-chevron-right"></span></button>
    </div>
    <script>$(document).ready(function() {silvercart.sly.init($('#widget-sly-product-history'));});</script>
</section>
<% end_if %>