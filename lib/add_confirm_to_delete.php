<?php
// trigger confirm dialogue
add_action('acf/input/admin_head', 'add_confirm_to_delete');

function add_confirm_to_delete() {

	?>
	<script type="text/javascript">
	(function($) {
		acf.add_action('ready', function(){

			$('body').on('click', '[data-event="remove-row"]', function( e ){
				return confirm("Are you sure you want to delete this?");
			});

		});

	})(jQuery);
	</script>

	<?php

}

?>
