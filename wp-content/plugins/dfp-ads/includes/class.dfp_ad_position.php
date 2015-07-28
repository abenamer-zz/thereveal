<?php
/** 
 * Class for Ad Positions
 *
 * Holds data for an ad position.
 *
 * @link  http://www.chriwgerber.com/dfp-ads/
 * @since 0.0.1
 *
 * @package    WordPress
 * @subpackage DFP-Ads
 */

class DFP_Ad_Position {

	/**
	 * ID of the CPT
	 *
	 * @access public
	 * @since 0.0.1
	 *
	 * @var int
	 */
	public $post_id;

	/**
	 * Title of the position
	 *
	 * @access public
	 * @since 0.0.1
	 *
	 * @var string
	 */
	public $title;

	/**
	 * Name of the Ad Slot
	 * Ex. SNG_ROS_Leaderboard
	 *
	 * @access public
	 * @since 0.0.1
	 *
	 * @var string
	 */
	public $ad_name;

	/**
	 * Div ID of the ad position
	 * Ex. div-gpt-ad-1375829763909
	 *
	 * @access public
	 * @since 0.0.1
	 *
	 * @var string
	 */
	public $position_tag;

	/**
	 * Ad sizes. Slot with multiple sizes needs to be nested.
	 * Ex. [728, 90] or [ [728, 90], [970, 90] ]
	 *
	 * @access public
	 * @since 0.0.1
	 *
	 * @var array
	 */
	public $sizes = array();

	/**
	 * Defines whether the slot should include Out of Page position
	 *
	 * @access public
	 * @since 0.0.1
	 *
	 * @var bool
	 */
	public $out_of_page = false;

	/**
	 * Defines targeting for the ad slot
	 *
	 * Targeting should be defined as an array of key => value pairs
	 *
	 * @access public
	 * @since 0.0.1
	 *
	 * @var array
	 */
	public $targeting = array();

	/**
	 * PHP5 Constructor
	 *
	 * Constructs the object using the information provided by default in every installation. Values
	 * will come from CPT meta data.
	 *
	 * @access public
	 * @since 0.0.1
	 *
	 * @param $id int|null Post ID to grab the post object and create the position
	 */
	public function __construct( $id = null ) {

		/**
		 * @param $position WP_Post
		 */
		if (
			(
				$id !== null &&
		                $position = get_post( $id )
			) &&
			$position->post_type === 'dfp_ads'
	        ) {
			$meta = get_post_meta( $position->ID );
			$this->post_id      = $id;
			$this->title        = $position->post_title;
			$this->ad_name      = $meta['dfp_ad_code'][0];
			$this->position_tag = strtolower('Ad_Pos_' . $this->ad_name);
			$this->sizes        = dfp_get_ad_sizes($meta['dfp_position_sizes'][0]);
			$this->out_of_page  = ( $meta['dfp_out_of_page'][0] ? true : false );
		}
	}

	/**
	 * Create ad position HTML
	 *
	 * @access public
	 * @since 0.0.1
	 *
	 * @return mixed
	 */
	public function display_position( ) {
		printf( __( '<!-- %1s -->', 'dfp-ads' ), $this->ad_name );
		?>
		<div id='<?php _e( $this->position_tag, 'dfp-ads'); ?>' class="<?php _e( $this->position_tag, 'dfp-ads'); ?> <?php _e( $this->ad_name, 'dfp-ads' ); ?>">
			<script type='text/javascript'>
				googletag.cmd.push(function() { googletag.display('<?php _e( $this->position_tag, 'dfp-ads'); ?>'); });
			</script>
		</div>
		<?php
	}

}