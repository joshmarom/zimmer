<?php
namespace WhiteZimmer\Widgets;

use ElementorPro\Modules\Posts\Widgets\Posts;
use ElementorPro\Modules\QueryControl\Module as Module_Query;

defined( 'ABSPATH' ) || exit; // Exit if accessed directly

class Posts_Gallery extends Posts {

	public function get_name() {
		return 'posts-gallery';
	}

	public function get_title() {
		return __( 'Posts Gallery', 'elementor-pro' );
	}

	public function get_keywords() {
		return [ 'posts', 'cpt', 'item', 'loop', 'query', 'gallery', 'custom post type' ];
	}

	public function get_script_depends() {
		return [ 'posts-gallery' ];
	}

	public function get_style_depends() {
		return [ 'jquery-mosaic' ];
	}

	protected function _register_skins() {}

	public function render() {
		/** @var Module_Query $elementor_query */
		$elementor_query = Module_Query::instance();
		$query = $elementor_query->get_query( $this, $this->get_name(), [
			'posts_per_page' => 6,
		] );

		while ( $query->have_posts() ) {
			$query->the_post();
			$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );
			$width = $thumbnail[1];
			$height = $thumbnail[2];
			?>
			<a class="item withImage" href="<?php the_permalink(); ?>" width="<?php echo $width; ?>" height="<?php echo $height; ?>" style="background-image: url(<?php echo $thumbnail[0]; ?> );">
				<div class="overlay">
					<div class="texts"><h1><?php the_title(); ?></h1></div>
				</div>
			</a>
			<?php
		}
	}
}
