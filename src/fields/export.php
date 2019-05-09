<?php 
/**
 * Displays an importer and exporter of saved option data 
 */
namespace MakeitWorkPress\WP_Custom_Fields\Fields;
use MakeitWorkPress\WP_Custom_Fields\Field as Field;

// Bail if accessed directly
if ( ! defined('ABSPATH') ) {
    die;
}

class Export implements Field {

    /**
     * Prepares the variables and renders the field
     * 
     * @param   array $field The array with field attributes data-alpha
     * @return  void
     */      
    public static function render( $field = [] ) {
        
        // Check before proceeding
        if( ! isset($field['key']) || ! isset($field['context']) || ! is_user_logged_in() ) {
            return;
        }

        $configurations = self::configurations();
        $button = isset( $field['labels']['button'] ) ? esc_html($field['labels']['button']) : $configurations['labels']['button'];
        $import = isset( $field['labels']['import'] ) ? esc_html($field['labels']['import']) : $configurations['labels']['import'];
        $id     = esc_attr($field['id']);   

        switch( $field['context'] ) {
            case 'post':

                if( ! current_user_can('edit_posts') || ! current_user_can('edit_pages') ) {
                    return;
                }

                global $post;
                $options = get_post_meta( $post->ID, $field['option_id'], true );
                break;
            case 'user':

                if( ! current_user_can('edit_users') ) {
                    return;
                }

                $user = $pagenow == 'profile.php' ? get_current_user_id() : $_GET['user_id']; 
                $options = get_term_meta( intval($user), $field['option_id'], true );
                
                break;
            case 'term':

                if( ! current_user_can('edit_posts') || ! current_user_can('edit_pages') ) {
                    return;
                }            

                $options = get_term_meta( intval($_GET['tag_ID']), $field['option_id'], true );
                
                break;
            default:

                if( ! current_user_can('manage_options') ) {
                    return;
                } 

                $options = get_option( $field['option_id'] );

        } ?>

            <div class="wp-custom-fields-export">   
                <label for="<?php echo $id; ?>-import"><?php echo $import; ?></label>';        
                <textarea id="<?php echo $id; ?>-import" name="import_value"><?php echo base64_encode( serialize($options) ); ?></textarea>';
                <input id="<?php echo $id; ?>-import" name="import_submit" class="button wp-custom-fields-import-settings" type="submit" value="<?php echo $button; ?>" /> 
            </div>
        
        <?php

    }
    
    /**
     * Returns the global configurations for this field
     *
     * @return array $configurations The configurations
     */     
    public static function configurations() {

        $configurations = [
            'type'      => 'export',
            'defaults'  => '',
            'labels'    => [
                'button' => __('Import', 'wp-custom-fields'),
                'import' => __('The Current Settings. Replace these to import new settings.', 'wp-custom-fields')
            ]
        ];
            
        return apply_filters( 'wp_custom_fields_export_config', $configurations );
        
    }
    
}