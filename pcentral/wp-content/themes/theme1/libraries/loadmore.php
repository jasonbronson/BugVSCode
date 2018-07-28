<?php
/**
 * Load More Class
 *
 * This is for ajax calls to get posts
 **/

namespace Libraries;
use Libraries\Loader;
use Libraries\Options;

class LoadMore extends Posts {

    public function __construct(){

        $this->options = new Options();
        
        $loader = new Loader();
        $loader->add_action('wp_ajax_nopriv_loadmore', $this, 'getAll' );
        $loader->add_action('wp_ajax_loadmore', $this, 'getAll' );
        $loader->run();

    }

    public function getAll($postsPerPage = null, $paged = null, $offset = null){

        $offset = isset($_REQUEST['offset']) ? $_REQUEST['offset']:0;
        $perPage = isset($_REQUEST['posts_per_page']) ? $_REQUEST[ 'posts_per_page' ] :get_option( 'posts_per_page' );
        $response = parent::getAll($perPage, null, $offset);
        wp_send_json($response);

    }


}