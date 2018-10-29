{$blok._editable}
<div class="feature">
    {* Add a field 'image' of type "Image" in Storyblok and enable the img tag below *}
    {* <img src="{$blok.image}" alt="{$blok.name}"> *}

    {* To optimize the image we just uploaded we can use the 'transform' modifier *}
    {* <img src="{$blok.image|transform:'600x400'}" alt="{$blok.name}"> *}
  <h2>{$blok.name}</h2>
</div>

