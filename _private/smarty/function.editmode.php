<?php
/**
 * Smarty {editmode} demo function plugin
 * 
 * Returns an initialized storyblok script if the version is 'draft'
 * or user is in edit mode.
 *
 * Type:     function<br>
 * Name:     editmode<br>
 *
 * @author   Dominik Angerer <da at storyblok dot com>
 * @param array                    $params   parameters
 * @param Smarty_Internal_Template $template template object
 * @return string
 */
function smarty_function_editmode($params, $template)
{  
  $storyblok_config = require __DIR__ . '/../../../../../storyblok.php';

  $version = $storyblok_config['version'];
  $token = $storyblok_config['token'];

  // validate preview mode: https://www.storyblok.com/docs/Guides/storyblok-latest-js#how-to-validate-if-the-user-is-viewing-your-site-in-the-storyblo
  $sb = $_GET['_storyblok_tk'];
  if (!empty($sb)) {
      $pre_token = $sb['space_id'] . ':' . $token . ':' . $sb['timestamp'];
      $token = sha1($pre_token);
      if ($token == $sb['token'] && (int)$sb['timestamp'] > strtotime('now') - 3600) {
        $version = 'draft';
      }
  }  

  // return editmode script only in draft mode
  // https://www.storyblok.com/docs/Guides/storyblok-latest-js#the-storyblok-bridge
  if ($version == 'draft') {
  return <<<EOT
<script src="//app.storyblok.com/f/storyblok-latest.js?t=$token" type="text/javascript"></script>
<script type="text/javascript">
storyblok.init()

storyblok.on(['published', 'change', 'unpublish'], function() {
  location.reload(true)
})

storyblok.pingEditor(function() {
  if (storyblok.inEditor) {
    storyblok.enterEditmode()
  }
})
</script>
EOT;
  } else {
    return '';
  }
}
?>