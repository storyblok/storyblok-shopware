{$blok._editable}
<div class="grid">
  {foreach $blok.columns as $column}
    {$component = $column.component}
    {include file="_private/storyblok/$component.tpl" blok=$column}
  {/foreach}
</div>