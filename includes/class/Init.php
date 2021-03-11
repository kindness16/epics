<?php
/**
 * @author : Jegtheme
 */

namespace EPIC;

use EPIC\Archive\Category;
use EPIC\Archive\Tag;
use EPIC\Archive\Author;
use EPIC\Image\Image;
use EPIC\Module\ModuleManager;
use EPIC\Module\ModuleVC;
use EPIC\Elementor\ModuleElementor;
use EPIC\Option\Option;
use EPIC\Widget\Module\RegisterModuleWidget;
use EPIC\Gutenberg\ModuleGutenberg;
use EPIC\Single\SinglePost;
use EPIC\Single\SingleArchive;

class Init {

	private static $instance;

	public static function getInstance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	private function __construct() {
		$this->load_helper();
		$this->load_hook();
		$this->load_module();
	}

	protected function load_hook() {
		add_action( 'admin_init', array( $this, 'manage_admin_menu' ) );
		add_action( 'init', array( $this, 'list_all_cpt' ), 9999 );
		add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );

		add_filter( 'jeg_customizer_active_callback_option', array( $this, 'active_callback_option' ), 10, 3 );
		add_filter( 'epic_plugin_list', array( $this, 'plugin_list' ) );

		epic_activation_hook( EPIC_FILE );
	}

	public function manage_admin_menu() {
		if ( ! epic_get_option( 'show_post_template', true ) ) {
			remove_menu_page( 'edit.php?post_type=custom-post-template' );
		}
		if ( ! epic_get_option( 'show_category_template', true ) ) {
			remove_menu_page( 'edit.php?post_type=archive-template' );
		}
	}

	public function list_all_cpt() {
		if ( ! $cpt_lists = wp_cache_get( 'cpt_lists', 'epic-ne' ) ) {
			$cpt_lists = get_option( 'epic_cpt_list', array() );
			wp_cache_set( 'cpt_lists', $cpt_lists, 'epic-ne' );
		}
		$update = false;

		/** @var  $post_types */
		$post_types = \EPIC\Util\Cache::get_exclude_post_type();
		$taxonomies = \EPIC\Util\Cache::get_enable_custom_taxonomies();

		if ( isset( $cpt_lists['post_types'] ) && $cpt_lists['post_types'] !== $post_types ) {
			$cpt_lists['post_types'] = $post_types;
			$update                  = true;
		}

		if ( ! isset( $cpt_lists['post_types'] ) ) {
			$cpt_lists['post_types'] = $post_types;
			$update                  = true;
		}

		if ( isset( $cpt_lists['taxonomies'] ) && $cpt_lists['taxonomies'] !== $taxonomies ) {
			$cpt_lists['taxonomies'] = $taxonomies;
			$update                  = true;
		}

		if ( ! isset( $cpt_lists['taxonomies'] ) ) {
			$cpt_lists['taxonomies'] = $taxonomies;
			$update                  = true;
		}

		if ( $update ) {
			update_option( 'epic_cpt_list', $cpt_lists );
		}
	}

	public function plugin_list( $plugins ) {
		$plugins[] = array(
			'id'      => 22369850,
			'name'    => 'Epic News Elements',
			'slug'    => 'epic-news-element',
			'version' => EPIC_VERSION,
			'option'  => 'epic_news_elements_license',
			'file'    => EPIC_FILE
		);

		return $plugins;
	}

	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'epic-ne', false, EPIC . '/languages/' );
	}

	protected function load_helper() {
		require_once EPIC_DIR . 'includes/helper.php';
	}

	protected function load_module() {
		if ( ! is_admin() ) {
			FrontendAjax::getInstance();
		}

		Category::getInstance();
		Tag::getInstance();
		Author::getInstance();
		Asset::getInstance();
		Option::getInstance();
		Image::getInstance();

		ModuleManager::getInstance();
		ModuleVC::getInstance();
		RegisterModuleWidget::getInstance();
		ModuleElementor::getInstance();
		ModuleGutenberg::getInstance();
		SinglePost::getInstance();
		SingleArchive::getInstance();


		// todo: where should it place?
		ShortCodeGenerator::getInstance();
	}

	public function load_widget_element() {
		RegisterModuleWidget::getInstance();
	}

	public function active_callback_option( $value, $key, $default ) {

		if ( strpos( $key, 'epic-ne' ) !== false ) {
			preg_match( "/\[(.*)\]/", $key, $matches );

			if ( $matches[1] ) {
				$value = epic_get_option( $matches[1], $default );
			}
		}

		return $value;
	}
}
