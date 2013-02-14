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
        'author' => array('Chris Reigrut', 'Yaron Koren', 'DaSch', 'Alexia E. Smith'),
        'version' => '1.0',
        'url' => 'https://www.mediawiki.org/wiki/Extension:Javascript_Slideshow',
        'descriptionmsg' => 'javascriptslideshow-desc',
);
 
$dir = __DIR__."/";

//Internationalization
$wgExtensionMessagesFiles['JavascriptSlideshow']		= $dir.'JavascriptSlideshow.i18n.php';
$wgExtensionMessagesFiles['JavascriptSlideshowMagic']	= $dir.'JavascriptSlideshow.i18n.magic.php';

//Autoload Classes
$wgAutoloadClasses['JavascriptSlideshowHooks'] = $dir.'JavascriptSlideshow.hooks.php';

//Hooks
$wgHooks['ParserFirstCallInit'][]		= 'JavascriptSlideshowHooks::wfSlideshowExtension';
$wgHooks['MakeGlobalVariablesScript'][]	= 'JavascriptSlideshowHooks::wfSlideshowSetGlobalJSVariables';

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
?>