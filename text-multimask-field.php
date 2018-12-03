<?php
/**
 * Plugin Name: Meta Box Input Mask Custom Field
 * Plugin URI: https://www.cftoolbox.io
 * Description: A javascript Input Mask Field plugin for Meta Box. Allows you to add masked fields like currency-fields.
 * Version: 1.0
 * Author: Badabing Breda
 * Author URI: https://www.badabing.nl
 * License: MIT
 */

// init on ... well .. init..
add_action( 'init' , '_multimask_init' );


/**
 * callback that adds field multimask
 * @return [type] [description]
 */
function _multimask_init() {

    if ( class_exists( 'RWMB_Field' ) ) {

        class RWMB_Multimask_Field extends RWMB_Field {

        	/**
        	 * load this script when field is used
        	 * @return [type] [description]
        	 */
        	public static function admin_enqueue_scripts() {
        		wp_enqueue_script( 'imask-plugin', "https://unpkg.com/imask" );
        	}

            /**
             * Output html for this field
             * @param  [type] $meta  [description]
             * @param  [type] $field [description]
             * @return [type]        [description]
             */
            public static function html( $meta, $field ) {

                $default_options = array(
                    'mask_type'             =>  'currency',
                    'scale'                 =>  '2',
                    'signed'                =>  'false',
                    'padFractionalZeros'    =>  'true',
                    'mask'                  =>  '$num',
                    'thousandsSeparator'    =>  ',',
                    'radix'                 =>  '.',
                    'mapToRadix'            =>  '[\'.\']',
                    'min'                   =>  false,
                    'max'                   =>  false,
                );

                // parse the field settings
                $field = wp_parse_args(
                    $field,
                    $default_options
                );

                // add the actual value-field. This one will be hidden but will hold the actual value
                $return_string = sprintf(
                    '<input type="multimask" name="%s" id="%s" value="%s" style="display:none;">',
                    $field['field_name'],
                    $field['id'],
                    $meta
                );

                // add a masked field. This would return the masked value, which we don't want
                 $return_string .= sprintf(
                    '<input type="multimask" name="%s" id="__%s" value="%s">',
                    $field['field_name'],
                    $field['id'],
                    $meta
                );

                switch ( $field['mask_type'] ) {
                    case "currency":
                    $return_string .= sprintf(
                                                self::get_mask_template( $field['mask_type'] ),
                                                    $field['id'],
                                                    $field['id'],
                                                    $field['mask'],
                                                    $field['thousandsSeparator'],
                                                    $field['scale'],
                                                    $field['signed'],
                                                    $field['padFractionalZeros'],
                                                    $field['radix']?",radix: '{$field['radix']}'":'',
                                                    $field['mapToRadix']?",mapToRadix: {$field['mapToRadix']}":'',
                                                    $field['min']?",min: {$field['min']}":'',
                                                    $field['max']?",max: {$field['max']}":'',
                                                    $field['id'],
                                                    $field['id'],
                                                    $field['id']
                                            );
                    break;
                }

                return $return_string;

            }


            /**
             * Get a certain mask template
             * @param  [type] $mask_type [description]
             * @return [type]            [description]
             */
            public static function get_mask_template( $mask_type ) {

                switch( $mask_type ) {
                    case "currency":

    $mask_template = <<<EOT
        <script type="text/javascript">
            var mask__%s = new IMask(
                document.getElementById( '__%s' ),
                {
                    mask: '\%s',
                    blocks: {
                        num: {mask: Number, thousandsSeparator: '%s', scale: %s, signed: %s, padFractionalZeros: %s%s%s%s%s}
                    }
                }
            );
            /* make sure to write accepted changes to the actual meta-field */
            mask__%s.on("accept", function() { document.getElementById( '%s' ).value = mask__%s.unmaskedValue;});
        </script>
EOT;
                    break;
                }

                return $mask_template;

            }

        }
    }

}
