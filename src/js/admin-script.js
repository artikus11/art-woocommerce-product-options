( function( $ ) {
	'use strict';

	class AwpoProductOptions {
		constructor( options ) {
			/**
			 * Данные всех опций, сгруппированные по их идентификаторам.
			 * @type {Object.<Object>}
			 */
			this.options_data = options.options_data || {};

			/**
			 * Массив идентификаторов опций, которые используются для загрузки опций.
			 * @type {number[]}
			 * @description Если передан массив `option_ids`, система автоматически загрузит соответствующие опции.
			 */
			this.option_ids = options.option_ids || [];

			this.lastOptionId = 0;
			this.lastSortOrder = 0;
			this.lastValueId = 0;
			this.lastValueSortOrder = {};

			Object.assign( this, options );

			this.initialize();
		}

		initialize() {
			this.bindEvents();
		}

		bindEvents() {
			$( document ).on( 'click', 'button.awpo-delete-option-button', ( e ) => this.deleteOption( e ) );
			$( document ).on( 'click', 'button.awpo-add-option-button', ( e ) => this.addOption( e ) );
			$( document ).on( 'change', 'select.awpo-option-type-select', ( e ) => this.onTypeChange( e ) );
			$( document ).on( 'click', 'button.awpo-add-option-value-button', ( e ) => this.addRow( e ) );
			$( document ).on( 'click', 'button.awpo-delete-option-value-button', ( e ) => this.deleteRow( e ) );
			$( document ).on( 'click', '.awpo_product_options_tab', () => this.loadOptions() );
		}

		loadOptions() {
			if ( this.option_ids && ! this.optionsLoaded ) {
				this.option_ids.forEach( ( id ) => this.addOption( {}, id ) );
				this.optionsLoaded = true;
			}
		}

		addOption( {}, optionId = null ) {
			let data;
			if ( optionId ) {
				data = { ...this.options_data[ optionId ], id: this.options_data[ optionId ].option_id };
			} else {
				data = {
					id: ++this.lastOptionId,
					option_id: -1,
					sort_order: ++this.lastSortOrder,
					required: 1,
				};
			}

			const template = wp.template( 'awpo-custom-option-base' );
			$( '#awpo_product_options_container' ).append( template( data ) );

			if ( optionId ) {
				$( `#awpo_option_${ optionId }_type` ).val( data.type ).trigger( 'change' );
			}
		}

		addRow( event = {}, id = null, rowData = null ) {
			if ( ! id ) {
				const currentElement = $( event.target );
				const parentId = `#${ currentElement.closest( '.fieldset-inner' ).attr( 'id' ) }`;
				id = parseInt( $( `${ parentId }_id` ).val(), 10 );
			}

			let data;
			if ( rowData ) {
				data = {
					...rowData,
					id,
					vid: rowData.value_id,
					price: rowData.price,
				};
			} else {
				data = {
					id,
					vid: ++this.lastValueId,
					value_id: -1,
					sort_order: ( this.lastValueSortOrder[ id ] = ( this.lastValueSortOrder[ id ] || 0 ) + 1 ),
				};
			}

			const template = wp.template( 'custom-option-select-type-row' );
			$( `#awpo_select_option_type_row_${ id }` ).append( template( data ) );
		}

		deleteOption( event ) {
			const optionWrapper = $( event.target ).closest( '.fieldset-wrapper' );
			optionWrapper.remove();
		}

		deleteRow( event ) {
			const row = $( event.target ).closest( 'tr' );
			row.remove();
		}

		onTypeChange( event ) {
			const currentElement = $( event.target );
			const group = currentElement
				.find( `[value="${ currentElement.val() }"]` )
				.closest( 'optgroup' )
				.attr( 'data-optgroup-name' );
			if ( ! group ) {
				return;
			}

			const parentId = `#${ currentElement.closest( '.fieldset-inner' ).attr( 'id' ) }`;
			const id = parseInt( $( `${ parentId }_id` ).val(), 10 );
			const prevGroup = $( `${ parentId }_group` ).val();

			let data;
			if ( this.options_data?.[ id ] ) {
				data = {
					...this.options_data[ id ],
					id: this.options_data[ id ].option_id,
					price: ( this.options_data[ id ].price || 0 ).toFixed( 2 ),
				};
			} else {
				data = { id };
			}

			if ( group === prevGroup ) {
				return;
			}

			if ( prevGroup ) {
				$( `#awpo_option_${ id }_type_${ prevGroup }` ).remove();
			}

			const template = wp.template( `custom-option-${ group }-type` );

			$( `#awpo_option_${ id }` ).append( template( data ) );

			$( `${ parentId }_group` ).val( group );

			if ( group === 'select' ) {
				const values = this.options_data?.[ id ]?.values || [];
				values.forEach( ( value ) => this.addRow( {}, id, value ) );

				if ( ! values.length ) {
					this.addRow( {}, id );
				}
			}
		}
	}

	$( document ).ready( () => {
		const wrapperOptions = $( '#awpo_product_options' );
		const options = wrapperOptions.data( 'product_options' );

		new AwpoProductOptions( options );
	} );
}( jQuery ) );
