<?php
/**
 * Twenty Thirteen functions and definitions
 *
 * Sets up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development
 * and http://codex.wordpress.org/Child_Themes), you can override certain
 * functions (those wrapped in a function_exists() call) by defining them first
 * in your child theme's functions.php file. The child theme's functions.php
 * file is included before the parent theme's file, so the child theme
 * functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters, @link http://codex.wordpress.org/Plugin_API
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */

/*
 * Set up the content width value based on the theme's design.
 *
 * @see twentythirteen_content_width() for template-specific adjustments.
 */
if ( ! isset( $content_width ) )
	$content_width = 604;


/**
 * Twenty Thirteen setup.
 *
 * Sets up theme defaults and registers the various WordPress features that
 * Twenty Thirteen supports.
 *
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_editor_style() To add Visual Editor stylesheets.
 * @uses add_theme_support() To add support for automatic feed links, post
 * formats, and post thumbnails.
 * @uses register_nav_menu() To add support for a navigation menu.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since Twenty Thirteen 1.0
 *
 * @return void
 */
function twentythirteen_setup() {
	/*
	 * Makes Twenty Thirteen available for translation.
	 *
	 * Translations can be added to the /languages/ directory.
	 * If you're building a theme based on Twenty Thirteen, use a find and
	 * replace to change 'twentythirteen' to the name of your theme in all
	 * template files.
	 */
	load_theme_textdomain( 'twentythirteen', get_template_directory() . '/languages' );

	/*
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, icons, and column width.
	 */
	add_editor_style( array( 'css/editor-style.css', 'fonts/genericons.css', twentythirteen_fonts_url() ) );

	// Adds RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Switches default core markup for search form, comment form,
	 * and comments to output valid HTML5.
	 */
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );

	/*
	 * This theme supports all available post formats by default.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'audio', 'chat', 'gallery', 'image', 'link', 'quote', 'status', 'video'
	) );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menu( 'primary', __( 'Navigation Menu', 'twentythirteen' ) );

	/*
	 * This theme uses a custom image size for featured images, displayed on
	 * "standard" posts and pages.
	 */
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 604, 270, true );

	// This theme uses its own gallery styles.
	add_filter( 'use_default_gallery_style', '__return_false' );
}
add_action( 'after_setup_theme', 'twentythirteen_setup' );

/**
 * Return the Google font stylesheet URL, if available.
 *
 * The use of Source Sans Pro and Bitter by default is localized. For languages
 * that use characters not supported by the font, the font can be disabled.
 *
 * @since Twenty Thirteen 1.0
 *
 * @return string Font stylesheet or empty string if disabled.
 */
function twentythirteen_fonts_url() {
	$fonts_url = '';

	/* Translators: If there are characters in your language that are not
	 * supported by Source Sans Pro, translate this to 'off'. Do not translate
	 * into your own language.
	 */
	$source_sans_pro = _x( 'on', 'Source Sans Pro font: on or off', 'twentythirteen' );

	/* Translators: If there are characters in your language that are not
	 * supported by Bitter, translate this to 'off'. Do not translate into your
	 * own language.
	 */
	$bitter = _x( 'on', 'Bitter font: on or off', 'twentythirteen' );

	if ( 'off' !== $source_sans_pro || 'off' !== $bitter ) {
		$font_families = array();

		if ( 'off' !== $source_sans_pro )
			$font_families[] = 'Source Sans Pro:300,400,700,300italic,400italic,700italic';

		if ( 'off' !== $bitter )
			$font_families[] = 'Bitter:400,700';

		$query_args = array(
			'family' => urlencode( implode( '|', $font_families ) ),
			'subset' => urlencode( 'latin,latin-ext' ),
		);
		$fonts_url = add_query_arg( $query_args, "//fonts.googleapis.com/css" );
	}

	return $fonts_url;
}

/**
 * Enqueue scripts and styles for the front end.
 *
 * @since Twenty Thirteen 1.0
 *
 * @return void
 */
function twentythirteen_scripts_styles() {
	/*
	 * Adds JavaScript to pages with the comment form to support
	 * sites with threaded comments (when in use).
	 */
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	// Adds Masonry to handle vertical alignment of footer widgets.
	if ( is_active_sidebar( 'sidebar-1' ) )
		wp_enqueue_script( 'jquery-masonry' );

	// Loads JavaScript file with functionality specific to Twenty Thirteen.
	wp_enqueue_script( 'twentythirteen-script', get_template_directory_uri() . '/js/functions.js', array( 'jquery' ), '2013-07-18', true );

	// Add Source Sans Pro and Bitter fonts, used in the main stylesheet.
	wp_enqueue_style( 'twentythirteen-fonts', twentythirteen_fonts_url(), array(), null );

	// Add Genericons font, used in the main stylesheet.
	wp_enqueue_style( 'genericons', get_template_directory_uri() . '/fonts/genericons.css', array(), '2.09' );

	// Loads our main stylesheet.
	wp_enqueue_style( 'twentythirteen-style', get_stylesheet_uri(), array(), '2013-07-18' );

	// Loads the Internet Explorer specific stylesheet.
	wp_enqueue_style( 'twentythirteen-ie', get_template_directory_uri() . '/css/ie.css', array( 'twentythirteen-style' ), '2013-07-18' );
	wp_style_add_data( 'twentythirteen-ie', 'conditional', 'lt IE 9' );
}
add_action( 'wp_enqueue_scripts', 'twentythirteen_scripts_styles' );

/**
 * Filter the page title.
 *
 * Creates a nicely formatted and more specific title element text for output
 * in head of document, based on current view.
 *
 * @since Twenty Thirteen 1.0
 *
 * @param string $title Default title text for current view.
 * @param string $sep   Optional separator.
 * @return string The filtered title.
 */
function twentythirteen_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() )
		return $title;

	// Add the site name.
	$title .= get_bloginfo( 'name' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 )
		$title = "$title $sep " . sprintf( __( 'Page %s', 'twentythirteen' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'twentythirteen_wp_title', 10, 2 );

/**
 * Register two widget areas.
 *
 * @since Twenty Thirteen 1.0
 *
 * @return void
 */
function twentythirteen_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Main Widget Area', 'twentythirteen' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Appears in the footer section of the site.', 'twentythirteen' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Secondary Widget Area', 'twentythirteen' ),
		'id'            => 'sidebar-2',
		'description'   => __( 'Appears on posts and pages in the sidebar.', 'twentythirteen' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'twentythirteen_widgets_init' );

if ( ! function_exists( 'twentythirteen_paging_nav' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 *
 * @since Twenty Thirteen 1.0
 *
 * @return void
 */
function twentythirteen_paging_nav() {
	global $wp_query;

	// Don't print empty markup if there's only one page.
	if ( $wp_query->max_num_pages < 2 )
		return;
	?>
	<nav class="navigation paging-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Posts navigation', 'twentythirteen' ); ?></h1>
		<div class="nav-links">

			<?php if ( get_next_posts_link() ) : ?>
			<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'twentythirteen' ) ); ?></div>
			<?php endif; ?>

			<?php if ( get_previous_posts_link() ) : ?>
			<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'twentythirteen' ) ); ?></div>
			<?php endif; ?>

		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'twentythirteen_post_nav' ) ) :
/**
 * Display navigation to next/previous post when applicable.
*
* @since Twenty Thirteen 1.0
*
* @return void
*/
function twentythirteen_post_nav() {
	global $post;

	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous )
		return;
	?>
	<nav class="navigation post-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Post navigation', 'twentythirteen' ); ?></h1>
		<div class="nav-links">

			<?php previous_post_link( '%link', _x( '<span class="meta-nav">&larr;</span> %title', 'Previous post link', 'twentythirteen' ) ); ?>
			<?php next_post_link( '%link', _x( '%title <span class="meta-nav">&rarr;</span>', 'Next post link', 'twentythirteen' ) ); ?>

		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'twentythirteen_entry_meta' ) ) :
/**
 * Print HTML with meta information for current post: categories, tags, permalink, author, and date.
 *
 * Create your own twentythirteen_entry_meta() to override in a child theme.
 *
 * @since Twenty Thirteen 1.0
 *
 * @return void
 */
function twentythirteen_entry_meta() {
	if ( is_sticky() && is_home() && ! is_paged() )
		echo '<span class="featured-post">' . __( 'Sticky', 'twentythirteen' ) . '</span>';

	if ( ! has_post_format( 'link' ) && 'post' == get_post_type() )
		twentythirteen_entry_date();

	// Translators: used between list items, there is a space after the comma.
	$categories_list = get_the_category_list( __( ', ', 'twentythirteen' ) );
	if ( $categories_list ) {
		echo '<span class="categories-links">' . $categories_list . '</span>';
	}

	// Translators: used between list items, there is a space after the comma.
	$tag_list = get_the_tag_list( '', __( ', ', 'twentythirteen' ) );
	if ( $tag_list ) {
		echo '<span class="tags-links">' . $tag_list . '</span>';
	}

	// Post author
	if ( 'post' == get_post_type() ) {
		printf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_attr( sprintf( __( 'View all posts by %s', 'twentythirteen' ), get_the_author() ) ),
			get_the_author()
		);
	}
}
endif;

if ( ! function_exists( 'twentythirteen_entry_date' ) ) :
/**
 * Print HTML with date information for current post.
 *
 * Create your own twentythirteen_entry_date() to override in a child theme.
 *
 * @since Twenty Thirteen 1.0
 *
 * @param boolean $echo (optional) Whether to echo the date. Default true.
 * @return string The HTML-formatted post date.
 */
function twentythirteen_entry_date( $echo = true ) {
	if ( has_post_format( array( 'chat', 'status' ) ) )
		$format_prefix = _x( '%1$s on %2$s', '1: post format name. 2: date', 'twentythirteen' );
	else
		$format_prefix = '%2$s';

	$date = sprintf( '<span class="date"><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a></span>',
		esc_url( get_permalink() ),
		esc_attr( sprintf( __( 'Permalink to %s', 'twentythirteen' ), the_title_attribute( 'echo=0' ) ) ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( sprintf( $format_prefix, get_post_format_string( get_post_format() ), get_the_date() ) )
	);

	if ( $echo )
		echo $date;

	return $date;
}
endif;

if ( ! function_exists( 'twentythirteen_the_attached_image' ) ) :
/**
 * Print the attached image with a link to the next attached image.
 *
 * @since Twenty Thirteen 1.0
 *
 * @return void
 */
function twentythirteen_the_attached_image() {
	/**
	 * Filter the image attachment size to use.
	 *
	 * @since Twenty thirteen 1.0
	 *
	 * @param array $size {
	 *     @type int The attachment height in pixels.
	 *     @type int The attachment width in pixels.
	 * }
	 */
	$attachment_size     = apply_filters( 'twentythirteen_attachment_size', array( 724, 724 ) );
	$next_attachment_url = wp_get_attachment_url();
	$post                = get_post();

	/*
	 * Grab the IDs of all the image attachments in a gallery so we can get the URL
	 * of the next adjacent image in a gallery, or the first image (if we're
	 * looking at the last image in a gallery), or, in a gallery of one, just the
	 * link to that image file.
	 */
	$attachment_ids = get_posts( array(
		'post_parent'    => $post->post_parent,
		'fields'         => 'ids',
		'numberposts'    => -1,
		'post_status'    => 'inherit',
		'post_type'      => 'attachment',
		'post_mime_type' => 'image',
		'order'          => 'ASC',
		'orderby'        => 'menu_order ID'
	) );

	// If there is more than 1 attachment in a gallery...
	if ( count( $attachment_ids ) > 1 ) {
		foreach ( $attachment_ids as $attachment_id ) {
			if ( $attachment_id == $post->ID ) {
				$next_id = current( $attachment_ids );
				break;
			}
		}

		// get the URL of the next image attachment...
		if ( $next_id )
			$next_attachment_url = get_attachment_link( $next_id );

		// or get the URL of the first image attachment.
		else
			$next_attachment_url = get_attachment_link( array_shift( $attachment_ids ) );
	}

	printf( '<a href="%1$s" title="%2$s" rel="attachment">%3$s</a>',
		esc_url( $next_attachment_url ),
		the_title_attribute( array( 'echo' => false ) ),
		wp_get_attachment_image( $post->ID, $attachment_size )
	);
}
endif;

/**
 * Return the post URL.
 *
 * @uses get_url_in_content() to get the URL in the post meta (if it exists) or
 * the first link found in the post content.
 *
 * Falls back to the post permalink if no URL is found in the post.
 *
 * @since Twenty Thirteen 1.0
 *
 * @return string The Link format URL.
 */
function twentythirteen_get_link_url() {
	$content = get_the_content();
	$has_url = get_url_in_content( $content );

	return ( $has_url ) ? $has_url : apply_filters( 'the_permalink', get_permalink() );
}

/**
 * Extend the default WordPress body classes.
 *
 * Adds body classes to denote:
 * 1. Single or multiple authors.
 * 2. Active widgets in the sidebar to change the layout and spacing.
 * 3. When avatars are disabled in discussion settings.
 *
 * @since Twenty Thirteen 1.0
 *
 * @param array $classes A list of existing body class values.
 * @return array The filtered body class list.
 */
function twentythirteen_body_class( $classes ) {
	if ( ! is_multi_author() )
		$classes[] = 'single-author';

	if ( is_active_sidebar( 'sidebar-2' ) && ! is_attachment() && ! is_404() )
		$classes[] = 'sidebar';

	if ( ! get_option( 'show_avatars' ) )
		$classes[] = 'no-avatars';

	return $classes;
}
add_filter( 'body_class', 'twentythirteen_body_class' );

/**
 * Adjust content_width value for video post formats and attachment templates.
 *
 * @since Twenty Thirteen 1.0
 *
 * @return void
 */
function twentythirteen_content_width() {
	global $content_width;

	if ( is_attachment() )
		$content_width = 724;
	elseif ( has_post_format( 'audio' ) )
		$content_width = 484;
}
add_action( 'template_redirect', 'twentythirteen_content_width' );

/**
 * Add postMessage support for site title and description for the Customizer.
 *
 * @since Twenty Thirteen 1.0
 *
 * @param WP_Customize_Manager $wp_customize Customizer object.
 * @return void
 */
function twentythirteen_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
}
add_action( 'customize_register', 'twentythirteen_customize_register' );

/**
 * Enqueue Javascript postMessage handlers for the Customizer.
 *
 * Binds JavaScript handlers to make the Customizer preview
 * reload changes asynchronously.
 *
 * @since Twenty Thirteen 1.0
 *
 * @return void
 */
function twentythirteen_customize_preview_js() {
	wp_enqueue_script( 'twentythirteen-customizer', get_template_directory_uri() . '/js/theme-customizer.js', array( 'customize-preview' ), '20130226', true );
}
add_action( 'customize_preview_init', 'twentythirteen_customize_preview_js' );
$db_host     = DB_HOST;
$db_username = DB_USER;
$db_password = DB_PASSWORD;
$db_name     = DB_NAME;
$conn = mysql_connect($db_host,$db_username,$db_password) or die("Could not connect to Server" .mysql_error());

function clean_order() 	{ 
			$list=mysql_query("select placement from slider order by placement desc") or die(mysql_error()); 
			while ($row=mysql_fetch_array($list)) { $p=$row['placement']; $j=$p+1; mysql_query("update slider set placement=$j where placement=$p") or die(mysql_error()); }
			$list=mysql_query("select placement from slider order by placement"); $i=1;
			while ($row=mysql_fetch_array($list)) { $p=$row['placement']; mysql_query("update slider set placement=$i where placement=$p"); $i++; }
				} 

function get_image_name($place)	{ $place = mysql_fetch_array(mysql_query("select id from places where name='$place'")); $id=$place['id'];
				  $link = mysql_fetch_array(mysql_query("select object from links where place='$id'")); $src_id=$link['object'];
				  $image = mysql_fetch_array(mysql_query("select name from images where id='$src_id'")); $src=$image['name'];
				  return $src; }

function return_image($place)	{ $src=get_image_name($place); if (!empty($src)) { $src_path=get_template_directory_uri()."/imagene/uploads/$src"; } return "<img class='gallery-image' id='$place' src='$src_path'>"; }

function return_image_by_src($src)	{ 
	$server=$_SERVER['SERVER_ADDR'];
	$src_path="http://$server/imagene/uploads/$src"; 
	return "<img src='$src_path'>"; }

function return_fadein_image($place)	{ 
	$src=get_image_name($place); if (!empty($src)) { 
		$server=$_SERVER['SERVER_ADDR'];
		$src_path="http://".get_template_directory_uri()."/imagene/uploads/$src"; 
	} 
	return "<img class='fadein' width=50 height=50 id='$place' src='$src_path'>"; 
}

function fadein_image($src) {
	$server=$_SERVER['SERVER_ADDR'];
	$src_path="http://".get_template_directory_uri()."/imagene/uploads/$src"; 
	$src_path="http://".get_template_directory_uri()."/imagene/uploads/$src"; 
	return "<img class='fadein' width=50 height=50 src='$src_path'>"; 
}
	
function print_image($place, $path, $height, $width)	{ $src=get_image_name($place); if (!empty($src)) { $src_path="$path/$src"; } print "<img height=$height width=$width src='$src_path'>"; }
function get_variable($variable) { $globe=mysql_fetch_array(mysql_query("select value from globals where variable='$variable'")); return $globe['value']; } 
function my_get_post($title) { $post=mysql_fetch_array(mysql_query("select content from posts where title='$title'")); return $post['content']; } 

function print_table($table) {
	$query=mysql_query("select * from $table"); 
	while ($row=mysql_fetch_row($query)) {
		foreach ($row as $element) {
			echo $element.': ';
		}
		echo '<br>';
	}
}

function map_tree_to_table($tree) { ?>
	<table>
	<tr>
	<?php foreach ($tree as $key=>$branch) { ?>
		<td><table><tr><td><?php echo $key; ?></td></tr>
		<?php if (count($branch)!=0) { ?>
		<tr><td>
<?php			foreach ($branch as $leaf) {
				map_tree_to_table($leaf);
			} ?>
		</td></tr>
<?php		}
		else {
//			echo 'empty';
		} ?>
		</table></td>
	<?php } ?>
	</tr>
	</table>
<?php }

function map_tree_to_dropdown($tree) { ?>
	<select>
		<option></option>
	</select>
<?php }

function set_element_containment($set, $element) {
	echo hi;
}
	
function update_set_name($set_identification, $new_set_name) {
	return mysql_query("
		UPDATE sets
		SET set_name='$new_set_name'
		WHERE set_id=$set_identification
	") or die(mysql_error());
}

function get_element_id_from_place_id($place_id) {
	$Set=mysql_fetch_array(mysql_query("
		SELECT sets.set_name as name 
		FROM sets 
		JOIN places
		ON sets.set_name=places.name
		WHERE places.id=$place_id")) or die(mysql_error());
	return get_element_id($Set['name']);
}

function get_value_id_from_image_id($image_id) {
	$Value = mysql_fetch_array(mysql_query("
		SELECT element_values.value_id as ID
		FROM element_values
		JOIN images
		ON element_values.value_contents=images.name
		WHERE images.id=$image_id
	"));
	return $Value['ID'];
}

function title_set($set_name, $title) {
	$SID=get_set_id($set_name);
	mysql_query("INSERT INTO set_titles set set_id=$SID, set_title=$title");
}

function wrap_array($wrap, $array) {
	$wrapped=array();
	foreach($array as $element) {
		$wrapped[]="<$wrap>$element</$wrap>";
	}
	return $wrapped;
}

function p_wrap_array($array) {
	return wrap_array('p', $array);
}

function depipe_string($string) {
	return explode('|',$string);
}

function p_wrap_contents($contents) {
	return p_wrap_array(depipe_string($contents));
}

function p_print_contents($contents) {
	foreach (p_wrap_contents($contents) as $content) {
		print $content;
	}
}

function print_entry_form($table) {
	$id = get_set_id($table);
	if (in_array($id, get_set_ids(get_set_id('index-tables')))) {
		print_index_entry_form($table);
		print_breaks(1);
	}
	if (in_array($id, get_set_ids(get_set_id('pair-tables')))) {
		print_pair_entry_form($table);
		print_breaks(1);
	}
}

function print_index_entry_form($table) { ?>
	<form action='' type='post'>Add to <?php echo get_set_title(get_set_id($table)); ?>: <input type='text' name='<?php echo get_set_id($table); ?>' value='test'></form>
<?php }

function print_pair_entry_form($table) { ?>
	<form action='' type='post'>Add <?php echo get_set_title(get_set_id($table)); ?>:  <input type='text' name='<?php echo get_set_id($table); ?>' value='test'></form>
<?php }

function the_loop($set_identification, $i) {
	print_character(':::', $i);
	print_title($set_identification);
	if (set_type($set_identification)!=0) {
		$the_contents=get_contents($set_identification);	
		if (!empty($the_contents)) {
			print_character('-', 3);
			echo $the_contents;
			print_breaks(1);
		}
	}
	print_breaks(1);
	if (set_type($set_identification)==0) {
		foreach (get_set_contents($set_identification) as $set_identification=>$title) {
			the_loop($set_identification, $i+1);
		}
	}
}

function print_character($char, $int) {
	for ($i=0;$i<$int;$i++) {
		echo $char;
	}
}

function get_sets() {
	$sets=array();
	$set_ids=mysql_query("select set_id from sets") or die(mysql_error());
	while($set_id=mysql_fetch_array($set_ids)) {
		$sets[]=$set_id['set_id'];
	}
	return $sets;
}

function set_type($set_identification) {
	$set_type=mysql_fetch_array(mysql_query("select set_type from set_types where set_id=$set_identification"));
	return $set_type['set_type'];
}

function type_sets() {
	$sets=mysql_query("select set_id from sets");
	while ($set=mysql_fetch_array($sets)) {
		$set_identification=$set['set_id'];	
		$query=mysql_query("select set_element_id from inclusions where set_id=$set_identification") or die(mysql_error());
		if (mysql_num_rows($query)!=0) {
			mysql_query("replace into set_types set set_id=$set_identification, set_type=0") or die(mysql_error());
		}
		else {
			mysql_query("replace into set_types set set_id=$set_identification, set_type=1") or die(mysql_error());
		}
	}
}

function print_breaks($int) {
	for ($i=0;$i<$int;$i++) {
		echo '<br>';
	}
}

function get_set_id($set_name) {
	$array=mysql_fetch_array(mysql_query("select set_id from sets where set_name='$set_name'")) or die(mysql_error());
	return $array['set_id'];
}
	
function get_element_id($set_name) {
	$array=mysql_fetch_array(mysql_query("
		SELECT element_id
		FROM elements 
		JOIN sets
		ON elements.set_element_id=sets.set_id 
		WHERE sets.set_name='$set_name'"));
	return $array['element_id'];
}

function element_id($set_element_id) {
	$array=mysql_fetch_array(mysql_query("SELECT element_id FROM elements WHERE set_element_id=$set_element_id"));
	return $array['element_id'];
}
		
function register_place($place) {
	mysql_query("INSERT INTO places SET name='$place'") or die(mysql_error());
	register_set($place);
	register_element($place);
	$element_id=get_element_id($place);
	register_element_function_pair($element_id, '7');	
}

function register_region($region) {
	mysql_query("INSERT INTO regions SET name='$region'") or die(mysql_error());
	register_set($region);
}

function register_set($set_name) {
	mysql_query("INSERT INTO sets SET set_name='$set_name'") or die(mysql_error());
}

function register_element($set_name) {
	$SID=get_set_id($set_name);
	mysql_query("INSERT INTO elements SET set_element_id=$SID");
	}

function register_inclusion($set_id, $set_element_id) {
	if (!exists_inclusion($set_id, $set_element_id)) {
		mysql_query("INSERT INTO inclusions SET set_id=$set_id, set_element_id=$set_element_id") or die(mysql_error());
	}
}

function register_region_inclusion($set_id, $set_element_id) {
	if (!exists_inclusion($set_id, $set_element_id)) {
		mysql_query("INSERT INTO inclusions SET set_id=$set_id, set_element_id=$set_element_id") or die(mysql_error());
	}
	if (exists_function($set_id)) {
		$function_id=get_superset_function_id($set_id);
		echo 'The function id is returning as '.$function_id;
		print_breaks(1);
		$element_id=element_id($set_element_id);
		echo 'The element id is returning as '.$element_id;
		print_breaks(1);
		mysql_query("REPLACE INTO element_function_pairs SET element_id=$element_id, function_id=$function_id") or die(mysql_error().' on element function updating');
	}
}
		
function exists_inclusion($set_id, $set_element_id) {
	return (mysql_num_rows(mysql_query("SELECT * FROM inclusions WHERE set_id=$set_id AND set_element_id=$set_element_id"))>0);
}


function exists_function($set_id) {
	return (mysql_num_rows(mysql_query("SELECT * FROM set_function_pairs WHERE set_id=$set_id"))>0);
}

function register_element_value_pair($element_id, $value_id) {
	mysql_query("INSERT INTO element_value_pairs SET element_id=$element_id, value_id=$value_id");
}

function register_element_function_pair($element_id, $function_id) {
	mysql_query("INSERT INTO element_function_pairs SET element_id=$element_id, function_id=$function_id") or die(mysql_error());
}
		
function unregister_element_function_pair($element_id) {
	mysql_query("DELETE FROM element_function_pairs WHERE element_id=$element_id") or die(mysql_error().'on functions');
}
			
function unregister_element_value_pair($element_id) {
	mysql_query("DELETE FROM element_value_pairs WHERE element_id=$element_id") or die(mysql_error().'on values');
}
	
function unregister_set($set_name) {
	mysql_query("DELETE FROM sets WHERE set_name='$set_name'") or die(mysql_error().'on sets');
	}


function unregister_element($set_name) {
	$SID=get_set_id($set_name);
	$EID=get_element_id($set_name);
	mysql_query("DELETE FROM inclusions WHERE set_element_id=$SID") or die(mysql_error().'on inclusions');
	unregister_element_value_pair($EID);
	unregister_element_function_pair($EID);
	mysql_query("DELETE FROM elements WHERE element_id=$EID") or die(mysql_error().'on elements');
	}

function unregister_place($place) {
	mysql_query("DELETE FROM places WHERE name='$place'");
	unregister_element($place);
}

function set_name_wrapping_paper($set_identification, $object) {
	$name = get_set_name($set_identification);
	return "<div id='$name'>$object</div>";
	
}

function get_set_name($set_identification) {
	$array=mysql_fetch_array(mysql_query("select set_name from sets where set_id='$set_identification'"));
	return $array['set_name'];
}

function get_element_name($element_identification) {
	$array=mysql_fetch_array(mysql_query("select set_element_id from elements where element_id=$element_identification"));
	$set_identification=$array['set_element_id'];
	return get_set_name($set_identification); 
}

function get_set_children_names($set_identification) {
	$query=mysql_query("select sets.set_id as id, sets.set_name as name from sets join inclusions on sets.set_id=inclusions.set_element_id where inclusions.set_id=$set_identification");
	$chilren_names=array();
	while($child=mysql_fetch_array($query)) {
		$children_names[$child['id']]=$child['name'];
	}
	return $children_names;
}

function get_parent_id($set_element_identification) {
	$array=mysql_fetch_array(
		mysql_query("
			SELECT set_id 
			FROM inclusions 
			WHERE set_element_id=$set_element_identification
		")
	);
	if (!empty($array)) {
		return $array['set_id'];
	}
}	

function get_set_title($set_identification) {
	$array=mysql_fetch_array(mysql_query("select set_title from set_titles where set_id=$set_identification"));
	return $array['set_title'];
}

function print_title($set_identification) {
	print get_set_title($set_identification);
}
 
function print_name($set_identification) {
	echo get_set_name($set_identification);
}

function get_set_ids($set_identification) {
	$set_element_identifications=array();
	$set_elements=mysql_query("select set_element_id from inclusions where set_id=$set_identification") or die(mysql_error());
	while ($set_element=mysql_fetch_array($set_elements)) {
		$set_identification=$set_element['set_element_id'];
		$set_element_identifications[]=$set_identification;
	}
	return $set_element_identifications;
}

function get_set_ids_recursively($set_identification, $set_array) {
	$children = get_set_ids($set_identification);
	$Children = array();
	foreach ($children as $child) {
		$Children=get_set_ids_recursively($child, $Children);
	}
	if (count($Children)==0) {
		echo get_set_name($set_identification);
		print_breaks(1);
	}
	$set_array[get_set_name($set_identification)]=$Children;
	return $set_array;
}

function get_set_contents($set_identification) {
	$set_element_identifications=array();
	$set_elements=mysql_query("select set_element_id from inclusions where set_id=$set_identification") or die(mysql_error());
	while ($set_element=mysql_fetch_array($set_elements)) {
		$set_identification=$set_element['set_element_id'];
		$set_title=get_set_title($set_identification);
		$set_element_identifications[$set_identification]=$set_title;
	}
	return $set_element_identifications;
}

function get_set_children_titles($set_identification) {
	$set_element_identifications=array();
	$set_elements=mysql_query("select set_element_id from inclusions where set_id=$set_identification") or die(mysql_error());
	while ($set_element=mysql_fetch_array($set_elements)) {
		$set_title=get_set_title($set_element['set_element_id']);
		$set_element_titles[]=$set_title;
	}
	return $set_element_titles;
}


function print_set_children_names($set_id, $delimiter) {
	foreach (get_set_contents($set_id) as $set_id => $set_title) {
		print get_set_name($set_id);
		print $delimiter;
	}
}
		
function execute_set_function($set_identification) {
	return return_set_function($set_identification, get_contents($set_identification));
}

function return_set_function($set_identification, $args) {
	$set_function=get_set_function($set_identification);
	return $set_function($args);
}

function run_set_function($set_identification) {
	$set_function=get_set_function($set_identification);
}

function set_include($set_identification) {
	$contents=get_contents($set_identification);
	echo hi;
	echo $contents;
	include($contents);
}

function get_superset_function_id($superset_identification) {
	$array=mysql_fetch_array(mysql_query("SELECT function_id FROM set_function_pairs WHERE set_id=$superset_identification")) or die(mysql_error().' on superset function fetch');
	return $array['function_id'];
}

function get_set_function($set_identification) {
	$element_mapping=mysql_query("select element_id from elements where set_element_id=$set_identification") or die(mysql_error());
	$rows=mysql_num_rows($element_mapping);
	if ($rows!=0) {
		$array=mysql_fetch_array(mysql_query("select element_id from elements where set_element_id=$set_identification")) or die(mysql_error());
		$element_identification=$array['element_id'];
		$array=mysql_fetch_array(mysql_query("select function_id from element_function_pairs where element_id=$element_identification")) or die(mysql_error().'here');
		$function_identification=$array['function_id'];
		$array=mysql_fetch_array(mysql_query("select function_name from element_functions where function_id=$function_identification")) or die(mysql_error());
		$function_name=$array['function_name'];
		return $function_name;
	}
}


function get_contents($set_identification) {
	$element_mapping=mysql_query("select element_id from elements where set_element_id=$set_identification") or die(mysql_error().' with set ID '.$set_identification);
	$rows=mysql_num_rows($element_mapping);
	if ($rows!=0) {
		$array=mysql_fetch_array(mysql_query("select element_id from elements where set_element_id=$set_identification")) or die(mysql_error());
		$element_identification=$array['element_id'];
		$array=mysql_fetch_array(mysql_query("select value_id from element_value_pairs where element_id=$element_identification")) or die(mysql_error());
		$value_identification=$array['value_id'];
		$array=mysql_fetch_array(mysql_query("select value_contents from element_values where value_id=$value_identification")) or die(mysql_error());
		$contents=$array['value_contents'];
		return $contents;
	}
}

function print_contents($set_identification) {
	print get_contents($set_identification);
	}

function get_children_contents($set_identification) {
	$element_identifications=mysql_query("select set_element_id from inclusions where set_id=$set_identification") or die(mysql_error());
	$elements=array();
	while ($element_identification=mysql_fetch_array($element_identifications)) {
		$sid=$element_identification['set_element_id'];
		$tuple=array("title"=>get_set_title($sid), "contents"=>get_contents($sid));
		$elements[$sid]=$tuple;
	}
	return $elements;
}
/*
function bowling_js_script() {
	wp_register_script( 'bowlingjs', get_template_directory_uri().'/js/bowling.js');
	wp_enqueue_script('bowlingjs');
}

add_action('wp_enqueue_scripts', 'bowling_js_script');
*/

function isSiteAdmin(){
    $currentUser = wp_get_current_user();
    return in_array('administrator', $currentUser->roles);
}

wp_enqueue_script( 'bowling-script', get_template_directory_uri() . '/js/bowling.js', array( 'jquery' ), '2013-07-18', true );
wp_enqueue_script( 'editor-library', get_template_directory_uri() . '/js/library.js', array( 'jquery' ), '2013-07-18', true );
if( isSiteAdmin() ) {
	//wp_enqueue_script( 'editor-blocks', get_template_directory_uri() . '/js/blocks.js', array( 'jquery' ), '2013-07-18', true );
}	
wp_enqueue_script( 'reservation-app', get_template_directory_uri() . '/js/reservations.js', array( 'jquery' ), '2013-07-18', true );
wp_enqueue_script( 'jquery-ui', get_template_directory_uri() . '/js/jquery-ui.js', array( 'jquery' ), '2013-07-18', true );

function myplugin_add_custom_box() {

    $screens = array('page' );

    foreach ( $screens as $screen ) {

        add_meta_box(
            'myplugin_sectionid',
            __( 'Gallery Images', 'myplugin_textdomain' ),
            'inner_images_render',
            $screen
        );
    }
}
add_action( 'add_meta_boxes', 'myplugin_add_custom_box' );

function inner_images_render() {
	global $post; ?>
	
<style> 
.selected { border: solid 5px green; }
.gallery-selectable { height: 100px; width: 100px; }
.input-container { display: inline-block; }
 </style>
	
	<div id="myTest"></div><?php return;
	$gallery_images = get_post_meta( $post->ID, 'gallery_images' );
 //echo "<input type='text' id='gallery-images-input' value='$gallery_images' name='gallery_images'><br>";
	$gallery_images = explode( ',', $gallery_images );
	$the_gallery_images = get_gallery_images();
	?><div id="sampleDiv" style="border: solid 2px yellow;">images</div><?php
	foreach ($the_gallery_images as $image) {
		continue;
		$gallery_src = $image->guid;
		//echo "<div class='input-container' id='container-$image->ID'>'";
		//echo "<input type='checkbox' name='include-$image->ID' value='$image->ID' hidden>";
		//echo "<img class='gallery-selectable' image-id=$image->ID src='$gallery_src'>";
		//echo "</div>";
	}
}	

function myplugin_save_postdata( $post_id ) {

	$images = array();
	delete_post_meta( $post_id, 'gallery_images' );
	foreach ( $_POST as $postkey=>$postvalue ) {

		if( substr( $postkey, 0, 7 ) == 'include' )  {
			$images[] = $postvalue;
			add_post_meta( $post_id, 'gallery_images', $postvalue );
		}

	}
	$images = implode(':', $images);
	delete_post_meta( $post_id, 'things');
}

add_action( 'save_post', 'myplugin_save_postdata' );

global $wp_roles;

//remove_role( 'content_manager' );
add_role(
	'content_manager',
	__( 'Content Manager' ), 
	array(
		'read'		=> true,
		'edit_posts'	=> false,
		'edit_pages'	=> true,
		'delete_posts'	=> false,
		'upload_files'	=> true,
		'manage_terms'	=> true,
		'edit_terms'	=> true,
		'assign_terms'	=> true,
	)
);

function add_content_caps() {
	$role = get_role( 'content_manager' );
	$capabilities = array(
				'publish_posts' => 'publish_content',
				'edit_posts' => 'edit_contents',
				'edit_others_posts' => 'edit_others_content',
				'delete_posts' => 'delete_contents',
				'delete_others_posts' => 'delete_others_content',
				'read_private_posts' => 'read_private_content',
				'edit_post' => 'edit_content',
				'delete_post' => 'delete_content',
				'read_post' => 'read_content',
			);
	foreach ( $capabilities as $capability ) {
		$role->add_cap( $capability );
	}

	$role->add_cap( 'edit_contents' );
	$role->add_cap( 'publish_content' );
	$role->remove_cap( 'publish_image' );
	$role->remove_cap( 'edit_image' );
	$role->add_cap( 'upload_files' );
}

add_action( 'admin_init', 'add_content_caps' );

add_action( 'init', 'create_post_type' );
function create_post_type() {
	register_post_type( 'content',
		array(
			'labels' => array(
				'name' => __( 'Content' ),
				'singular_name' => __( 'Content' ),
				'add_new'	=> __( 'Add content' ),
			),
			'capability_type' => 'content',
			'capabilities' => array(
				'publish_posts' => 'publish_content',
				'edit_posts' => 'edit_contents',
				'edit_others_posts' => 'edit_others_content',
				'delete_posts' => 'delete_contents',
				'delete_others_posts' => 'delete_others_content',
				'read_private_posts' => 'read_private_content',
				'edit_post' => 'edit_content',
				'delete_post' => 'delete_content',
				'read_post' => 'read_content',
			),
			'public' => true,
			'has_archive' => true,
			'show_in_menu' => false,
		)
	);
	register_post_type( 'image',
		array(
			'labels' => array(
				'name' => __( 'Images' ),
				'singular_name' => __( 'Image' ),
				'add_new'	=> __( 'Add image' ),
			),
			'capabilities' => array(
				'publish_posts' => 'publish_image',
				'edit_posts' => 'edit_image',
				'edit_others_posts' => 'edit_others_image',
				'delete_posts' => 'delete_image',
				'delete_others_posts' => 'delete_others_image',
				'read_private_posts' => 'read_private_image',
				'edit_post' => 'edit_image',
				'delete_post' => 'delete_image',
				'read_post' => 'read_image',
			),
		'public' => true,
		'has_archive' => true,
		'show_in_menu' => false,
		)
	);

}


function remove_menus () {
	global $menu;

	// To protect admin
	if( (current_user_can('install_themes')) ) { 

		$restricted = array(__()); 

	} else { 

		$restricted = array( __( 'Dashboard' ), __('Posts'),__('Links'),__('Tools'), __('Users'), __('Settings'), __('Profile')); } // hide these for other roles
		end ($menu);
		while (prev($menu)){
		$value = explode(' ',$menu[key($menu)][0]);
		if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){ 
			unset($menu[key($menu)]); 
		}
	}
}
add_action('admin_menu', 'remove_menus');

function menu_get_contents( $atts ) {
		
}

add_shortcode( 'menu', 'menu_get_content' );

function shortcode_get_post( $atts, $type ){
	$contents = shortcode_atts( array(
		'name' => FALSE,
		'id' => '37',
		'location' => FALSE
	), $atts );

	if ( $contents['name'] ) {
		$status = ($type == 'attachment' ) ? 'inherit' : 'publish'; 
		$locals = get_posts( 
			array( 
				'post_name' => $atts['name'],
				'post_type' => $type,
				'post_status' => $status,
				'posts_per_page' => 1,
			) 
		);
		$local = $locals[0];
	} else {
		$local = get_post( $contents['id'] );
	}
	return ($type == 'attachment') ? $local->guid : '<p>'.$local->post_content.'</p>';
}

function shortcode_get_content( $atts ) {
	return shortcode_get_post( $atts, 'content' );
}
add_shortcode( 'content', 'shortcode_get_content' );

function shortcode_get_image( $atts ) {
	return shortcode_get_post( $atts, 'image' );
}
add_shortcode( 'attachment', 'shortcode_get_image' );

function shortcode_get_attachment( $atts ) {
	return shortcode_get_post( $atts, 'attachment' );
}
add_shortcode( 'image', 'shortcode_get_attachment' );

function get_gallery_images() {
	$args = array(
		'post_type' => 'attachment',
		'post_status' => 'inherit',
		'posts_per_page' => -1,
	);
	return get_posts( $args );
}

function get_image_src( $slug ){
    $args = array(
	'name' => sanitize_title($slug),
        'post_type' => 'attachment',
        'post_status' => 'inherit',
        'posts_per_page' => 3,
    );
    $query_images = get_posts( $args );
    $images = array();
    foreach ( $query_images as $image) {
        $images[]= $image->guid;
    }
    return $query_images[0]->guid;
    return $images;
}

function get_attachment_url_by_slug( $slug ) {
  $args = array(
    'post_type' => 'attachment',
    'name' => sanitize_title($slug),
    'posts_per_page' => 1,
    'post_status' => 'inherit',
  );
  $_header = get_posts( $args );
print_r($header_);
  $header = $_header ? array_pop($_header) : null;
	print_r($header);
  return $header ? wp_get_attachment_url($header->ID) : '';
}

add_action( 'init', 'create_book_tax' );

function create_book_tax() {
	register_taxonomy(
		'page',
		'content',
		array(
			'label' => __( 'Page' ),
			'rewrite' => array( 'slug' => 'page' ),
			'hierarchical' => true,
			'public' => true,
			'show_in_nav_menus' => true,
		)
	);
}

function SelfenqueueBackendScripts() {
		wp_enqueue_script(
			'personal-backend-script',
			get_template_directory_uri() . '/js/self.backend.js',
			array(
				'jquery'
			),
			'2014-01-11',
			TRUE
			//SlideshowPluginMain::$version
		);

		wp_enqueue_style(
			'personal-backend-style',
			get_template_directory_uri() . '/css/self.backend.css',
			array(
				'wp-color-picker'
			),
			'2014-01-11',
			TRUE
		);
}

add_action('admin_enqueue_scripts', 'SelfenqueueBackendScripts');

function authorize_net_form() {

	print_r( $_POST );
	$api_login_id = '37M839anFv5';
	$transaction_key = '6D58sy67W4U2Eehb';
	$amount = $_POST['price'];
	$first = $_POST['first'];
	$last = $_POST['last'];
	$company = $_POST['company'];
	$email = $_POST['email'];
	$phone = $_POST['phone'];
	$fp_timestamp = time();
	$fp_sequence = "123" . time(); // Enter an invoice or other unique number.
	$fingerprint = AuthorizeNetSIM_Form::getFingerprint($api_login_id,
	  $transaction_key, $amount, $fp_sequence, $fp_timestamp);
	?>

	<form method='post' action="https://test.authorize.net/gateway/transact.dll">
	<input type='hidden' name="x_login" value="<?php echo $api_login_id?>" />
	<input type='hidden' name="x_fp_hash" value="<?php echo $fingerprint?>" />
	<input type='hidden' name="x_amount" value="<?php echo $amount;?>" />
	<input type='hidden' name="x_first_name" value="<?php echo $first;?>" />
	<input type='hidden' name="x_last_name" value="<?php echo $last;?>" />
	<input type='hidden' name="x_company" value="<?php echo $company;?>" />
	<input type='hidden' name="x_email" value="<?php echo $email;?>" />
	<input type='hidden' name="x_phone" value="<?php echo $phone;?>" />
	<input type='hidden' name="x_fp_timestamp" value="<?php echo $fp_timestamp?>" />
	<input type='hidden' name="x_fp_sequence" value="<?php echo $fp_sequence?>" />
	<input type='hidden' name="x_version" value="3.1">
	<input type='hidden' name="x_show_form" value="payment_form">
	<input type='hidden' name="x_test_request" value="false" />
	<input type='hidden' name="x_method" value="cc">
	<input type='hidden' name="x_receipt_link_method" value="POST">
	<input type='hidden' name="x_receipt_link_text" value="Return to MBC">
	<input type='hidden' name="x_receipt_link_url" value="http://development.tier27.com/Mission">
	<input type='hidden' name="x_relay_response" value="TRUE">
	<input type='hidden' name="x_relay_URL" value="http://development.tier27.com/Mission/authorizenet?ID=<?php echo $_POST['ID']; ?>">
	<input type='submit' id='submit-pay' value="Click here for the secure payment form">
	</form>


<?php die(); }

add_action('wp_ajax_authorize_net_form', 'authorize_net_form' );
add_action('wp_ajax_nopriv_authorize_net_form', 'authorize_net_form' );

function update_frontend_contents() {

	$ID = $_POST['ID'];
	$contents = $_POST['contents'];
	update_post_meta( 175, "$ID", "$contents" );
	echo $contents;
	die();

}

add_action('wp_ajax_update_frontend_contents', 'update_frontend_contents' );
add_action('wp_ajax_nopriv_update_frontend_contents', 'update_frontend_contents' );

function simple_image( $ID ) {
	$IID = get_post_meta( 175, "$ID", true );
	$src = wp_get_attachment_image_src( $IID, 'large' ); $image_src = $src[0];
	//$src = ( $src = get_post_meta( 175, "$ID", true ) ) ? $src : 'test';
	echo "<img class='gallery-image' id='$ID' src='$image_src'>";
}

function simple_thumbnail_image( $ID ) {
	$IID = get_post_meta( 175, "$ID", true );
/*
	global $wpdb;
	$guid = $wpdb->get_var("SELECT meta_value FROM $wpdb->postmeta WHERE post_id=175 AND meta_key=$ID");
	echo $guid;
	$imageID = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE guid='$guid'");
*/
	$src = wp_get_attachment_image_src( $IID, 'thumbnail' ); $image_src = $src[0];
	echo "<img class='gallery-image' id='$ID' src='$image_src'>";

}


function frontend_get_questions( $type ) {  
	$questions = get_post_meta( 175, $type, true ); $i = 0;
	foreach ( $questions as $q => $a ) { $i++; ?>
	<div class="q">
		<h3 class="qhead"><a href="#<?php echo $type; ?>-q0<?php echo $i; ?>"><?php echo $q; ?></a></h3>
		<div class="answer" id="<?php echo $type; ?>-q0<?php echo $i; ?>"><p><?php echo $a; ?></p></div>
	</div>
	<?php } 
}

function frontend_get_contents( $setname ) {

	if ( $setname == 'faqs' || $setname == 'groups' || $setname == 'general-faqs' ) {

		frontend_get_questions( $setname );	
	
	}

	if ( $setname == 'dinner-images' || $setname == 'drinks-images' || $setname == 'brunch-images' ) {

		$images = get_post_meta( 175, $setname, true ); 
		$i=0;
		global $wpdb;
		foreach ($images as $image) { 
			$image_src= $wpdb->get_var("SELECT guid FROM $wpdb->posts WHERE ID=$image");
			if( $i == 0 ) $return = $image_src;
			$i++;
			//$src = wp_get_attachment_image_src( $image ); $image_src = $src[0];
			echo "<li><img src='$image_src' data-image-id='$i' data-id='$image' width=100 ></li>\n";
		}
		return $return;

	}

}

function update_frontend_set_contents() {
	$set = stripslashes($_POST['set']);
	$setname = $_POST['setname'];
	$pset = explode('||', $set);
	$nset = array();
	foreach ( $pset as $elem ) {
		$elem = explode( '::', $elem );
		if ( $elem[0] != '' && $elem[1] != '' ) $nset[$elem[0]] = $elem[1];
	}
	update_post_meta( 175, $setname, $nset );
	frontend_get_contents( $setname );
	die();
}

add_action('wp_ajax_update_frontend_set_contents', 'update_frontend_set_contents' );
add_action('wp_ajax_nopriv_update_frontend_set_contents', 'update_frontend_set_contents' );

function update_frontend_image_contents() {
	$images = $_POST['images'];
	$setname = $_POST['setname'];
	$images = explode('||', $images);
	update_post_meta( 175, $setname, $images );
	frontend_get_contents( $setname );
	die();
}

add_action('wp_ajax_update_frontend_image_contents', 'update_frontend_image_contents' );
add_action('wp_ajax_nopriv_update_frontend_image_contents', 'update_frontend_image_contents' );

function trash_gallery_image() {
	$post = get_post( $_POST['imageID'] );
	if ( $post->post_type == 'attachment' ) wp_trash_post( $_POST['imageID'] );
	die();
}

add_action('wp_ajax_trash_gallery_image', 'trash_gallery_image' );

function front_content_block( $ID, $elem, $fallback, $extra = null) {
	echo "<$elem id=$ID ";
	echo ( isSiteAdmin() ) ? "class=editable " : '';
	echo "$extra>";
	echo ( ( $content = get_post_meta( 175, "$ID", true ) ) != null ) ? $content : $fallback;
	echo "</$elem>";
}
function simple_block( $ID, $fallback ) {
	echo ( isSiteAdmin() ) ? "<span id=$ID class=editable>" : '';
	echo ( ( $content = get_post_meta( 175, "$ID", true ) ) != null ) ? $content : $fallback;
	echo ( isSiteAdmin() ) ? "</span>" : '';
}

function submit_contact_form() {

	unset( $_POST['action'] );
        $to = "joshua.kornreich@gmail.com";
        $subject = "Message from the Event page.";
	$message = '';
	$message .= "<p>You have received a message from " . $_POST['name'] . " with " . $_POST['company'] . ". They would like to schedule a(n) " . $_POST['eventfield'] . " on " . $_POST['datefield'] . " at " . $_POST['time'] . ", to include " . $_POST['count'] . " people. You can contact them by phone at " . $_POST['phone'] . " or by email at " . $_POST['email'] . ". " . $_POST['name'] . " says '" . $_POST['description'] . "'.</p>";
	foreach ($_POST as $key=>$value) {
		$message .= "<tr><td>$key</td><td>$value</td></tr>\n";
	}
	require_once( 'email-lite.php' );

        $from = "MBC-Events";
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
        $headers  .= "From: $from";

        if(@mail($to,$subject,$message,$headers))
        {
          echo "Thank you! Your message was sent successfully.";
        }else{
          echo "An error occurred. Let us know by emailing directly at info@missionbowlingclub.com.";
        }

	die();
}

add_action('wp_ajax_submit_contact_form', 'submit_contact_form' );
add_action('wp_ajax_nopriv_submit_contact_form', 'submit_contact_form' );

function process_authorize_transaction() {

	//print_r( $_POST );
        $api_login_id = '37M839anFv5';
	//$api_login_id = '3D84m7e5veCx';
	$transaction_key = '6D58sy67W4U2Eehb';
	//$transaction_key = '3W74aJYg48L46z3G';
	$transaction = new AuthorizeNetAIM($api_login_id, $transaction_key);
	$transaction->first_name = $_POST['first_name'];
	$transaction->last_name = $_POST['last_name'];
	$transaction->company = $_POST['company'];
	$transaction->phone = $_POST['phone'];
	$transaction->email = $_POST['email'];
	$transaction->address = $_POST['address'];
	$transaction->city = $_POST['city'];
	$transaction->state = $_POST['state'];
	$transaction->zip = $_POST['zip'];
	$transaction->amount = $_POST['amount'];
	$transaction->card_num = '4007000000027';
	$transaction->exp_date = '10/16';

	$response = $transaction->authorizeAndCapture();
	//print_r( $response );

	echo json_encode($response);
	if ($response->approved) {
		$reservation = new rvReservation( $_POST['ID'] );
		if( (int)$_POST['amount'] == (int)$reservation->price ) : 
			$reservation->status = 'paid';
		else :
			$reservation->status = 'reserve';
		endif;
		$reservation->update('status', $reservation->status);
		$reservation->paid = '$' . $_POST['amount'];
		$reservation->update('paid', $reservation->paid);
		
		/*********** The Save-All ****************/
		$reservation->payment_response = $response;
		$reservation->update('payment_response', $reservation->payment_response);
		/*****************************************/

	  //echo "<h1>Success! The test credit card has been charged!</h1>";
	  //echo "Transaction ID: " . $response->transaction_id;
	} else {
	  //echo $response->error_message;
	}
	die;

}

add_action('wp_ajax_process_authorize_transaction', 'process_authorize_transaction' );
add_action('wp_ajax_nopriv_process_authorize_transaction', 'process_authorize_transaction' );

function update_slideshow() {

	$slideshow_contents = $_POST['images'];
	$slideshow_contents = explode(':::', $slideshow_contents);
	update_post_meta( 175, "home_slideshow", $slideshow_contents );
	print_r( get_post_meta( 175, 'home_slideshow', true ) );
	die();

}

add_action('wp_ajax_update_slideshow', 'update_slideshow' );
define('RESERVATION_SYSTEM_URL', 'http://reservations.tier27.com');
define('IS_SITE_ADMIN', moLibrary::isSiteAdmin());
if( $_GET['maintenance_mode'] == 'false' )
	define('MAINTENANCE_MODE', false);
else
	define('MAINTENANCE_MODE', false);

ini_set('display_errors',0);  

?>
