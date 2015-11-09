<?php
// Fetch the Help page Instance
$help = Tribe__Admin__Help_Page::instance();

// Fetch plugins
$plugins = $help->get_plugins( null, false );

// Creates the Feature Box section
$help->add_section( 'feature-box', null, 0, 'box' );
$help->add_section_content( 'feature-box', '<img src="' . esc_url( plugins_url( 'resources/images/modern-tribe@2x.png', dirname( __FILE__ ) ) ) . '" alt="Modern Tribe Inc." title="Modern Tribe Inc.">' );
$help->add_section_content( 'feature-box', sprintf( esc_html__( 'Thanks you for using %s! All of us at Modern Tribe sincerely appreciate it and are stoked to have you on board.', 'tribe-common' ), $help->get_plugins_text() ) );

// Creates the Support section
$help->add_section( 'support', __( 'Getting Support', 'tribe-common' ), 10 );
$help->add_section_content( 'support', sprintf( __( 'The %s on our website is a great place to find tips and tricks for customizing the plugins and its functionality. It is an extensive collection of tested and updated methods to help you use, tweak, and customize the front-end in creative ways.', 'tribe-common' ), '<a href="http://m.tri.be/18j9" target="_blank">' . __( 'Knowledgebase', 'tribe-common' ) . '</a>' ), 0 );
$help->add_section_content( 'support', sprintf( __( '<strong>Want to dive deeper?</strong> Check out our %s for developers.', 'tribe-common' ), '<a href="http://m.tri.be/18jf" target="_blank">' . __( 'list of available functions', 'tribe-common' ) . '</a>' ), 50 );

// Creates the Extra Help section
$help->add_section( 'extra-help', __( 'I Need Extra Help!', 'tribe-common' ), 20 );
$help->add_section_content( 'extra-help', __( 'While the resources above help solve a majority of the issues we see, there are times you might be looking for extra support. If you need assistance with a legitimate bug with the plugin and would like us to take a look please follow these steps:', 'tribe-common' ), 0 );
$help->add_section_content( 'extra-help', array(
	'type' => 'ol',

	sprintf( __( '%s. All of the common (and not-so-common) answers to questions we see are all here. It’s often the fastest path to finding an answer!', 'tribe-common' ), '<strong><a href="http://m.tri.be/18j9" target="_blank">' . __( 'Check our Knowledgebase', 'tribe-common' ) . '</a></strong>' ),
	sprintf( __( '%s. Testing for an existing conflict is the best start for in-depth troubleshooting. We will often ask you to follow these steps when opening a new thread, so doing this ahead of time will be super helpful.', 'tribe-common' ), '<strong><a href="http://m.tri.be/18jh" target="_blank">' . __( 'Test for a theme or plugin conflict', 'tribe-common' ) . '</a></strong>' ),
	sprintf( __( '%s. There are very few issues we haven’t seen and it’s likely another user has already asked your question and gotten an answer from our support staff. While posting to the forums is open only to paid customers, they are open for anyone to search and review.', 'tribe-common' ), '<strong><a href="http://m.tri.be/4w/" target="_blank">' . __( 'Search our support forum', 'tribe-common' ) . '</a></strong>' ),
), 10 );

// By default these three will be gathered
$help->add_section_content( 'extra-help', __( 'If you have tried the steps above and are still having trouble, you can post a new thread to our open-source forums on WordPress.org:', 'the-events-calendar' ), 20 );
$help->add_section_content( 'extra-help', $help->get_plugin_forum_links(), 20 );
$help->add_section_content( 'extra-help', __( 'Our support staff monitors these forums once a week and would be happy to assist you there.', 'the-events-calendar' ), 20 );

$help->add_section_content( 'extra-help', sprintf( __( 'Looking for more immediate support? %s on our website with the purchase of any of our premium plugins. Pick up a license and you can post directly there and expect a response within 24-48 hours during weekdays.', 'the-events-calendar' ), '<a href="http://m.tri.be/4w/" target="_blank">' . esc_html__( 'We offer premium support', 'the-events-calendar' ) . '</a>' ), 30 );
$help->add_section_content( 'extra-help', __( 'Please note that all hands-on support is provided via the forums. You can email or tweet at us… ​but we will probably point you back to the forums 😄.', 'tribe-common' ), 40 );
$help->add_section_content( 'extra-help', '<div style="text-align: right;"><a href="http://m.tri.be/18ji" target="_blank" class="button">' . __( 'Read more about our support policy', 'tribe-common' ) . '</a></div>', 40 );

// Creates the System Info section
$help->add_section( 'system-info', __( 'System Information', 'tribe-common' ), 30 );
$help->add_section_content( 'system-info', __( 'The details of your plugins and settings are often needed for you or our staff to help troubleshoot an issue. Please copy and paste this information into the System Information field when opening a new thread and it will help us help you faster!', 'tribe-common' ), 0 );
?>

<div id="tribe-help-general">
	<?php $help->get_sections(); ?>
</div>


<div id="tribe-help-sidebar">
	<?php
	/**
	 * Fires at the top of the sidebar on Settings > Help tab
	 */
	do_action( 'tribe_help_sidebar_before' );

	foreach ( $plugins as $key => $plugin ) {
		$help->print_plugin_box( $key );
	}
	?>
	<h3><?php esc_html_e( 'News and Tutorials', 'tribe-common' ); ?></h3>
	<ul>
		<?php
		foreach ( $help->get_feed_items() as $item ) {
			echo '<li><a href="' . $help->get_ga_link( $item['link'], false ) . '">' . $item['title'] . '</a></li>';
		}
		echo '<li><a href="' . $help->get_ga_link( 'category/products' ) . '">' . esc_html__( 'More...', 'tribe-common' ) . '</a></li>';
		?>
	</ul>

	<?php
	/**
	 * Fires at the bottom of the sidebar on the Settings > Help tab
	 */
	do_action( 'tribe_help_sidebar_after' ); ?>

</div>