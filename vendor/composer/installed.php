<?php return array(
    'root' => array(
        'name' => 'danmarsden/moodle-mod_attendance',
        'pretty_version' => 'dev-main',
        'version' => 'dev-main',
        'reference' => '0dcc32791828208656ce7f09eebd46bc2545f155',
        'type' => 'moodle-mod',
        'install_path' => __DIR__ . '/../../',
        'aliases' => array(),
        'dev' => true,
    ),
    'versions' => array(
        'composer/installers' => array(
            'pretty_version' => 'v1.12.0',
            'version' => '1.12.0.0',
            'reference' => 'd20a64ed3c94748397ff5973488761b22f6d3f19',
            'type' => 'composer-plugin',
            'install_path' => __DIR__ . '/./installers',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'danmarsden/moodle-mod_attendance' => array(
            'pretty_version' => 'dev-main',
            'version' => 'dev-main',
            'reference' => '0dcc32791828208656ce7f09eebd46bc2545f155',
            'type' => 'moodle-mod',
            'install_path' => __DIR__ . '/../../',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'roundcube/plugin-installer' => array(
            'dev_requirement' => false,
            'replaced' => array(
                0 => '*',
            ),
        ),
        'shama/baton' => array(
            'dev_requirement' => false,
            'replaced' => array(
                0 => '*',
            ),
        ),
    ),
);
