<div class="row">
    <section id="content-main" class="col-12 col-md-9">
        <h2 class="sr-only">{$Title}</h2>
        <% include SilverCart/Model/Pages/BreadCrumbs %>
        <article>
            <header><h1><%t SilverCart\ProductHistory\Model\Product\ProductHistory.RecentlyViewed 'Recently viewed' %></h1></header>
        <% if $RemovedProduct %>
            <% with $RemovedProduct %>
            <div class="alert alert-info clearfix">
                <a href="{$Link}"><img class="float-left mr-2 thumbnail" src="{$ListImage.Pad(40,40).URL}" alt="{$Title}" /></a>
                <strong><span class="fa fa-check"></span> <%t SilverCart\ProductHistory\Model\Product\ProductHistory.SuccessfullyRemovedTitle 'Product was removed from your history.' %></strong><br/>
                <%t SilverCart\ProductHistory\Model\Product\ProductHistory.SuccessfullyRemoved '<a href="{link}">{title}</a> was removed from your history.' link=$Link title=$Title %>
            </div>
            <% end_with %>
        <% end_if %>
        <% if $ProductHistory %>
            <div class="row">
                <% loop $ProductHistory %>
                    <% if $Product %>
                        <% with $Product %>
                            <% include SilverCart\View\GroupView\ProductHistoryTile %>
                        <% end_with %>
                    <% end_if %>
                <% end_loop %>
            </div>
        <% else %>
            <div class="alert alert-info"><span class="fa fa-info-circle"></span> <%t SilverCart\ProductHistory\Model\Product\ProductHistory.NoHistory 'Your history is empty.' %></div>
        <% end_if %>
        </article>
        <% include SilverCart/Model/Pages/WidgetSetContent %>
    </section>
    <aside class="col-12 col-md-3">
        {$SubNavigation}
        {$InsertWidgetArea(Sidebar)}
    </aside>
</div>




