<div class="marker_icons">
	<?php
	$marker_icon = get_option( 'cbn_marker_icon' ) ? get_option( 'cbn_marker_icon' ) : 'default';
	$items       = $this->marker_icons;

	foreach ( $items as $val ) {
		$selected = ( $marker_icon == $val ) ? 'checked' : '';
		echo "<label class='$selected'><div class='marker_icon_preview' data-style='$val'></div><input type='radio' name='cbn_marker_icon' $selected value='$val'></label>";
	}

	?>


	<?php
	//pro marker icons
	$marker_user_icon = get_option( 'cbn_marker_user_icon' );
	$pro_items        = $this->pro_marker_icons;

	foreach ( $pro_items as $val ) {
		$selected        = ( $marker_icon == $val ) ? 'checked' : '';
		$user_icon_style = ( $marker_user_icon ) ? "style='background-image: url($marker_user_icon)'" : '';
		echo "<label class='$selected pro label_marker_user_icon'><div id='cbn_marker_user_icon_preview' class='marker_icon_preview' data-style='$val' " . $user_icon_style . "></div><input type='radio' name='cbn_marker_icon' $selected value='$val'>";

		echo "
          <div class='icon_upload'>
            <a href='#' class='cbn_upload_icon_button button button-secondary'>" . __( 'Upload Icon', 'compass' ) . "</a>
            <p class='description'>PNG, max. 100px</p>
            <input type='hidden' id='cbn_marker_user_icon' name='cbn_marker_user_icon' value='" . esc_attr( $marker_user_icon ) . "'>
          </div>
        ";

		echo '</label>';
	}
	?>

	<?php if ( true ) : ?>

		<?php
		//pro marker icons
		$pro_items = $this->pro_marker_icons;

		foreach ( $pro_items as $val ) {
			echo "<label class='label_marker_user_icon'><div class='marker_icon_preview' data-style='$val'></div>";

			echo "
        <div class='icon_upload'>
          <button disabled class='button button-secondary'>" . __( 'Upload Icon', 'compass' ) . "</button>
          <p class='description'>PNG, max. 100px</p>
        </div>
      ";
			echo '</label>';
		}
		?>

	<?php endif; ?>

</div>
