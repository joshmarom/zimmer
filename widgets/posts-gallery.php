<?php
namespace WhiteZimmer\Widgets;

use Elementor\Controls_Manager;
use ElementorPro\Modules\Posts\Widgets\Posts;
use ElementorPro\Modules\QueryControl\Module as Module_Query;

defined( 'ABSPATH' ) || exit; // Exit if accessed directly

class Posts_Gallery extends Posts {

	public function get_name() {
		return 'posts-gallery';
	}

	public function get_title() {
		return __( 'Posts Gallery', 'zimmer' );
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

	public function _register_controls() {
		parent::_register_controls();

		$this->remove_control( 'section_layout' );

		$this->start_controls_section(
			'section_mosaic_settings',
			[
				'label' => __( 'Mosaic Settings', 'elementor' ),
			]
		);

		$this->add_control(
			'max_row_height',
			[
				'label' => __( 'Max Row Height', 'zimmer' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 400,
				'min' => 100,
				'max' => 1000,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'outer_margin',
			[
				'label' => __( 'Outer Margin', 'zimmer' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'inner_gap',
			[
				'label' => __( 'Inner Gap', 'zimmer' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'max_row_height_policy',
			[
				'label' => __( 'Max Row Height Policy', 'zimmer' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'skip' => __( 'Skip', 'zimmer' ),
					'crop' => __( 'Crop', 'zimmer' ),
					'oversize' => __( 'Oversize', 'zimmer' ),
					'tail' => __( 'Tail', 'zimmer' ),
				],
				'default' => 'skip',
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();
	}

	protected function _register_skins() {}

	public function render() {
		/** @var Module_Query $elementor_query */
		$elementor_query = Module_Query::instance();
		$query = $elementor_query->get_query( $this, $this->get_name(), [
			'posts_per_page' => 10,
		] );

		while ( $query->have_posts() ) {
			$query->the_post();
			$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );
			$width = $thumbnail[1];
			$height = $thumbnail[2];
			?>
			<a class="item withImage" href="<?php the_permalink(); ?>" width="<?php echo $width; ?>" height="<?php echo $height; ?>"
			   style="background-image: url(<?php echo $thumbnail[0]; ?> );">
				<div class="overlay">
					<div class="texts"><h1><?php the_title(); ?></h1></div>
				</div>
			</a>
			<?php
		}
	}
}
