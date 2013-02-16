<?php
	// This is the default option array
    $option = array( );
    $option['form'] = array(
		'fields' => array(
			'tabbed-1' => array(
				array( "type" => "address", "label" => "address" ),
				array( "type" => "phone"  , "label" => "phone"   ),
				array( "type" => "email"  , "label" => "email"   ),
				array( "type" => "website", "label" => "website" ),
				array( "type" => "social" , "label" => "social"  ),
			),
			'tabbed-2' => array(
				array( "type" => "position", "label" => "position" ),
				array( "type" => "bio",      "label" => "bio"      ),
			),
			'normal' => array(
				array( "type" => "name",     "label" => "name" ),
			),
			'side' => array(
				array( "type" => "picture",  "label" => "picture" ),
			),
			'bench' => array(
				array( "type" => "department",               "label" => "department"                ),
				array( "type" => "courses",                  "label" => "courses"                   ),
				array( "type" => "officehours",              "label" => "office hours"              ),
				array( "type" => "education",                "label" => "education"                 ),
				array( "type" => "awards",                   "label" => "awards"                    ),
				array( "type" => "specialization",           "label" => "specialization"            ),
				array( "type" => "teaching",                 "label" => "teaching"                  ),
				array( "type" => "publications",             "label" => "publications"              ),
				array( "type" => "research",                 "label" => "research"                  ),
				array( "type" => "projects",                 "label" => "projects"                  ),
				array( "type" => "unitassociations",         "label" => "unit associations"         ),
				array( "type" => "professionalaffiliations", "label" => "professional affiliations" ),
				array( "type" => "graduatestudent",          "label" => "graduate student"          ),
			)
		),
		'tabs' => array( "Basic Info", "Bio" )
    );


    $option['page'] = array(
		'fields' => array(
			'tabbed-1' => array(
				array( "type" => "address", "label" => "address" ),
				array( "type" => "phone",   "label" => "phone"   ),
				array( "type" => "email",   "label" => "email"   ),
				array( "type" => "website", "label" => "website" ),
				array( "type" => "social",  "label" => "social"  ),
			),
			'tabbed-2' => array(
				array( "type" => "position", "label" => "position"  ),
				array( "type" => "bio",      "label" => "biography" ),
			),
			'header' => array(
				array( "type" => "picture", "label" => "picture" ),
				array( "type" => "name", "label" => "name" )
			),
			'bottom' => array(
			),
			'bench' => array(
				array( "type" => "department",               "label" => "department"                ),
				array( "type" => "education",                "label" => "education"                 ),
				array( "type" => "awards",                   "label" => "awards"                    ),
				array( "type" => "specialization",           "label" => "specialization"            ),
				array( "type" => "projects",                 "label" => "projects"                  ),
				array( "type" => "graduatestudent",          "label" => "graduate student"          ),
				array( "type" => "permalink",                "label" => "permalink"                 ),
				array( "type" => "unitassociations",         "label" => "unit associations"         ),
				array( "type" => "professionalaffiliations", "label" => "professional affiliations" ),
				array( "type" => "courses",                  "label" => "courses"                   ),
				array( "type" => "officehours",              "label" => "office hours"              ),
			)
		),
		'tabs' => array( "Basic Info", "Bio" )
    );


    $option['list'] = array(
		'fields' => array(
			'normal' => array(
				array( "type" => "picture", "label" => "picture" ),
				array( "type" => "name",    "label" => "name"    ),
				array( "type" => "phone",   "label" => "phone"   ),
				array( "type" => "email",   "label" => "email"   ),
			),
			'bench' => array(
				array( "type" => "address",          "label" => "address"           ),
				array( "type" => "website",          "label" => "website"           ),
				array( "type" => "social",           "label" => "social"            ),
				array( "type" => "position",         "label" => "position"          ),
				array( "type" => "department",       "label" => "department"        ),
				array( "type" => "courses",          "label" => "courses"           ),
				array( "type" => "officehours",      "label" => "office hours"      ),
				array( "type" => "education",        "label" => "education"         ),
				array( "type" => "awards",           "label" => "awards"            ),
				array( "type" => "specialization",   "label" => "specialization"    ),
				array( "type" => "projects",         "label" => "projects"          ),
				array( "type" => "graduatestudent",  "label" => "graduate student"  ),
				array( "type" => "permalink",        "label" => "permalink"         ),
				array( "type" => "unitassociations", "label" => "unit associations" ),
			)
		)
    );


    $option['settings'] = array(
		"picture" => array(
			"width"  => 150,
			"height" => 150,
		),
		"archive" => array(
			
		),
		'slug' => 'person',
		"permissions" => array(
			'administrator' => array(
				'edit_profile_cct'          => true,
				'edit_profiles_cct'         => true,
				'edit_others_profile_cct'   => true,
				'publish_profile_cct'       => true,
				'read_private_profile_cct'  => true,
				'delete_profile_cct'        => true,
				'delete_others_profile_cct' => true,
			),
			'editor' => array(
				'edit_profile_cct'          => true,
				'edit_profiles_cct'         => true,
				'edit_others_profile_cct'   => true,
				'publish_profile_cct'       => true,
				'read_private_profile_cct'  => true,
				'delete_profile_cct'        => true,
				'delete_others_profile_cct' => true,
			),
			'author' => array(
				'edit_profile_cct'          => true,
				'edit_profiles_cct'         => true,
				'edit_others_profile_cct'   => false,
				'publish_profile_cct'       => true,
				'read_private_profile_cct'  => false,
				'delete_profile_cct'        => false,
				'delete_others_profile_cct' => false
			),
			'contributor' => array(
				'edit_profile_cct'          => true,
				'edit_profiles_cct'         => false,
				'edit_others_profile_cct'   => false,
				'publish_profile_cct'       => false,
				'read_private_profile_cct'  => false,
				'delete_profile_cct'        => false,
				'delete_others_profile_cct' => false,
			),
			'subscriber' => array(
				'edit_profile_cct'          => true,
				'edit_profiles_cct'         => false,
				'edit_others_profile_cct'   => false,
				'publish_profile_cct'       => false,
				'read_private_profile_cct'  => false,
				'delete_profile_cct'        => false,
				'delete_others_profile_cct' => false,
			)
		)
    );

    $option['taxonomy'] = array( );

    $option['new_fields'] = array(
		"1.1" => array(
			array(
				'field' => array( "type" => "data", "label" => "data" ),
				'where' => array( "form", "page", "list" )
			)
		)
    );

