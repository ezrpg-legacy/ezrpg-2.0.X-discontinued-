var dialog = function(element, title) {
	
	var obj = $(element, { title: title });

	var 
		overlay = $('<div />').addClass('modal-overlay')
	,	dialog = $('<div />').addClass('dialog')
	,	heading = $('<div />')
					.addClass('header')
					.append(
						$('<span />').html(title)
					)
					.appendTo(dialog);

	overlay
		.css({
			width: $(document).width() + 'px',
			height: $(document).height() + 'px'
		})
		.hide();

	overlay
		.appendTo('body')
		.fadeIn(150);

	dialog
		.append(obj)
		.hide()
		.appendTo('body')
		.fadeIn(250);

	return true;
};