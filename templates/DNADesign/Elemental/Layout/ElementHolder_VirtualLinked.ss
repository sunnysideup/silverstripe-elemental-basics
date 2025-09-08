<% if $LinkedElement %>
<div class="element element--virtual $LinkedElement.SimpleClassName.LowerCase<% if $LinkedElement.StyleVariant %> $LinkedElement.StyleVariant<% end_if %><% if $LinkedElement.ExtraClass %> $LinkedElement.ExtraClass<% end_if %>
top-padding-$LinkedElement.TopPadding top-margin-$LinkedElement.TopMargin bottom-padding-$LinkedElement.BottomPadding bottom-margin-$LinkedElement.BottomMargin backgroundcolour-$LinkedElement.ElementBackgroundColour.CssClass textcolour-$LinkedElement.ElementTextColour.CssClass" id="{$LinkedElement.Anchor}">
    $Element
</div>
<% end_if %>
