<?php
/**
 * Gallery
 *
 * A simple Gallery made to learn Elgg plugins enviroment
 */

elgg_register_event_handler('init', 'system', 'gallery_init');

/**
 * Init Gallery Plugin
 */
function gallery_init()
{
  // Add Library
  elgg_register_library('elgg:gallery', elgg_get_plugins_path().'elgg_gallery/lib/elgg-gallery.php');

  //Add custom CSS
  elgg_extend_view('css/elgg', 'elgg-gallery/css');

  //Add some js files
  $gallery_js = elgg_get_simplecache_url('js', 'elgg-gallery/<name_file>');
  elgg_register_simplecache_view('js/elgg-gallery/<name_file>');
  elgg_register_js('elgg.mygallery',$gallery_js);

	// routing of urls
  elgg_register_page_handler('gallery', 'gallery_page_handler');

	// register actions
	$action_path = elgg_get_plugins_path() . 'elgg-gallery/actions/elgg-gallery';
	elgg_register_action('elgg-gallery/save', "$action_path/save.php");
	elgg_register_action('elgg-gallery/delete', "$action_path/delete.php");
}

/**
 * Dispatches gallery pages.
 * URLs take the form of
 *  All images:       elgg-gallery/all
 *  Gallery Image:    blog/view/<guid>/<title>
 *  New image:        elgg-gallery/add/<guid>
 */
function blog_page_handler($page)
{
  elgg_load_library('elgg:gallery');

  if(!isset($page[0]))
    $page[0] = 'all';

  $page_type = $page[0];
  switch($page_type)
  {
    case 'view':
      $params = gallery_get_page_content_read($page[1]);
      break;
    case 'add':
      gatekeeper();
      $params = gallery_get_page_content_edit($page_type, $page[1]);
      break;
    case 'all':
      $params = gallery_get_page_content_list();
      break;
    default:
      return false;
  }

  if(isset($params['sidebar']))
    $params['sidebar'] .= elgg_view('elgg-gallery/sidebar', array('page' => $page_type));

  $body = elgg_view_layout('content', $params);

  echo elgg_view_page($params['title'], $body);
  return true;
}
