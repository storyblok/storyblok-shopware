# Demo Setup for Shopware with Storyblok

Storyblok’s Content Management System (CMS) platform is the perfect tool to centralize, organize and share all your digital content. Storyblok’s CMS is easy-to-use, yet rich on functionality, enabling you to save time and cut out the hassle when working with content and collaborating with others. 

![Storyblok Edit mode with Shopware demo shop](https://a.storyblok.com/f/39898/3354x1820/2d773017bc/bildschirmfoto-2018-10-29-um-12-48-02.jpg)

With Storyblok’s Components, you can easily collaborate and communicate the right story for your products. Tailor personal, emotionally- engaging and SEO boosting content for your product pages. Enrich your existing e-commerce solution now, because your customers expect more than generic product information.

## How to use

1. Copy the `_private` folder in your Theme directory
2. Copy the `storyblok.php` configuration file in your shopware base
3. Add `hooks` in the `.tpl` you want to have Storyblok enabled

## Configuration

The value you can put in `storyblok.php`

```
<?php return array (
  'token' => 'AwyueJSppBCDcfDb0yvOMwtt', // your own preview token
  'version' => 'draft'                   // draft or published
);
```

## Template Function

Add the below code in your `.tpl` file of choice (for example: `/themes/Frontend/Bare/frontend/custom/index.tpl`).

```
{* 
  Use custom smarty function to access content via Storyblok API
  This will assign the response object from Storyblok to the variable name
  passed as use.
*}
{storyblok use='story'}

{* output HTML comment for editing *}
{$story.content._editable}
<div>
{* 
  loop through the component tree and include the specific tpl files 
  with the current components context 
*}
{foreach $story.content.body as $blok}
  {$component = $blok.component}
  {include file="_private/storyblok/$component.tpl" blok=$blok}
{/foreach}
</div>
```

### Load Content

```
{storyblok use='story' slug='home'}
```

Allows you to load content from Storyblok's API. In this demo setup we only load one Story, but with another filter or a different `params` you can modifier the behavior as you like. The file you would need to change is `_private/smarty/function.storyblok.php`.

#### Params

| param | description                                                                 |
|-------|-----------------------------------------------------------------------------|
| use   | Define the template variable that should be used to store the response data |
| slug  | Load a specific Story with given slug; ignores current request path         |


### Use Content

```
{* default fields *}
{$story.name}
{$story.full_slug}
{$story.created_at}

{* block type include *}
{foreach $story.content.body as $blok}
  {$component = $blok.component}
  {include file="_private/storyblok/$component.tpl" blok=$blok}
{/foreach}
```

You can access all fields you define in Storyblok in the `$story.content` variable (or the name you passed in `use`). Storyblok allows you to define components with a specific name and a set of fields. To include templates specific for those given components we will use the `$story.content.body` field that is defined with the type "Blocks" in Storyblok, meaning it allows nested components that we can include. For each included template we will pass the current component's data as the `blok` variable so we can use the field directly inside that component.

### Edit Content

To edit content with Storyblok we actually do not need to define field in Shopware as this is done in Storyblok. The default components in this example will be created for your automatically if you start with an empty "API only" Project in Storyblok.

With `{$story.content._editable}` (content type level) or `{$blok._editable}` (in components) we will output HTML comments above the element that should be clickable in Storyblok's preview. With our JavaScript Bridge, that we will include in the base template, we attach event handler with parameteres that the Storyblok Editor will use to open your content entries. How this works in more detail [can be found in our documentation](https://www.storyblok.com/docs/the-editor#visual-composer).

> Attention: Make sure to Enable HTML Comments in your Shopware instance; otherwise those HTML comments will be removed.

### Create Component Templates

For this demo we include a `.tpl` file for each component defined in Storyblok resulting in a reusable set of components for your current and future projects. In the `_private/storyblok` folder we've added basic smarty templates for `teaser`, `grid` and `feature` which are the default components we will ship if you create a new project in Storyblok. You can modified and access all smarty functionalities in there. With the `$blok` variable you will be able to access the current instance variables for that component because we pass them as they [get included](#use-content). You can modify the directy as you like.

### Initialize Editmode

```
{editmode}
```

To initialize the editmode for Storyblok you can use the `_private/smarty/function.editmode.php`. Open your Theme's base index template (eg. `/themes/Frontend/Bare/frontend/index/index.tpl`) and add `{editmode}` at the bottom. It will automatically include the JavaScript Bridge with your token from the `storyblok.php` configuration.

### Image Optimization

```
{* <img src="{$blok.image|transform:'600x400'}"> *}
```

We've created a basic modifier called `transform` that you can use the [resize, optimize and transform](https://www.storyblok.com/docs/image-service) images uploaded to Storyblok as you like. You can find the line above in the `_private/storyblok/feature.tpl`, make sure to add the `image` field to the component `feautre` in Storyblok and upload an image to access it in your template. If you want to check out the code for the modifier: `_private/smarty/modifier.transform.php`.
