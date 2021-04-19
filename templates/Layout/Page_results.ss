<section id="search">
    <div class="container">
        <h1>$Title</h1>
        <% if $Query %>
            <p class="search-query alert alert-info"><%t SimpleSearch.YOUSEARCHEDFOR "You searched for <strong>{Query}</strong>" Query=$Query %></p>
        <% end_if %>

        <% if $Results %>
        <ul class="list-group mb-3">
            <% loop $Results %>
            <li class="search-item list-group-item">
                <div class="d-flex w-100 justify-content-between">
                    <h2>
                    <a href="$Link"><% if $MenuTitle %>$MenuTitle<% else %>$Title<% end_if %></a>
                    </h2>
                    <small>$LastEdited.ago</small>
                </div>

                <% if $Content %>
                <p>$Content.LimitWordCount</p>
                <% else_if $Description %>
                <p>$Description.LimitWordCount</p>
                <% end_if %>
                <a class="search-readmore" href="$Link"><%t SimpleSearch.READMORE "Read more about '{Title}'" Title=$Title %></a>
            </li>
            <% end_loop %>
        </ul>

        <% with Results %>
        <% include Pagination %>
        <% end_with %>

        <% else %>
        <p><%t Page_SimpleSearchresults.NORESULTS "No results matched your search query." %></p>
        <% end_if %>
    </div>
</section>
