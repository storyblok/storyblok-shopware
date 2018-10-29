<?php
/**
 * 
 * Smarty {transform} demo modifier plugin
 * 
 * Uses Storyblok's Image Service to transform/resize/optimize images 
 * uploaded to Storyblok. 
 * 
 * Full documentation for param: https://www.storyblok.com/docs/image-service
 *
 * Type:     modifier<br>
 * Name:     transform<br>
 *
 * @author   Dominik Angerer <da at storyblok dot com>
 * @param string  $image
 * @param string  $param
 * @return string
 */
function smarty_modifier_transform($image, $param = '')
{
  $imageService = '//img2.storyblok.com/';
  $resource = str_replace('//a.storyblok.com', '', $image);
  return $imageService . $param . $resource;
}
