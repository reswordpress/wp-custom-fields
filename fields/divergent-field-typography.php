<?php
 /** 
  * Displays a typography input field
  */
namespace Classes\Divergent\Fields;
use Classes\Divergent\Divergent as Divergent;

// Bail if accessed directly
if ( ! defined( 'ABSPATH' ) ) 
    die;

class Divergent_Field_Typography implements Divergent_Field {
    
    public static function render($field = array()) {
        
        $type = isset($field['subtype']) ? $field['subtype'] : 'text';
        
        $fonts = Divergent::$fonts;
        
        // Display the fonts
        $output = '<div class="divergent-typography-fonts divergent-field-left">';
        
        // Select fount display
        if( isset($field['values']['font']) ) {
        
            foreach( $fonts as $fontspace => $types) {
                
                foreach( $types as $key => $font ) {
                    
                    if($font['family'] != $field['values']['font'])
                        continue;
                    
                    $font_display = isset($font['example']) ? $font['example'] : DIVERGENT_ASSETS_URL . 'img/' . $key . '.png';
                    $weights = isset($font['weights']) ? ' data-weights="' . implode(',', $font['weights']) . '"' : '';
                    $styles = isset($font['styles']) ? ' data-styles="' . implode(',', $font['styles']) . '"' : ''; 

                    $output .= '<p class="divergent-typography-title">' . __('Selected Font:', 'divergent') . '</p>';    
                    $output .= '<div class="divergent-typography-set">';                    
                    $output .= '    <div class="divergent-typography-font selected"' . $weights . $styles . '>';           
                    $output .= '        <img src="' . $font_display . '" />';              
                    $output .= '    </div>';                   
                    $output .= '</div>'; 
                    
                }
                
            }
            
        }
        
        // Loop through the sets
        foreach( $fonts as $fontspace => $types ) {
            
            $output .= '    <p class="divergent-typography-title">' . ucfirst($fontspace) . ':</p>';    
            $output .= '    <ul class="divergent-typography-set">';
            
            foreach( $types as $key => $font ) {
                            
                $font_display   = isset($font['example']) ? $font['example'] : DIVERGENT_ASSETS_URL . 'img/' . $key . '.png';
                $weights        = isset($font['weights']) ? ' data-weights="' . implode(',', $font['weights']) . '"' : '';
                $styles         = isset($font['styles']) ? ' data-styles="' . implode(',', $font['styles']) . '"' : '';
                $selected       = isset($field['values']['font']) && $font['family'] == $field['values']['font'] ? ' checked="checked" ' : '';
                
                $output .= '    <li class="divergent-typography-font"' . $weights . $styles . '>';
                $output .= '        <input type="radio" name="' . $field['name'] . '[font]" id="' . $field['id'] . '-' . $key . '" value="' . $font['family'] . '"' . $selected . '/>';               
                $output .= '        <label for="' . $field['id'] . '-' . $key . '">';
                $output .= '            <img src="' . $font_display . '" />';
                $output .= '        </label>';                
                $output .= '    </li>';
            }
            
            $output .= '    </ul>';
        }
             
        $output .= '</div><!-- .divergent-typography-fonts -->';
        
        $output .= '<div class="divergent-typography-properties divergent-field-left">';
        
        // Text Dimensions
        $dimensions = array(
            'size'          => __('Font-Size', 'divergent'), 
            'line_spacing'  => __('Line-Height', 'divergent'), 
        );
        
        foreach($dimensions as $key => $label) {
         
            $output .= Divergent_Field_Dimension::render( array(
                'icon'          => 'format_' . $key,
                'id'            => $field['id'] . '_' . $key,
                'name'          => $field['name'] . '[' . $key . ']',
                'placeholder'   => $label,
                'values'        => isset($field['values'][$key]) ? $field['values'][$key] : ''
            ) );
            
        } 
        
        // Font-weight
        $output .= '<i class="material-icons">format_bold</i> ';
        $output .= Divergent_Field_Select::render( array(
            'id'            => $field['id'] . '_font_weight',
            'name'          => $field['name'] . '[font_weight]', 
            'options'       => array( 
                100 => __('100 (Thin)', 'divergent'), 
                200 => __('200 (Extra Light)', 'divergent'), 
                300 => __('300 (Light)', 'divergent'), 
                400 => __('400 (Normal)', 'divergent'), 
                500 => __('500 (Medium)', 'divergent'), 
                600 => __('600 (Semi Bold)', 'divergent'), 
                700 => __('700 (Bold)', 'divergent'), 
                800 => __('800 (Extra Bold)', 'divergent'), 
                900 => __('900 (Black)', 'divergent') 
            ),
            'placeholder'   => __('Font-Weight', 'divergent'),
            'values'        => isset($field['values']['font_weight']) ? $field['values']['font_weight'] : 400
        ) );
        
        // Display font characteristics
        $styles = array(
            'styles'     => array(
                'italic'        => 'format_italic', 
                'line-through'  => 'format_strikethrough', 
                'underline'     => 'format_underlined', 
                'uppercase'     => 'title'
            ),
            'text-align' => array('left' => 'format_align_left', 'right' => 'format_align_right', 'center' => 'format_align_center', 'justify' => 'format_align_justify'),
        );        
        
        // Style Buttons
        foreach($styles as $key => $style) {
            $output .= '    <ul class="divergent-typography-' . $key . ' divergent-icon-list">'; 
            
            foreach($style as $value => $icon) {
                $checked = is_array($field['values']) && in_array($value, $field['values']) ? ' checked="checked" ' : '';
                
                $name = $key == 'styles' ? $field['name'] . '[' . $value . ']' : $field['name'] . '[' . $key . ']';
                $type = $key == 'styles' ? 'checkbox' : 'radio';
                
                $output .= '    <li class="divergent-icons-icon">';
                $output .= '        <input type="' . $type . '" name="' . $name . '" id="' . $field['id'] . '-' . $value . '" value="' . $value . '"' . $checked . '/>';
                $output .= '        <label for="' . $field['id'] . '-' . $value . '">';
                $output .= '            <i class="material-icons">' . $icon . '</i>';
                $output .= '        </label>';
                $output .= '    </li>';                    
            }
            
            $output .= '    </ul>';
        }
        
        $output .= '</div><!-- .divergent-typography-styles -->';
        
        return $output;    
    }
    
    public static function configurations() {
        $configurations = array(
            'type' => 'typography'
        );
            
        return $configurations;
    }
    
}