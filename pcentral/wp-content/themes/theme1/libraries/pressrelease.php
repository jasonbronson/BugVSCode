<?php
/**
 * Press Release Class
 *
 * This is for press release custom post type
 */
namespace Libraries;
use Libraries\Options;

class PressRelease extends Posts {

	protected $options;
	
    public function __construct(){
		$this->options = new Options();
	}

	public function getAll($postsPerPage = null, $paged = null, $offset = null){
		$this->args = array(
			'post_type' => 'pressrelease',
			'post_status' => 'publish'
		);
		return parent::getAll($postsPerPage, $paged, $offset);
	}

	public function get($id){
		$this->args = array(
			'post_type' => 'pressrelease',
			'post_status' => 'publish',
			'p' => $id
		);
		return parent::get($id);
	}

	protected function getDataObject($post){
		$authorData = $this->getAuthorById($post->ID, $post->post_author);
		$data = (object) array(
			'id' => $post->ID,
			'title' => $post->post_title,
			'subtitle' => get_field('press_release_subtitle', $post->ID),
			'content' => apply_filters( 'the_content', $post->post_content),
			'url' => $post->post_name,
			'blog_title' => $post->post_title,
			'author_display_name' => $authorData['display_name'],
			'author_user_login' => $authorData['login'],
			'caption' => get_the_post_thumbnail_caption($post),
			'image_alt' => get_post_meta(get_post_thumbnail_id( $post->ID ), '_wp_attachment_image_alt', true),
			'thumbnail' => get_the_post_thumbnail_url( $post->ID, 'thumbnail' ),
			'image_fullsize' => get_the_post_thumbnail_url( $post->ID, 'full' ),
			'image_large' => get_the_post_thumbnail_url( $post->ID, 'large' ),
			'image_medium' => get_the_post_thumbnail_url( $post->ID, 'medium' ),
			'date' => $post->post_date,
			'date_site_format' => date($this->options->getDateFormat(), strtotime($post->post_date)),
			'permalink' => get_the_permalink($post->ID),
			'short_description' => get_field('press_release_short_description', $post->ID),
            'featured_image' => get_field('press_release_featured_image', $post->ID),
            'description_overview' => get_field('press_release_overview', $post->ID),
            'offsite_link_enabled' => get_field('press_release_offsite_link_enabled', $post->ID),
			'offsite_link_url' => get_field('press_release_offsite_link', $post->ID),
			'location' => get_field('press_release_location', $post->ID),
            
        );
        
		return $data;
	}
}
