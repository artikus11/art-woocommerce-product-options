( function( $ ) {
	'use strict';

	/**
	 * @typedef {Object} awpo_scripts_settings
	 * @property {string} price_decimal_sep - Десятичный разделитель.
	 * @property {string} price_num_decimals - Число дробных знаков.
	 * @property {string} price_thousand_sep - Разделитель тысяч.
	 * @property {string} price_currency_pos - Позиция валюты.
	 * @property {string} price - Цена.
	 * @property {string} required_text - Текст об обязательности.
	 */
	class AwpoProductOptions {
		constructor( options ) {

			Object.assign( this, options );

			this.initialize();
		}

		initialize() {
			this.element = $( '#awpo_product_options' );
			this.priceDiv = this.isOnSale
			                ? $( `.product .summary .price ins .woocommerce-Price-amount` )
			                : $( `.product .summary .price .woocommerce-Price-amount` );

			const bdi = this.priceDiv.find( 'bdi' );

			if ( bdi.length ) {
				this.priceDiv = bdi;
			}

			this.form = this.element.closest( 'form' );

			this.form.on( 'submit', ( e ) => this.validate( e ) );

			this.element.on( 'change', '.awpo-option', ( e ) => this.updatePrice() );
		}

		updatePrice( e ) {
			let price = awpo_scripts_settings.price;
			const elements = this.element.find( '.awpo-option' );
			console.log( price );

			//price = price.replace( awpo_scripts_settings.price_decimal_sep, '.' );
			price = price.replace( /[^.\d]+/g, '' );
			price = parseFloat( price.replace( awpo_scripts_settings.price_decimal_sep, '.' ) );
			//price = price.replace( /\B(?=(\d{3})+(?!\d))/g, awpo_scripts_settings.price_thousand_sep );
			console.log( price );
			elements.toArray().forEach( ( el ) => {
				const $el = $( el );
				const type = el.type;

				switch ( type ) {
					case 'select-one': {
						const vId = $el.val();
						if ( vId && this.valuePrices[ vId ] ) {
							price += this.valuePrices[ vId ];
						}
						break;
					}
					case 'select-multiple': {
						const vIds = $el.val();
						if ( vIds ) {
							vIds.forEach( ( vId ) => {
								if ( this.valuePrices[ vId ] ) {
									price += this.valuePrices[ vId ];
								}
							} );
						}
						break;
					}
					case 'radio':
					case 'checkbox': {
						if ( el.checked ) {
							console.log( el.value );
							const vId = el.value;
							if ( vId && this.valuePrices[ vId ] ) {
								let valuePrice = this.valuePrices[ vId ];
								console.log( valuePrice );
								price += valuePrice;
							}
						}
						break;
					}
					case 'text':
					case 'textarea': {
						if ( $el.val() !== '' ) {
							const oId = parseInt( el.name.match( /\[(\d+)\]/ )[ 1 ], 10 );
							if ( this.optionPrices[ oId ] ) {
								price += this.optionPrices[ oId ];
							}
						}
						break;
					}
					default:
						break;
				}
			} );

			let formattedPrice = price
				.toFixed( awpo_scripts_settings.price_num_decimals )
				.replace( '.', awpo_scripts_settings.price_decimal_sep );

			formattedPrice = formattedPrice
				.toString()
				.replace( /\B(?=(\d{3})+(?!\d))/g, awpo_scripts_settings.price_thousand_sep );

			console.log( formattedPrice );
			console.log( awpo_scripts_settings.price_currency_pos );

			if ( awpo_scripts_settings.price_currency_pos === 'left_space' ) {
				formattedPrice = ` ${ formattedPrice }`;
			} else if ( awpo_scripts_settings.price_currency_pos === 'right_space' ) {
				formattedPrice += ' ';
			}

			if ( awpo_scripts_settings.price_currency_pos === 'left' || awpo_scripts_settings.price_currency_pos === 'left_space' ) {
				console.log( this.priceDiv );
				this.priceDiv.contents().last()[ 0 ].textContent = formattedPrice;
			} else {
				this.priceDiv.contents().first()[ 0 ].textContent = formattedPrice;
			}
		}

		validate( event ) {
			let firstInvalidInput;
			let isFormValid = true;

			this.element.find( '.awpo-required.awpo-not-valid' ).removeClass( 'awpo-not-valid' );
			this.element.find( '.awpo-required .awpo-required-text' ).remove();

			const requiredText = awpo_scripts_settings.required_text;

			this.element.find( '.awpo-required' ).toArray().forEach( ( el ) => {
				const $el = $( el );
				const input = $el.find( '.awpo-option' ).first();
				const type = input[ 0 ].type;
				let isValid = true;

				switch ( type ) {
					case 'select-one':
					case 'select-multiple':
						isValid = input.val() !== '' && input.val() !== null;
						break;
					case 'radio':
					case 'checkbox':
						isValid = $el.find( '.awpo-option:checked' ).length > 0;
						break;
					case 'text':
					case 'textarea':
						isValid = input.val() !== '';
						break;
					default:
						break;
				}

				if ( ! isValid ) {
					$el.addClass( 'awpo-not-valid' );
					$el.append( `<div class="awpo-required-text">${ requiredText }</div>` );

					if ( ! firstInvalidInput ) {
						firstInvalidInput = input;
					}

					isFormValid = false;
				}
			} );

			if ( firstInvalidInput ) {
				firstInvalidInput.focus();
			}

			if ( ! isFormValid ) {
				event.preventDefault();
			}

			return isFormValid;
		}

	}

	$( document ).ready( () => {
		const wrapperOptions = $( '#awpo_product_options' );
		const options = wrapperOptions.data( 'product_options' );

		new AwpoProductOptions( options );
	} );
} )( jQuery );
