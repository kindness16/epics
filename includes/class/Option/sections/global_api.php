<?php

$options = array();

$options[] = array(
	'id'          => 'epic-ne[youtube-api]',
	'transport'   => 'refresh',
	'type'        => 'jeg-text',
	'label'       => esc_html__( 'YouTube API Key', 'epic-ne' ),
	'description' => sprintf(
		__( 'Insert your youtube API right here. For more information, <a href="%s">please go here</a>', 'epic-ne' ),
		'https://developers.google.com/youtube/v3/getting-started'
	),
	'option_type' => 'option',
);

return $options;
