<?php
/**
 * Sitemap.xml generator
 * https://DOMAINNAME/wp-admin/admin-ajax.php?action=sitemap
 * Keeps it hidden from bot scanners
 *
 */
namespace Libraries;
use Libraries\Loader;

class SitemapGenerator {

  public function __construct(){
    $loader = new Loader();
    $loader->add_action('wp_ajax_nopriv_sitemap', $this, 'createSitemap' );
    $loader->add_action('wp_ajax_sitemap', $this, 'createSitemap' );
    $loader->run();
  }

  public function createSitemap(){
    
    $postsForSitemap = get_posts(array(
        'numberposts' => -1,
        'orderby' => 'modified',
        'post_type'  => array( 'post', 'page' ),
        'order'    => 'DESC'
    ));

    $sitemap = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    $sitemap .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";

    foreach( $postsForSitemap as $post ) {
        setup_postdata( $post );

        $sitemap .= "<url>\n".
          "<loc>" . get_permalink( $post->ID ) . "</loc>\n" .
          "<lastmod>" . date("Y-m-d G:i:s", strtotime($post->post_modified)) . "</lastmod>\n" .
          "<changefreq>monthly</changefreq>\n" .
         "</url>\n";
      }

    $sitemap .= "</urlset>";

    $fp = fopen( THEME_PATH . '/sitemap.xml', 'w' );

    fwrite( $fp, $sitemap );
    fclose( $fp );

  }


}

