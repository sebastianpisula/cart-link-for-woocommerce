jQuery( function ( $ ) {
	$( document.body ).on( 'click', '.js--add-product-button', function () {
		let template = wp.template( 'table-row-item' );

		$( '#the-list' ).append( template().replaceAll( '[XXX]', '[' + Date.now() + ']' ) );

		$( '.no-items' ).remove();

		$( document.body ).trigger( 'wc-enhanced-select-init' );

		return false;
	} ).on( 'click', '.js--delete-selected-products-button', function () {
		$( '.js--cb-field:checked' ).closest( 'tr' ).remove();

		if ( $( '#the-list tr' ).length === 0 ) {
			$( '#the-list' ).append( wp.template( 'no-items' )() );
		}

		return false;
	} ).on( 'change', '.js--action-field-trigger, #cb-select-all-1', function () {
		let disabled = $( '.js--cb-field:checked' ).length === 0;

		if ( disabled ) {
			$( '.js--action-field' ).attr( 'disabled', 'disabled' )
		} else {
			$( '.js--action-field' ).removeAttr( 'disabled' )
		}

		return false;
	} ).on( 'click', '.js--duplicate-selected-products-button', function () {
		$( '.js--cb-field:checked' ).prop( 'checked', false ).closest( 'tr' ).each( function () {
			let $tr = $( this );
			let $clone = $tr.clone();
			let unique = Math.random().toString( 36 ).substr( 2, 9 );

			$clone.find( ':input[data-item_id]' ).each( function () {
				let item_id = $( this ).attr( 'data-item_id' );

				$( this ).attr( 'name', $( this ).attr( 'name' ).replace( item_id, unique ) );
				$( this ).attr( 'data-item_id', unique );
			} );

			$tr.after( $clone );
		} );

		return false;
	} ).on( 'click', '.js--campaign-change-status', function () {
		let field = $( this );
		let new_post_status;
		let current_status = field.attr( 'data-status' );
		let post_id = field.attr( 'data-id' );
		let url_field = field.closest( 'tr' ).find( '.js--copy-campaign-url' );

		if ( current_status !== 'publish' ) {
			new_post_status = 'publish';
		} else {
			new_post_status = 'draft';
		}

		jQuery.ajax( {
			method: 'POST',
			url: __jsVars.url.rest_api + 'wp/v2/' + __jsVars.post_type + '/' + post_id + '/',
			data: { 'status': new_post_status },
			dataType: 'json',

			beforeSend: function ( xhr ) {
				xhr.setRequestHeader( 'X-WP-Nonce', __jsVars.nonce );
			},

			success: function ( data ) {

				url_field.val( data.link );
				field.attr( 'data-status', data.status )
				field.removeClass( 'woocommerce-input-toggle--enabled, woocommerce-input-toggle--disabled' )

				if ( data.status === 'publish' ) {
					url_field.closest( '.form-row' ).removeClass( 'hidden' );
					field.addClass( 'woocommerce-input-toggle--enabled' )
				} else {
					url_field.closest( '.form-row' ).addClass( 'hidden' );
					field.addClass( 'woocommerce-input-toggle--disabled' )
				}
			},
		} );

		return false;
	} ).on( 'click', '.js--copy-campaign-url', function () {
		$( this ).closest( '.form-row' ).find( '.js--copy-button' ).click();

		return false;
	} ).on( 'click', '.js--copy-button', function () {
		let button = $( this );
		let copied_text = button.attr( 'data-copied_text' );
		let current_text = button.text();

		$( this ).text( copied_text );

		setTimeout( function () {
			button.text( current_text );
		}, 1000 )

		$( this ).closest( '.form-row' ).find( '.js--copy-campaign-url' ).select();
		document.execCommand( "copy" );

		return false;
	} );
} );
