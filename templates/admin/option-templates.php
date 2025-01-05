<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<script id="tmpl-awpo-custom-option-base" type="text/html">
	<div class="fieldset-wrapper">
		<div class="fieldset-wrapper--title">
			<div class="actions">
				<button type="button" title="Удалить опцию" class="button action-delete awpo-delete-option-button">
					<span>Удалить</span>
				</button>
			</div>
		</div>
		<div class="fieldset-wrapper--content" id="awpo_option_{{{data.id}}}_content">
			<fieldset class="fieldset">
				<fieldset class="fieldset-inner" id="awpo_option_{{{data.id}}}">
					<!--<input id="awpo_option_{{{data.id}}}_is_delete" name="awpo_options[{{{data.id}}}][is_delete]" type="hidden" value=""/>-->
					<input id="awpo_option_{{{data.id}}}_id" name="awpo_options[{{{data.id}}}][id]" type="hidden" value="{{{data.id}}}"/>
					<input id="awpo_option_{{{data.id}}}_option_id" name="awpo_options[{{{data.id}}}][option_id]" type="hidden" value="{{{data.option_id}}}"/>
					<input id="awpo_option_{{{data.id}}}_group" name="awpo_options[{{{data.id}}}][group]" type="hidden" value=""/>


					<div class="fieldset-inner--option-type">
						<div class="field field-option-title">
							<label class="label" for="awpo_option_{{{data.id}}}_title">
								Название опции
							</label>
							<div class="control">
								<input type="text" id="awpo_option_{{{data.id}}}_title" name="awpo_options[{{{data.id}}}][title]" value="{{{data.title}}}" autocomplete="off"/>
							</div>
						</div>
						<div class="field field-option-input-type">
							<label class="label" for="awpo_option_{{{data.id}}}_type">
								Тип опции
							</label>
							<div class="control opt-type">
								<select name="awpo_options[{{{data.id}}}][type]" id="awpo_option_{{{data.id}}}_type" class="awpo-option-type-select">
									<option value="">-- Выбрать --</option>
									<optgroup label="Выбор" data-optgroup-name="select">
										<option value="drop_down">Выпадающий список</option>
										<option value="radio">Радиокнопки</option>
										<option value="checkbox">Чекбоксы</option>
										<option value="multiple">Множественный выбор</option>
									</optgroup>
									<optgroup label="Текст" data-optgroup-name="text">
										<option value="field">Поле</option>
										<option value="area">Область</option>
									</optgroup>
								</select>
							</div>
						</div>
						<div class="field field-option-req">
							<label class="label" for="awpo_option_{{{data.id}}}_required">
								Обязательная
							</label>
							<div class="control">
								<input id="awpo_option_{{{data.id}}}_required" name="awpo_options[{{{data.id}}}][required]" type="checkbox" <# if (data.required == 1){ #>
								checked="checked"<# } #> value="1"/>
							</div>
						</div>
						<div class="field field-option-sort-order">
							<label class="label" for="awpo_option_{{{data.id}}}_sort_order">
								Очередность
							</label>
							<div class="control">
								<input id="awpo_option_{{{data.id}}}_sort_order"
										name="awpo_options[{{{data.id}}}][sort_order]"
										type="text"
										value="{{{data.sort_order}}}"
										autocomplete="off"/>
							</div>
						</div>
					</div>


				</fieldset>
			</fieldset>
		</div>
	</div>
</script>
<script id="tmpl-custom-option-select-type" type="text/html">
	<div id="awpo_option_{{{data.id}}}_type_select" class="fieldset">
		<table class="data-table">
			<thead>
				<tr>
					<th>Наименование</th>
					<th class="col-price">Цена</th>
					<th class="ox-col-sku">Очередность</th>
					<th class="col-actions">&nbsp;</th>
				</tr>
			</thead>
			<tbody id="awpo_select_option_type_row_{{{data.id}}}"></tbody>
			<tfoot>
				<tr>
					<td colspan="4">
						<button type="button" class="button awpo-add-option-value-button">Добавить строку</button>
					</td>
				</tr>
			</tfoot>
		</table>
	</div>
</script>
<script id="tmpl-custom-option-select-type-row" type="text/html">
	<tr id="awpo_option_select_{{{data.vid}}}">
		<td class="select-opt-title">
			<input name="awpo_options[{{{data.id}}}][values][{{{data.vid}}}][value_id]" type="hidden" value="{{{data.value_id}}}">
			<!--<input id="awpo_option_select_{{{data.vid}}}_is_delete" name="awpo_options[{{{data.id}}}][values][{{{data.vid}}}][is_delete]" type="hidden" value="">-->
			<input name="awpo_options[{{{data.id}}}][values][{{{data.vid}}}][title]" type="text" value="{{data.title}}" autocomplete="off"/>
		</td>
		<td class="col-price select-opt-price">
			<input name="awpo_options[{{{data.id}}}][values][{{{data.vid}}}][price]" type="number" value="{{data.price}}" autocomplete="off">
		</td>
		<td class="ox-col-sort-order">
			<input name="awpo_options[{{{data.id}}}][values][{{{data.vid}}}][sort_order]" type="number" value="{{data.sort_order}}" autocomplete="off">
		</td>
		<td class="col-actions col-delete">
			<button type="button" class="button awpo-delete-option-value-button" title="Удалить строку"></button>
		</td>
	</tr>
</script>
<script id="tmpl-custom-option-text-type" type="text/html">
	<div id="awpo_option_{{{data.id}}}_type_text" class="fieldset">
		<table class="data-table" cellspacing="0">
			<thead>
				<tr>
					<th class="type-price">Цена</th>
				</tr>
			</thead>
			<tr>
				<td class="opt-price">
					<input name="awpo_options[{{{data.id}}}][price]" type="text" value="{{data.price}}" autocomplete="off">
				</td>
			</tr>
		</table>
	</div>
</script>
