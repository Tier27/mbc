<? /** Template Name: Menu **/ ?>
<?php get_header(); ?>
<?php 
	$the_posts = get_posts(
		array(
			'post_type' => 'content',
			'genre'      => 'jazz',
			'post_status' => 'publish',
		)
	);
$gallery_images = get_post_meta( $post->ID, 'gallery_images' );
?>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<link type="text/css" rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/menu.css">
<style>
#thumbnails ul li { display: inline-block; }
</style>
</head>
<body onLoad="javascript:preloader()">
<div class="clear"></div>
<div id="menu">
<div class="container">
<style> #<?php echo $pagename; ?>-menu { height: 100%; } </style>
<?php if ($pagename == 'dinner' || $pagename == 'drinks' || $pagename == 'brunch' ) { ?>
<!-- Start of Page Content for Dinner -->
	<div class="menu-image">
		<nav>
			<?php $gallery_images = get_gallery_images(); ?>
			<ul class="menu image-contents" data-id="<?php echo $pagename; ?>-images">
			<?php $first = frontend_get_contents( $pagename . '-images' ); ?>
			</ul>
		</nav>
		<div id="img-main">
			<img src="<?php echo $first; ?>">
		</div>
	</div>
	<div class="menu-content">
		<h2 class="dots"><span class="dots"><?php echo $pagename; ?></span><div class="stripe-line">&nbsp; </div></h2>

<!-- cut -->
<?php
while ( have_posts() ) :
	the_post();
endwhile;
?>
		<?php moOutput::content( array( "tag" => "p", "id" => "$pagename-menu-content-first", "content" => "This is default content" ) ); ?>
		<br>
		<?php moOutput::content( array( "tag" => "p", "id" => "$pagename-menu-content-second", "content" => "" ) ); ?>
		<br>
		<?php moOutput::content( array( "tag" => "p", "id" => "$pagename-menu-content-third", "content" => "This is default content" ) ); ?>
		<br>
		<?php moOutput::content( array( "tag" => "p", "id" => "$pagename-menu-content-fourth", "content" => "" ) ); ?>
		<br>
		<p><a id='menu-link' target='_blank'>View the menu</a></p>
		<script>
			jQuery( function($) {
				$('#menu-link').click(function(e) {
					e.preventDefault;
					window.open('menu', 'menu', 'width=600, height=600');
				});
			});
		</script>	
		<?php if(  isSiteAdmin() ) { ?>
		<button type="button" class="btn" id="menu-update-button">Upload Menu</button>
		<button type="button" class="btn" id="menu-submit-button" style="display: none;">Submit</button>
		<form id="menu-update-form" action="" method="post" enctype="multipart/form-data"><input type="file" name="file" id="file" style="display: none"><input type="submit" name="submit" value="Submit" style="display: none;"></form>
		<?php 
			$basedir = str_replace($_SERVER['SERVER_NAME'], $_SERVER['DOCUMENT_ROOT'], str_replace( 'http://', '', get_template_directory_uri()));
			if( isset( $_FILES ) ) { 
				if( $_FILES["file"]["type"] == 'application/pdf' ) move_uploaded_file($_FILES["file"]["tmp_name"], $basedir . "/menus/$pagename-menu.pdf");
			}
		?>
		<script>
			jQuery( function($) {
				$('#menu-update-button').click( function() {
					$('#menu-update-form input[name="file"]').trigger('click');
				});
				$('#menu-update-form input[name="file"]').change(function() {
					$('#menu-submit-button').show();
				});
				$('#menu-submit-button').click( function() {
					$('#menu-update-form input[name="submit"]').trigger('click');
				});
			});
		</script>
		<?php
		} 
		?>
	</div>
<!-- End of Page Content -->
<?php } ?>

	<div class="menu-col-alpha">
	<div id="thumbnails">
	<ul style="list-style: none;">
	</ul>
	</div>
	</div>

	
</div>
</div>
</body>
</html>


<?php get_footer(); ?>
<?php if( IS_SITE_ADMIN ) : ?>
<script>
jQuery(function($) {
        $('.image-contents img').bind( "click", gallery_alt );
});
</script>
<?php endif; ?>
<script>
jQuery(function($) {
	$.each($('.menu-image ul img'), function( index, value ) {
		if( index == 0 ) return;	
		//$('#img-main').append($(value).clone().hide());
	});
	$('.menu-image ul img').hover( function() {
		if( $('#img-main img').attr('src') == $(this).attr('src') ) return;
		//$('#img-main img').attr('src', $(this).attr('src')).fadeIn();
		$('#img-main').html('').stop('true', 'true').hide().append($(this).clone()).fadeIn();
		//$('#img-main').append($(this));
		var dataID = $(this).attr('data-image-id');
		$('#img-main img').filter( function() {
			return ( $(this).attr('data-image-id') == dataID );
		}).show();
	});
});
</script>
