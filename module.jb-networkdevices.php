<?php

/**
 * @copyright   Copyright (C) 2021 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     2021-08-26 16:05:02
 *
 * iTop module definition file
 */

SetupWebPage::AddModule(
        __FILE__, // Path to the current file, all other file names are relative to the directory containing this file
        'jb-networkdevices/2.6.210826',
        array(
                // Identification
                //
                'label' => 'Datamodel: Network devices - subclasses',
                'category' => 'business',

                // Setup
                //
                'dependencies' => array( 
					'itop-config-mgmt/2.7.0'
                ),
                'mandatory' => false,
                'visible' => true,

                // Components
                //
                'datamodel' => array(
					'model.jb-networkdevices.php'
                ),
                'webservice' => array(

                ),
                'data.struct' => array(
					// add your 'structure' definition XML files here,
                ),
                'data.sample' => array(
					// add your sample data XML files here,
                ),

                // Documentation
                //
                'doc.manual_setup' => '', // hyperlink to manual setup documentation, if any
                'doc.more_information' => '', // hyperlink to more information, if any

                // Default settings
                //
                'settings' => array(
                        // Module specific settings go here, if any
                ),
        )
);



