( function( $ ) {
	'use strict';

	/**
	 * Класс для управления пользовательскими опциями продукта.
	 *
	 * @typedef {Object} awpo_scripts_settings
	 * @property {string} price_decimal_sep - Десятичный разделитель.
	 * @property {string} price_num_decimals - Число дробных знаков.
	 * @property {string} price_thousand_sep - Разделитель тысяч.
	 * @property {string} price_currency_pos - Позиция валюты.
	 * @property {string} price - Цена продукта.
	 * @property {boolean} is_sale - Флаг наличия скидки на продукт.
	 * @property {string} required_text - Текст об обязательности заполнения.
	 *
	 * @typedef {Object} product_options
	 * @property {Object.<string|number, number>} valuePrices - Цены для опций.
	 * @property {Object.<string|number, number>} optionPrices - Цены для текстовых/многострочных опций.
	 */
	class AwpoProductOptions {
		constructor( settings, options ) {
			this.settings = settings;

			/**
			 * Объект, содержащий цены опций по их идентификаторам.
			 * @type {Object.<Object>}
			 */
			this.valuePrices = options.valuePrices || {};

			Object.assign( this, options );

			this.initialize();
		}

		initialize() {
			this.element = $( '#awpo_product_options' );
			this.priceDiv = this.settings.is_sale
				? $( `.product .summary .price ins .woocommerce-Price-amount` )
				: $( `.product .summary .price .woocommerce-Price-amount` );

			const bdi = this.priceDiv.find( 'bdi' );

			if ( bdi.length ) {
				this.priceDiv = bdi;
			}

			this.element.on( 'change', '.awpo-option', () => this.updatePrice() );

			this.bindEvents();
		}

		bindEvents() {
			$( document ).on( 'submit', 'form.cart', ( e ) => this.validate( e ) );
		}

		updatePrice() {
			let price = this.settings.price;
			const elements = this.element.find( '.awpo-option' );

			price = price.replace( /[^.\d]+/g, '' );
			price = parseFloat( price.replace( this.settings.price_decimal_sep, '.' ) );

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
							const vId = el.value;
							if ( vId && this.valuePrices[ vId ] ) {
								price += this.valuePrices[ vId ];
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
				.toFixed( this.settings.price_num_decimals )
				.replace( '.', this.settings.price_decimal_sep );

			formattedPrice = formattedPrice
				.toString()
				.replace( /\B(?=(\d{3})+(?!\d))/g, this.settings.price_thousand_sep );

			if ( this.settings.price_currency_pos === 'left_space' ) {
				formattedPrice = ` ${ formattedPrice }`;
			} else if ( this.settings.price_currency_pos === 'right_space' ) {
				formattedPrice += ' ';
			}

			if ( this.settings.price_currency_pos === 'left' || this.settings.price_currency_pos === 'left_space' ) {
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

			const requiredText = this.settings.required_text;

			this.element
				.find( '.awpo-required' )
				.toArray()
				.forEach( ( el ) => {
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

	/* global awpo_scripts_settings */
	$( document ).ready( () => {
		const wrapperOptions = $( '#awpo_product_options' );
		const options = wrapperOptions.data( 'product_options' );

		new AwpoProductOptions( awpo_scripts_settings, options );
	} );
}( jQuery ) );
