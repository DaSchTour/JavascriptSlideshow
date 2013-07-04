<?php
/**
 * Javascript Slideshow
 * Javascript Slideshow Hooks
 *
 * @author 		@See $wgExtensionCredits
 * @license		GPL
 * @package		Javacsript Slideshow
 * @link		http://www.mediawiki.org/wiki/Extension:Javascript_Slideshow
 *
**/

if (!defined('MEDIAWIKI')) {
	die();
}

$wgExtensionCredits['parserhook'][] = array(
		'path'=> __FILE__ ,
		'name' => 'Javascript Slideshow',
		'author' => array('Chris Reigrut', 'Yaron Koren', '[http://www.dasch-tour.de DaSch]', 'Alexia E. Smith', 'Nick White'),
		'version' => '1.2.3',
		'url' => 'http://www.mediawiki.org/wiki/Extension:Javascript_Slideshow',
		'descriptionmsg' => 'javascriptslideshow-desc',
);

$dir = dirname(__FILE__).'/';

//Internationalization
$wgExtensionMessagesFiles['JavascriptSlideshow']		= $dir.'JavascriptSlideshow.i18n.php';
$wgExtensionMessagesFiles['JavascriptSlideshowMagic']	= $dir.'JavascriptSlideshow.i18n.magic.php';

//Autoload Classes
$wgAutoloadClasses['JavascriptSlideshowHooks'] = $dir.'JavascriptSlideshow.hooks.php';

//Hooks
$wgHooks['ParserFirstCallInit'][]		= 'JavascriptSlideshowHooks::wfSlideshowExtension';

$slideshowResourceTemplate = array(
				'localBasePath' => $dir,
				'remoteExtPath' => 'JavascriptSlideshow',
		);
		
$wgResourceModules += array(
		'ext.slideshow.main' => $slideshowResourceTemplate + array(
				'scripts' => array('slideshow.js',),
		),
		'ext.slideshow.css' => $slideshowResourceTemplate + array(
				'styles' => array('JavascriptSlideshow.css',),
		),
);

/* Add a CSS module with addModuleStyles to ensure it's loaded
 * even if there is no Javascript support */
$wgExtensionFunctions[]	= function () {
	global $wgOut;
	$wgOut->addModuleStyles('ext.slideshow.css');
	return true;
}
?>
