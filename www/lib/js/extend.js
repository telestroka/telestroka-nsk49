/**
 * Standart objects extend
 *
 * @author Mikhail Miropolskiy <the-ms@ya.ru>
 * @package Lib
 * @copyright (c) 2012. Mikhail Miropolskiy. All Rights Reserved.
 */

/**
 * Remove DOM node yourself
 */
HTMLElement.prototype.remove = function() {
	this.parentNode.removeChild(this);
};

/**
 * Return random number between min and max
 * @param {Number} min
 * @param {Number} max
 * @return {Number} Random number
 */
Math.rand = function(min, max) {
	var argc = arguments.length;
	 if (argc === 0) {
		 min = 0;
		 max = 2147483647;
	 } else if (argc === 1) {
		 throw new Error('Warning: rand() expects exactly 2 parameters, 1 given');
	 }
	 return Math.floor(Math.random() * (max - min + 1)) + min;
};

/**
 * Return cookie value by name
 * @param {String} name
 * @return {String|Null} value
 */
document.getCookie = function (name) {
    var matches = document.cookie.match(name + '=(.*?)(;|$)');
    return matches ? matches[1] : null
};

/**
 * Set cookie
 * @param {String} name
 * @param {String} value
 * @param {Number} days
 */
document.setCookie = function (name, value, days) {
    var expires = new Date;
    expires.setDate(expires.getDate() + days);
    document.cookie = name + '=' + value + '; path=/; expires=' + expires.toUTCString();
};

document.favorite = function (a) {
	var title = document.title,
		url = document.location.href;

	try {
		// Internet Explorer
		window.external.AddFavorite(url, title);
	}
	catch (e) {
		try {
			// Mozilla
			window.sidebar.addPanel(title, url, '');
		}
		catch (e) {
			// Opera
			if (typeof(opera) == 'object') {
				a.rel = 'sidebar';
				a.title = title;
				a.url = url;
				a.href = url;

				return true;
			} else {
				// Unknown
				alert('Нажмите Ctrl-D чтобы добавить страницу в закладки');
			}
		}
	}

	return false;
}