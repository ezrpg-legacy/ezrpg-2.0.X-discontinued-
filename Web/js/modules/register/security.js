var security = {
	botCheck : function(){
		var countUp = function(value) {
			var 
				val = parseInt(($('input[name=username]').val().length + $('input[name=email]').val().length), 10);

			$('input[name=verify]').val(val);
		};

		$('input[name=username]').keyup(function(){
			countUp();
		});

		$('input[name=email]').keyup(function(){
			countUp();
		});
		
		// fixes a bug wherein if the user is redirected, the counter doesn't count.
		countUp();
	}
};