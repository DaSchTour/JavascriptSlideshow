// @TODO - this file could be fully jQuery-ized, to remove dependence on IDs.
var slideshowDivs = [];
var currentDivIndexes = [];

jQuery('.slideshow').each(function() {
	id = jQuery(this).attr('id');
	startSlideshow(id);
});

function getChildMaxImgWidth(parent) {
	var maxWidth = 0;
	var curWidth = 0;
	var child;
	var i;
	for (i = 0; i < parent.childNodes.length; i++) {
		child = parent.childNodes[i];
		if (child.tagName == 'IMG') {
			curWidth = child.getAttribute("width");
			if (curWidth > maxWidth) {
				maxWidth = curWidth;
			}
		} else {
			/* go recursive (needed for imgs in <a> tags) */
			maxWidth = getChildMaxImgWidth(child);
		}
	}
	return maxWidth;
}

function getChildDivs(id) {
	var parent = document.getElementById(id);
	var spacer = document.getElementById(id + '-spacer');
	var childDivs = [];
	var childDivCount = 0;
	var i;
	var maxHeight = 0;
	var maxWidth = 0;
	var maxImgWidth = 0;
	for (i = 0; i < parent.childNodes.length; i++) {
		var child = parent.childNodes[i];
		if (child.tagName == 'DIV') {
			childDivs[childDivCount++] = child;
			child.style.display = 'block';
			if (maxHeight < child.offsetHeight) {
				maxHeight = child.offsetHeight
			}
			if (maxWidth < child.offsetWidth) {
				maxWidth = child.offsetWidth
			}
			child.style.position = 'absolute';
			/* IE6 & IE8 need the div width to be set */
			maxImgWidth = getChildMaxImgWidth(child);
			if (maxImgWidth > 0) {
				child.style.width = maxImgWidth + 'px';
			}
			jQuery(child).hide();
		}
	}
	if (maxImgWidth > 0) {
		parent.style.width = maxImgWidth + 'px';
	}
	spacer.style.height = maxHeight + 'px';
	spacer.style.width = maxWidth + 'px';

	return childDivs;
}

function getInitialDivIndex(id, sequence) {
	var sequence = document.getElementById(id).getAttribute("data-sequence");
	var index = -1;
	if (sequence == 'forward') {
		index = 0;
	} else if (sequence == 'backward') {
		index = (slideshowDivs[id].length) - 1;
	} else if (sequence == 'random') {
		index = Math.floor(Math.random() * slideshowDivs[id].length);
	}
	jQuery(slideshowDivs[id][index]).show();
	return index;
}

function getNextDivIndex(id) {
	var sequence = document.getElementById(id).getAttribute("data-sequence");
	var index = -1;
	if (sequence == 'forward') {
		index = currentDivIndexes[id] + 1;
		if (index == slideshowDivs[id].length) {
			index = 0;
		}
	} else if (sequence == 'backward') {
		index = currentDivIndexes[id] - 1;
		if (index == -1) {
			index = slideshowDivs[id].length - 1;
		}
	} else if (sequence == 'random') {
		index = currentDivIndexes[id];
		if (slideshowDivs[id].length > 1) {
			while (index == currentDivIndexes[id]) {
				index = Math.floor(Math.random() * slideshowDivs[id].length);
			}
		}
	}

	return index;
}

function getNode(id, index) {
	return jQuery(slideshowDivs[id][index]);
}

function doTransition(parentId, currentNode, newNode) {
	var parent = document.getElementById(parentId);
	var transition = parent.getAttribute("data-transition");
	var duration = parent.getAttribute("data-transitiontime");

	if (transition == 'cut') {
		currentNode.hide();
		newNode.show();
	} else if (transition == 'fade') {
		currentNode.fadeOut(Number(duration));
		newNode.fadeIn(Number(duration));
	} else if (transition == 'blindDown') {
		currentNode.fadeOut(Number(duration));
		newNode.slideDown(Number(duration));
	}
}

function runSlideshow(id) {
	var newIndex = getNextDivIndex(id);
	doTransition(id, getNode(id, currentDivIndexes[id]), getNode(id, newIndex));
	currentDivIndexes[id] = newIndex;
}

function startSlideshow(id) {
	slideshowDivs[id] = getChildDivs(id);
	if (slideshowDivs[id].length > 0) {
		var refresh = document.getElementById(id).getAttribute("data-refresh");
		currentDivIndexes[id] = getInitialDivIndex(id);
		var tempFunc = function() {
			runSlideshow(id);
		};
		setInterval(tempFunc, refresh);
	}
}
