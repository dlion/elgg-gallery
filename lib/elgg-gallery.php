<?php
/**
 * Library to use Elgg-gallery
 *
 */

/**
 * Get page components to add image.
 *
 * @return array
 */
function gallery_get_page_content_add()
{
    elgg_load_library('elgg:file');
    $vars = array('enctype' => 'multipart/form-data');
    $body_vars = file_prepare_form_vars();

    //Create Array values to return
    $return = array(
        'title' => 'Aggiungi Immagine',
        'content' => elgg_view_form('elgg-gallery/uppa', $vars,$body_vars)
        );

    //Returns array
    return $return;
}

/**
 * Get page components to view all images
 *
 * @return  array
 */
function gallery_get_page_content_all()
{
    //Take entities from user
    $entita = elgg_get_entities(array('types' => array('object'),
                                      'subtypes' => array('file'),
                                      'owner_guid' => elgg_get_logged_in_user_guid(),
                                      'limit' => 0,
                                      'order_by' => 'time_created asc'
                                ));

    if(count($entita) > 0)
        foreach ($entita as $key)
            $immagine .= elgg_view('elgg-gallery/all',array('title' => $key['title'],
                                                            'icon' => $key->getIconURL('medium'),
                                                            'img_guid' => $key['guid']
                                                            ));
    else
        $immagine = elgg_view('elgg-gallery/error', array('error' => 'Nessuna Immagine nella tua Galleria!'));

    $return = array(
        'title' => 'Tutte le Immagini',
        'content' => $immagine);

    return $return;
}