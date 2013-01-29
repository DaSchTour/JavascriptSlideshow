<?php
# This extension will show the contents of a single <div> inside of it, and will
# rotate through the remaining divs as specified
# To activate the extension, include it from your LocalSettings.php
# with: include("extensions/Slideshow/slideshow.php");
#
#
if( !defined( 'MEDIAWIKI' ) ) {
        die();
}
 
$wgExtensionCredits['other'][] = array(
		'path'=> __FILE__ ,
		'name' => 'Javascript Slideshow',
        'author' => array( 'Chris Reigrut', 'Yaron Koren', 'DaSch' ),
        'version' => '0.4',
        'url' => 'https://www.mediawiki.org/wiki/Extension:Javascript_Slideshow',
        'descriptionmsg' => 'javascriptslideshow-desc',
);
 
$dir = dirname( __FILE__ );

// Internationalization
$wgExtensionMessagesFiles['JavascriptSlideshow'] = $dir . '/' . 'JavascriptSlideshow.i18n.php';
$wgExtensionMessagesFiles['JavascriptSlideshowMagic'] = $dir . '/' . 'JavascriptSlideshow.i18n.magic.php';

# Define a setup function
$wgAutoloadClasses['JavascriptSlideshowHooks'] = $dir . '/' .'JavascriptSlideshow.hooks.php';
$wgExtensionFunctions[] = 'JavascriptSlideshowHooks::wfSlideshowExtension';
$wgHooks['MakeGlobalVariablesScript'][] = 'JavascriptSlideshowHooks::wfSlideshowSetGlobalJSVariables';

$slideshowResourceTemplate = array(
				'localBasePath' => $dir,
				'remoteExtPath' => 'Slideshow',
		);
		
		$wgResourceModules += array(
				'ext.slideshow.main' => $slideshowResourceTemplate + array(
						'scripts' => array(
								'slideshow.js',
						),
				),
		);