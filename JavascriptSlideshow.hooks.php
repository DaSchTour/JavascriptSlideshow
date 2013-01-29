<?php
class JavascriptSlideshowHooks {
	
	function wfSlideshowExtension() {
		global $wgOut, $wgParser;
		
		$wgOut->addModules( 'ext.slideshow.main' );
		$wgParser->setHook( 'slideshow', 'JavascriptSlideshowHooks::renderSlideshowTag' );
		$wgParser->setFunctionHook( 'slideshow', 'JavascriptSlideshowHooks::renderSlideshowParserFunction' );
	}
	
	function explode_assoc($glue1, $glue2, $array) {
		$array2 = explode($glue2, $array);
		foreach($array2 as $val) {
			$pos = strpos($val,$glue1);
			$key = substr($val,0,$pos);
			$array3[$key] = trim(substr($val,$pos+1,strlen($val)));
		}
		return $array3;
	}
	
	function renderSlideshowParserFunction( &$parser, $input = '', $options = '' ) {
		$parser->disableCache();
		return renderSlideshow( $input, explode_assoc( '=', ' ', $options ) );
	}
	
	# The callback function for converting the input text to HTML output
	function renderSlideshowTag( $input, $argv, $parser, $frame ) {
	$wikitext = JavascriptSlideshowHooks::renderSlideshow( $input, $argv );
	$parser->disableCache();
	return $parser->recursiveTagParse( $wikitext, $frame );
	}
	
	function renderSlideshow( $wikitext, $options ) {
	$isValid = true;
	$validSequences = array( 'forward', 'backward', 'random' );
	$validTransitions = array( 'cut', 'fade', 'blindDown' );
	
	$output = '';
	
	$id = ( isset( $options['id'] ) ) ? $options['id'] : 'slideshow_' . rand();
	$refresh = ( isset( $options['refresh'] ) ) ? $options['refresh'] : '1000';
	
	$sequence = ( isset( $options['sequence'] ) ) ? $options['sequence'] : 'forward';
	if ( ! in_array( $sequence, $validSequences ) ) {
	$output .= "Invalid sequence $sequence (may be one of: " . implode(',', $validSequences) . "). ";
		$isValid = false;
	}
	
	$transition = (isset($options['transition'])) ? $options['transition'] : 'cut';
			if ( ! in_array( $transition, $validTransitions ) ) {
		$output .= "Invalid transition $transition (may be one of: " . implode(',', $validTransitions) . "). ";
		$isValid = false;
	}
	
	if ($isValid) {
	global $wgSlideshowRefresh, $wgSlideshowSequence, $wgSlideshowTransition;
	$wgSlideshowRefresh = $refresh;
	$wgSlideshowSequence = $sequence;
		$wgSlideshowTransition = $transition;
		$output .= "<div id='$id' class='slideshow'>$wikitext</div> ";
		$output .= "<div id='$id-spacer' class='slideshowspacer'></div>";
		}
	
		return $output;
		}
	
		// @TODO - this use of global variables means that two slideshows on the
		// same page can't have different settings, which is a bug.
	function wfSlideshowSetGlobalJSVariables( &$vars ) {
		global $wgSlideshowRefresh, $wgSlideshowSequence, $wgSlideshowTransition;
		$vars['wgSlideshowRefresh'] = $wgSlideshowRefresh;
		$vars['wgSlideshowSequence'] = $wgSlideshowSequence;
		$vars['wgSlideshowTransition'] = $wgSlideshowTransition;
		return true;
	}
}