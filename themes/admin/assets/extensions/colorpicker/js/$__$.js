$__$.on("html-appended",function($html){
	var selector = ".input-color-picker";
	var doneClass = "input-color-picker-done";
	$html.find(selector).andSelf().filter(selector).each(function(){
		var $self = $(this);
		$self.addClass(doneClass);
		$self.hide();
		var $div = $('<div class="colorSelector"><div></div></div>');
		$div.insertAfter($self);
		var value = $self.val() ? $self.val() :'#0000ff';
		$div.ColorPicker({
			color: value,
			onShow: function (colpkr) {
				$(colpkr).show();
				return false;
			},
			onHide: function (colpkr) {
				$(colpkr).hide();
				return false;
			},
			onChange: function (hsb, hex, rgb) {
				$div.children('div').css('backgroundColor', '#' + hex);
				$self.val("#" + hex);
			}
		});
		$div.children('div').css('backgroundColor', value);
	});
});