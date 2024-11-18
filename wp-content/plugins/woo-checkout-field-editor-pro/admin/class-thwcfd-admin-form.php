<?php
/**
 * The admin settings page functionality of the plugin.
 *
 * @link       https://themehigh.com
 *
 * @package    woo-checkout-field-editor-pro
 * @subpackage woo-checkout-field-editor-pro/admin
 */

if(!defined('WPINC')){ die; }

if(!class_exists('THWCFD_Admin_Form')):

abstract class THWCFD_Admin_Form {
	public $cell_props = array();
	public $cell_props_TA = array();
	public $cell_props_CP = array();
	public $cell_props_CB = array();

	public function __construct() {
		$this->init_constants();
	}

	private function init_constants(){
		$this->cell_props = array(
			'label_cell_props' => 'class="label"',
			'input_cell_props' => 'class="field"',
			'input_width' => '260px',
		);
		$this->cell_props_TA = array(
			'label_cell_props' => 'class="label"',
			'input_cell_props' => 'class="field"',
			'input_width' => '260px',
			'rows' => 10,
			'cols' => 29,
		);
		$this->cell_props_CP = array(
			'label_cell_props' => 'class="label"',
			'input_cell_props' => 'class="field"',
			'input_width' => '223px',
		);

		$this->cell_props_CB = array(
			'label_props' => 'style="margin-right: 40px;"',
		);
		$this->cell_props_CBS = array(
			'label_props' => 'style="margin-right: 15px;"',
		);
		$this->cell_props_CBL = array(
			'label_props' => 'style="margin-right: 52px;"',
		);

		$this->field_props = $this->get_field_form_props();
		$this->field_props_display = $this->get_field_form_props_display();
	}

	public function render_form_field_element($field, $args = array(), $render_cell = true){

		if($field && is_array($field)){
			$defaults = array(
			    'label_cell_props' => 'class="label"',
				'input_cell_props' => 'class="field"',
				'label_cell_colspan' => '',
				'input_cell_colspan' => '',
			);
			$args = wp_parse_args( $args, $defaults );

			$ftype     = isset($field['type']) ? $field['type'] : 'text';
			$flabel    = isset($field['label']) && !empty($field['label']) ? $field['label'] : '';
			$sub_label = isset($field['sub_label']) && !empty($field['sub_label']) ? $field['sub_label'] : '';
			$tooltip   = isset($field['hint_text']) && !empty($field['hint_text']) ? $field['hint_text'] : '';

			$field_html = '';

			if($ftype == 'text'){
				$field_html = $this->render_form_field_element_inputtext($field, $args);

			}else if($ftype == 'textarea'){
				$field_html = $this->render_form_field_element_textarea($field, $args);

			}else if($ftype == 'select'){
				$field_html = $this->render_form_field_element_select($field, $args);

			}else if($ftype == 'multiselect'){
				$field_html = $this->render_form_field_element_multiselect($field, $args);

			}else if($ftype == 'colorpicker'){
				$field_html = $this->render_form_field_element_colorpicker($field, $args);

			}else if($ftype == 'checkbox'){
				$field_html = $this->render_form_field_element_checkbox($field, $args, $render_cell);
				$flabel 	= '&nbsp;';

			}else if($ftype == 'number'){
				$field_html = $this->render_form_field_element_number($field, $args);
			}

			if($render_cell){
				$required_html = isset($field['required']) && $field['required'] ? '<abbr class="required" title="required">*</abbr>' : '';

				$label_cell_props = !empty($args['label_cell_props']) ? $args['label_cell_props'] : '';
				$input_cell_props = !empty($args['input_cell_props']) ? $args['input_cell_props'] : '';
				?>
				<td <?php echo wp_kses($label_cell_props, array('class' => true)) ?> >
					<?php echo esc_html($flabel); echo wp_kses($required_html, array('abbr' => array('class' => true, 'title' => true)) );
					if($sub_label){
						?>
						<br/><span class="thpladmin-subtitle"><?php echo esc_html($sub_label); ?></span>
						<?php
					}
					?>
				</td>
				<?php $this->render_form_fragment_tooltip($tooltip); ?>
				<td <?php echo wp_kses($input_cell_props, array('class' => true)) ?> ><?php echo wp_kses($field_html, THWCFD_Utils::get_allowed_html()); ?></td>
				<?php
			}else{
				echo wp_kses($field_html, THWCFD_Utils::get_allowed_html());
			}
		}
	}

	private function prepare_form_field_props($field, $args = array()){
		$field_props = '';

		$defaults = array(
		    'input_width' => '',
			'input_name_prefix' => 'i_',
			'input_name_suffix' => '',
		);
		$args = wp_parse_args( $args, $defaults );

		$ftype = isset($field['type']) ? $field['type'] : 'text';

		$input_class = '';
		if($ftype == 'text'){
			$input_class = 'thwcfd-inputtext';
		}else if($ftype == 'number'){
			$input_class = 'thwcfd-inputtext';
		}else if($ftype == 'select'){
			$input_class = 'thwcfd-select';
		}else if($ftype == 'multiselect' || $ftype == 'multiselect_grouped'){
			$input_class = 'thwcfd-select thwcfd-enhanced-multi-select';
		}else if($ftype == 'colorpicker'){
			$input_class = 'thwcfd-color thpladmin-colorpick';
		}

		if($ftype == 'multiselect' || $ftype == 'multiselect_grouped'){
			$args['input_name_suffix'] = $args['input_name_suffix'].'[]';
		}

		$fname  = $args['input_name_prefix'].$field['name'].$args['input_name_suffix'];
		$fvalue = isset($field['value']) ? esc_html($field['value']) : '';

		$input_width  = $args['input_width'] ? 'width:'.$args['input_width'].';' : '';
		$field_props  = 'name="'. $fname .'" style="'. $input_width .'"';
		$field_props .= !empty($input_class) ? ' class="'. $input_class .'"' : '';
		$field_props .= $ftype == 'textarea' ? '' : ' value="'. $fvalue .'"';
		$field_props .= $ftype == 'multiselect_grouped' ? ' data-value="'. $fvalue .'"' : '';
		$field_props .= ( isset($field['placeholder']) && !empty($field['placeholder']) ) ? ' placeholder="'.$field['placeholder'].'"' : '';
		$field_props .= ( isset($field['onchange']) && !empty($field['onchange']) ) ? ' onchange="'.$field['onchange'].'"' : '';

		if( $ftype == 'number' ){
			$min = isset( $field['min'] ) ? $field['min'] : '';
			$max = isset( $field['max'] ) ? $field['max'] : '';
			$field_props .= ' min="'.$min.'" max="'.$max.'"';
		}

		return $field_props;
	}

	private function render_form_field_element_inputtext($field, $atts = array()){
		$field_html = '';
		if($field && is_array($field)){
			$field_props = $this->prepare_form_field_props($field, $atts);
			$field_html = '<input type="text" '. wp_kses_post($field_props) .' />';
		}
		return $field_html;
	}

	private function render_form_field_element_textarea($field, $args = array()){
		$field_html = '';
		if($field && is_array($field)){
			$args = wp_parse_args( $args, array(
			    'rows' => '5',
				'cols' => '29',
			));

			$fvalue = isset($field['value']) ? $field['value'] : '';
			$field_props = $this->prepare_form_field_props($field, $args);
			$field_html = '<textarea '. wp_kses_post($field_props) .' rows="'.esc_attr($args['rows']).'" cols="'.esc_attr($args['cols']).'" >'.esc_html($fvalue).'</textarea>';
		}
		return $field_html;
	}

	private function render_form_field_element_select($field, $atts = array()){
		$field_html = '';
		if($field && is_array($field)){
			$fvalue = isset($field['value']) ? $field['value'] : '';
			$field_props = $this->prepare_form_field_props($field, $atts);

			$field_html = '<select '. wp_kses_post($field_props) .' >';
			foreach($field['options'] as $value => $label){
				$selected = $value === $fvalue ? 'selected' : '';
				$field_html .= '<option value="'. esc_attr(trim($value)) .'" '.esc_attr($selected).'>'. esc_html__($label, 'woo-checkout-field-editor-pro') .'</option>';
			}
			$field_html .= '</select>';
		}
		return $field_html;
	}

	private function render_form_field_element_multiselect($field, $atts = array()){
		$field_html = '';
		if($field && is_array($field)){
			$field_props = $this->prepare_form_field_props($field, $atts);

			$field_html = '<select multiple="multiple" '. wp_kses_post($field_props) .'>';
			foreach($field['options'] as $value => $label){
				//$selected = $value === $fvalue ? 'selected' : '';
				$field_html .= '<option value="'. esc_attr(trim($value)) .'" >'. esc_html__($label, 'woo-checkout-field-editor-pro') .'</option>';
			}
			$field_html .= '</select>';
		}
		return $field_html;
	}

	private function render_form_field_element_multiselect_grouped($field, $atts = array()){
		$field_html = '';
		if($field && is_array($field)){
			$field_props = $this->prepare_form_field_props($field, $atts);

			$field_html = '<select multiple="multiple" '. wp_kses_post($field_props) .'>';
			foreach($field['options'] as $group_label => $fields){
				$field_html .= '<optgroup label="'. esc_attr($group_label) .'">';

				foreach($fields as $value => $label){
					$value = trim($value);
					if(isset($field['glue']) && !empty($field['glue'])){
						$value = $value.$field['glue'].trim($label);
					}

					$field_html .= '<option value="'. esc_attr($value) .'">'. esc_html__($label, 'woo-checkout-field-editor-pro') .'</option>';
				}

				$field_html .= '</optgroup>';
			}
			$field_html .= '</select>';
		}
		return $field_html;
	}

	private function render_form_field_element_radio($field, $atts = array()){
		$field_html = '';
		/*if($field && is_array($field)){
			$field_props = $this->prepare_form_field_props($field, $atts);

			$field_html = '<select '. $field_props .' >';
			foreach($field['options'] as $value => $label){
				$selected = $value === $fvalue ? 'selected' : '';
				$field_html .= '<option value="'. trim($value) .'" '.$selected.'>'. __($label, 'woo-checkout-field-editor-pro') .'</option>';
			}
			$field_html .= '</select>';
		}*/
		return $field_html;
	}

	private function render_form_field_element_checkbox($field, $atts = array(), $render_cell = true){

		$field_html = '';
		if($field && is_array($field)){
			$args = shortcode_atts( array(
				'label_props' => '',
				'cell_props'  => '',
				'input_props' => '',
				'id_prefix'   => 'a_f',
				'render_input_cell' => false,
			), $atts );

			$fid 	= $args['id_prefix']. $field['name'];
			$flabel = isset($field['label']) && !empty($field['label']) ? __($field['label'], 'woo-checkout-field-editor-pro') : '';

			$field_props  = $this->prepare_form_field_props($field, $atts);
			$field_props .= isset($field['checked']) && $field['checked'] === 1 ? ' checked' : '';
			$field_props .= $args['input_props'];

			$field_html  = '<input type="checkbox" id="'. esc_attr($fid).'" '. wp_kses_post($field_props) .' />';
			$field_html .= '<label for="'. esc_attr($fid) .'" '. wp_kses_post($args['label_props']) .' > '. esc_html($flabel) .'</label>';
		}
		if(!$render_cell && $args['render_input_cell']){
			return '<td '.wp_kses_post($args['cell_props']) .' >'. wp_kses_post($field_html) .'</td>';
		}else{
			
			return wp_kses($field_html, THWCFD_Utils::get_allowed_html());
		}
	}

	private function render_form_field_element_colorpicker($field, $atts = array()){
		$field_html = '';
		if($field && is_array($field)){
			$field_props = $this->prepare_form_field_props($field, $atts);

			$field_html  = '<span class="thpladmin-colorpickpreview '.esc_attr($field['name']).'_preview" style=""></span>';
            $field_html .= '<input type="text" '. wp_kses_post($field_props) .' >';
		}
		return $field_html;
	}

	private function render_form_field_element_number($field, $atts = array() ){
		$field_html = '';
		if($field && is_array($field)){
			$field_props = $this->prepare_form_field_props($field, $atts);
			$field_html = '<input type="number" '. wp_kses_post($field_props) .' />';
		}
		return $field_html;
	}

	public function render_form_fragment_tooltip($tooltip = false){
		if($tooltip){
			?>
			<td class="tip" style="width: 26px; padding:0px;">
			<a href="javascript:void(0)" title="<?php echo esc_attr($tooltip); ?>" class="thwcfd_tooltip">
    			<img src="<?php echo esc_url(THWCFD_ASSETS_URL_ADMIN . '/images/help.png'); ?>" title=""/>
			</a>
			</td>
			<?php
		}else{
			?>
			<td style="width: 26px; padding:0px;"></td>
			<?php
		}
	}

	public function render_form_fragment_h_spacing($padding = 5){
		$style = $padding ? 'padding-top:' . esc_attr($padding) . 'px;' : '';
		?>
		<tr><td colspan="3" style="<?php echo esc_attr($style); ?>"></td></tr>
		<?php
	}

	public function render_form_fragment_h_separator($atts = array()){
		$args = shortcode_atts( array(
			'colspan' 	   => 6,
			'padding-top'  => '5px',
			'border-style' => 'dashed',
    		'border-width' => '1px',
			'border-color' => '#e6e6e6',
			'content'	   => '',
		), $atts );

		$style  = $args['padding-top'] ? 'padding-top:'.esc_attr($args['padding-top']).';' : '';
		$style .= $args['border-style'] ? ' border-bottom:'.esc_attr($args['border-width']).' '.esc_attr($args['border-style']).' '.esc_attr($args['border-color']).';' : '';

		?>
        <tr><td colspan="<?php echo esc_attr($args['colspan']); ?>" style="<?php echo esc_attr($style); ?>"><?php echo esc_attr($args['content']); ?></td></tr>
        <?php
	}

	public function render_form_field_blank($colspan = 3){
		?>
        <td colspan="<?php echo esc_attr($colspan); ?>">&nbsp;</td>
        <?php
	}

	public function render_form_section_separator($props, $atts=array()){
		?>
		<tr valign="top"><td colspan="<?php echo esc_attr($props['colspan']); ?>" style="height:10px;"></td></tr>
		<tr valign="top"><td colspan="<?php echo esc_attr($props['colspan']); ?>" class="thpladmin-form-section-title" ><?php echo esc_html($props['title']); ?></td></tr>
		<tr valign="top"><td colspan="<?php echo esc_attr($props['colspan']); ?>" style="height:0px;"></td></tr>
		<?php
	}

	/*----- Tab Title -----*/
	public function render_form_tab_main_title($title){
		?>
		<main-title classname="main-title">
			<button class="device-mobile btn--back Button">
				<i class="button-icon button-icon-before i-arrow-back"></i>
			</button>
			<span class="device-mobile main-title-icon text-primary"><i class="i-check drishy"></i><?php echo esc_html($title); ?></span>
			<span class="device-desktop"><?php echo esc_html($title); ?></span>
		</main-title>
		<?php
	}

	/*----- Form Element Row -----*/
	public function render_form_elm_row($field, $args=array()){
		$row_class = $this->prepare_settings_row_class( $field );
		?>
		<tr class="<?php echo esc_attr( $row_class ); ?>">
			<?php $this->render_form_field_element($field, $this->cell_props); ?>
		</tr>
		<?php
	}

	public function render_form_elm_row_ta($field, $args=array()){
		$row_class = $this->prepare_settings_row_class( $field );
		?>
		<tr class="<?php echo esc_attr( $row_class ); ?>">
			<?php $this->render_form_field_element($field, $this->cell_props_TA); ?>
		</tr>
		<?php
	}

	public function render_form_elm_row_cb($field, $args=array()){
		$row_class = $this->prepare_settings_row_class( $field );
		?>
		<tr class="<?php echo esc_attr( $row_class ); ?>">
			<td colspan="2"></td>
			<td class="field">
	    		<?php $this->render_form_field_element($field, $this->cell_props_CB, false); ?>
	    	</td>
	    </tr>
		<?php
	}

	public function render_form_elm_row_cp($field, $args=array()){
		?>
		<tr>
	    	<?php $this->render_form_field_element($field, $this->cell_props_CP); ?>
	    </tr>
		<?php
	}

	public function prepare_settings_row_class( $field ){
		$name = isset($field['name']) ? $field['name'] : '';
		return 'form_field_'.$name;
	}
}

endif;
