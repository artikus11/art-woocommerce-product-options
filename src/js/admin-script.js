( function ( $ ) {
	"use strict";

	$.widget( "pektsekye.awpoProductOptions", {

		lastOptionId:       0,
		lastSortOrder:      0,
		lastValueId:        0,
		lastValueSortOrder: {},


		_create: function () {

			$.extend( this, this.options );

			this._on( {
				"click button.awpo-delete-option-button":       $.proxy( this.deleteOption, this ),
				"click button.awpo-add-option-button":          $.proxy( this.addOption, this ),
				"change select.awpo-option-type-select":        $.proxy( this.onTypeChange, this ),
				"click button.awpo-add-option-value-button":    $.proxy( this.addRow, this ),
				"click button.awpo-delete-option-value-button": $.proxy( this.deleteRow, this )
			} );

			$( '.product_options_for_woocommerce_tab' ).click( $.proxy( this.loadOptions, this ) );
		},


		loadOptions: function () {
			if ( this.option_ids && ! this.optionsLoaded ) {
				const l = this.option_ids.length;
				for ( let i = 0; i < l; i++ ) {
					this.addOption( {}, this.option_ids[ i ] );
				}
				this.optionsLoaded = true;
			}
		},


		addOption: function ( e, optionId ) {
			let data;

			if ( optionId ) {
				data    = this.options_data[ optionId ];
				data.id = data.option_id;
			} else {
				data            = {};
				data.id         = this.last_option_id + 1;
				data.option_id  = -1;
				data.sort_order = this.last_sort_order + 1;
				data.required   = 1;
				this.last_option_id++;
				this.last_sort_order++;
			}

			const template = wp.template( 'awpo-custom-option-base' );
			$( '#awpo_product_options_container' ).append( template( data ) );

			if ( optionId ) {
				$( '#awpo_option_' + optionId + '_type' ).val( data.type ).change();
			}
		},


		onTypeChange: function ( e ) {
			const currentElement = $( e.target );
			const group          = currentElement.find( '[value="' + currentElement.val() + '"]' ).closest( 'optgroup' ).attr( 'data-optgroup-name' );

			if ( ! group ) {
				return;
			}

			const parentId = '#' + currentElement.closest( '.fieldset-inner' ).attr( 'id' );

			const id = parseInt( $( parentId + '_id' ).val() );

			const prevGroup = $( parentId + '_group' ).val();

			let data;
			if ( this.options_data && this.options_data[ id ] ) {
				data    = $.extend( {}, this.options_data[ id ] );
				data.id = data.option_id;
				//data.price = data.price !== 0 ? data.price.toFixed( 2 ) : '';
			} else {
				data    = {};
				data.id = id;
			}

			if ( group === prevGroup ) {
				return;
			} else if ( prevGroup !== '' ) {
				$( '#awpo_option_' + id + '_type_' + prevGroup ).remove();
			}

			const template = wp.template( 'custom-option-' + group + '-type' );

			$( '#awpo_option_' + id ).append( template( data ) );

			$( parentId + '_group' ).val( group );

			if ( group === 'select' ) {
				if ( this.options_data && this.options_data[ id ] && this.options_data[ id ][ 'values' ] ) {
					const l = this.options_data[ id ][ 'values' ].length;
					for ( let i = 0; i < l; i++ ) {
						this.addRow( {}, id, this.options_data[ id ][ 'values' ][ i ] );
					}
				} else {
					this.addRow( {}, id );
				}
			}
		},


		addRow: function ( e, id, rowData ) {

			if ( ! id ) {
				const currentElement = $( e.target );
				const parentId       = '#' + currentElement.closest( '.fieldset-inner' ).attr( 'id' );
				id                   = parseInt( $( parentId + '_id' ).val() );
			}

			let data;

			if ( rowData ) {
				data     = $.extend( {}, rowData );
				data.id  = id;
				data.vid = data.value_id;
				//data.price = data.price != 0 ? data.price.toFixed( 2 ) : '';
			} else {
				data     = {};
				data.id  = id;
				data.vid = this.last_value_id + 1;
				if ( ! this.last_value_sort_order[ id ] )
					this.last_value_sort_order[ id ] = 0;
				data.sort_order = this.last_value_sort_order[ id ] + 1;
				data.value_id   = -1;
				this.last_value_id++;
				this.last_value_sort_order[ id ]++;
			}

			const template = wp.template( 'custom-option-select-type-row' );
			$( '#awpo_select_option_type_row_' + id ).append( template( data ) );
		},


		deleteOption: function ( e ) {
			const optionWrapper = $( e.target ).closest( '.fieldset-wrapper' );

			optionWrapper.remove();
		},


		deleteRow: function ( e ) {
			const tr = $( e.target ).closest( 'tr' );

			tr.remove();
		}

	} );

	const config       = {};
	let wrapperOptions = $( '#awpo_product_options' );

	const options = wrapperOptions.data( 'product_options' );


	wrapperOptions.awpoProductOptions( config, options );

} )( jQuery );
