<?php
/**
 * Smarty {storyblok} demo function plugin
 *
 * Type:     function<br>
 * Name:     storyblok<br>
 *
 * @author   Dominik Angerer <da at storyblok dot com>
 * @param array                    $params   parameters
 * @param Smarty_Internal_Template $template template object
 * @return array
 */
function smarty_function_storyblok($params, $template)
{  
  $storyblok_config = require __DIR__ . '/../../../../../storyblok.php';

  $version = $storyblok_config['version'];
  $token = $storyblok_config['token'];
  $use = 'story';

  $current_slug = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

  // load 'home' as '/' would load a collection of Stories
  if ($current_slug == '/') {
    $current_slug = 'home';
  }

  // override current slug with slug param
  if (!empty($params['slug'])) {
    $current_slug = $params['slug'];
  }

  // validate preview mode: https://www.storyblok.com/docs/Guides/storyblok-latest-js#how-to-validate-if-the-user-is-viewing-your-site-in-the-storyblo
  $sb = $_GET['_storyblok_tk'];
  if (!empty($sb)) {
    $pre_token = $sb['space_id'] . ':' . $token . ':' . $sb['timestamp'];
    $control_token = sha1($pre_token);
    if ($control_token == $sb['token'] && (int)$sb['timestamp'] > strtotime('now') - 3600) {
      $version = 'draft';
    }
  } 

  // CURL can be exchanged for Guzzle or your favorite HTTP Request Client
  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.storyblok.com/v1/cdn/stories/" . $current_slug ."?token=" . $token . "&version=" . $version . "&cv=" . time(),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET"
  ));

  $response = curl_exec($curl);
  $err = curl_error($curl);

  curl_close($curl);

  // use given variable name for template variable assignment
  if(!empty($params['use'])) {
    $use = $params['use'];
  }
  
  $template->assign($use, json_decode($response, true)['story']);
}

?>