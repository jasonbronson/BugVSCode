<?php
/**
 * Posts Class
 *
 * This class handles all the methods for retrieving posts.
 **/

namespace Libraries;
use Libraries\Options;

class Posts {

	protected $dataObject;
	protected $authorData;
	protected $options;
	protected $args = array(
		'post_type' => 'post',
		'post_status' => 'publish');

	public function __construct(){

		$this->options = new Options();
	}

	protected function getDataObject($post){

		$authorData = $this->getAuthorById($post->ID, $post->post_author);
		$data = array(
			'id' => $post->ID,
			'title' => $post->post_title,
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
			'short_description' => get_field('blog_short_description', $post->ID),
			'featured_image' => get_field('blog_featured_image', $post->ID),
			'subtitle' => get_field('blog_subtitle', $post->ID)
		);
		
		return $data;
	}

	/**
	 * Gets all data.
	 *
	 * @return array<object>
	 */
	public function getAll( $postsPerPage = null, $paged = null, $offset = null){
		$paged = isset($paged)?$paged:10;
		$perPage = isset($postsPerPage)?$postsPerPage:get_option( 'posts_per_page' );

		$args = array_merge($this->args, 
			array(
				'posts_per_page' => $perPage,
				'offset' => $offset,
				'orderby' => 'post_date',
				'order' => 'DESC'
			)
		);
		$query = new \WP_Query( $args );
		$data = array();
		$authorData = array();
		
		foreach($query->posts as $post ){
			$dataObject = $this->getDataObject($post);
			$data[] = $dataObject;
		}
		$temp = new \stdClass();
		$temp->totalPages = ceil($query->found_posts / $perPage);
		$temp->posts = $data;
		$temp->page = $paged;
		return $temp;
	}

	public function get($id){
		$args = array_merge(
			$this->args, 
			array('p' => $id)
		);
		$query = new \WP_Query( $args );
		foreach($query->posts as $post ){
			$dataObject = $this->getDataObject($post);
			$data = $dataObject;
		}
		return (object) $data;
	}

	public function getAuthorById($postID, $authorID){

		$author = get_field('blog_authors', $postID);
		if(!empty($author) ){
			$author = get_userdata($author);
			$authorData['display_name'] = $author->data->display_name;
			$authorData['login'] = $author->data->user_login;
		}else{
			$author = get_userdata($authorID);
			$authorData['display_name'] = $author->data->display_name;
			$authorData['login'] = $author->data->user_login;
		}
		return $authorData;
	}
}
