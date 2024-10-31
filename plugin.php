<?php
/*
Plugin Name: Page Presentation Shortcode
Plugin URI: 
Description: This shortcode enables you to create a rolling page/post presentation sequence by redirecting 
the current post/page after waiting for a choosen number of seconds.

If the destination page is not in a published state the shortcode processes each destination page's shortcode until it find one that is.

[pagepresenter id='101' after='20']
[pagepresenter slug='my_page_slug' after='20']
[pagepresenter url='http://www.domain.com/page-1' after='20']
[pagepresenter url='/page-1' after='20']

If the destination post/page doesn't live in the same Wordpress installation then no redirection will occur.

Based on the excellent Shortcode Redirect: https://wordpress.org/support/view/plugin-reviews/shortcode-redirect 
Author: alineainteractive
Version: 1.1.0
Author URI: www.alinea.co

GNU General Public License, Free Software Foundation <http://creativecommons.org/licenses/GPL/2.0/>
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

//this holds the initial delay of the first encountered shortcode
$delay = null;

add_shortcode('pagepresenter', 'do_pagepresenter');
function do_pagepresenter($attributes, $content, $tag)
{
    global $delay;
    ob_start();
    
    $debug_mode = defined('WP_DEBUG') && WP_DEBUG || true;
    
    $attrURL      = (isset($attributes['url']) && !empty($attributes['url']))
                    ? esc_url($attributes['url'])
                    : "";
    $attrDelayInSeconds = is_null($delay)
                        ?(isset($attributes['after']) && !empty($attributes['after']))
                                ? esc_attr($attributes['after'])
                                : "0"
                        : $delay;
    $delay = $attrDelayInSeconds;
                    

    $attrSlug   = (isset($attributes['slug']) && !empty($attributes['slug']))
                    ? $attributes['slug']
                    : "";
    $attrID     = (isset($attributes['id']) && !empty($attributes['id']))
                    ? $attributes['id']
                    : null;
    
    //use postID if no url supplied
    if( !is_null($attrID) && empty($attrURL) ) {

        $attrURL = esc_url( get_permalink( $attrID ) );
        if( $debug_mode ) {
            echo "<script>console.log('".__FUNCTION__.": post/page ID supplied', {id:'$attrID',URL:'$attrURL'});</script>\n";
        }
    }

    //use post slug if no url supplied
    if( !empty($attrSlug) && empty($attrURL) ) {

        $attrURL = esc_url( get_permalink( get_page_by_path( $attrSlug ) ) );
        if( $debug_mode ) {
            echo "<script>console.log('".__FUNCTION__.": post/page slug supplied', {slug:'$attrSlug',URL:'$attrURL'});</script>\n";
        }
    }

    $destination_id = url_to_postid( $attrURL );
    
    //ensure url is valid and within this installation
    if(!empty($attrURL) && $destination_id){
            
        $status = get_post_status($destination_id);
        $isPublished = !strcasecmp('publish', $status);

        if( $debug_mode ) {
            echo "<script>console.log('".__FUNCTION__.": will redirect to post/page', {id :$destination_id, delay: $attrDelayInSeconds, URL:'$attrURL',status:'$status',isPublished:".json_encode($isPublished)."});</script>\n";
        }

        if( !$isPublished ) {

            //get next published slide from the destination shortcode
            if( $debug_mode ) {                            
                echo "<script>console.log('".__FUNCTION__.": destination ID $destination_id ($attrURL) is not published');</script>\n";
            }
            
            $content_post = get_post($destination_id);

            //extract short codes
            preg_match_all('/'. get_shortcode_regex() .'/s', $content_post->post_content, $matches);
            
            if( $debug_mode ) {
                echo "<script>console.log('".__FUNCTION__.": parsing destination content', ".json_encode(array(regex=>get_shortcode_regex(),post=>$content_post, matches=>$matches)).");</script>\n";
            }
            
            //filter for out shortcode
            if( $matches && isset($matches[2]) && is_array($matches[2]) ) {
                
                    foreach( $matches[2] as $k => $v) {

                    if ( !strcasecmp($tag, $v) ) {

                        $shortcode = $matches[0][$k];
                        if( $debug_mode ) {
                            echo "<script>console.log('".__FUNCTION__.": executing shortcode for next destination', {id :'$destination_id',shortcode: ".json_encode($shortcode)."});</script>\n";
                        }

                        echo do_shortcode($shortcode);
                        return;
                    }
                }
            }
        }
        else {
?>
        <meta http-equiv="refresh" content="<?php echo $attrDelayInSeconds; ?>; url=<?php echo $attrURL; ?>">
<?php
       }
    }
    else {

        if( $debug_mode ) {
            echo "<script>console.log('".__FUNCTION__.": destination post/page is invalid', {id :'$destination_id ',URL:'$attrURL'});</script>\n";
        }    
    }
    
    return ob_get_clean();
}
?>
