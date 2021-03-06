<?php
/**
 * Plugin Name: Meta Box Input Mask Custom Field
 * Plugin URI: https://www.cftoolbox.io
 * Description: A javascript Input Mask Field plugin for Meta Box. Allows you to add masked fields like currency-fields.
 * Version: 1.3
 * Author: Badabing Breda
 * Author URI: https://www.badabing.nl
 * License: MIT
 */

// init on ... well .. init..
add_action( 'init' , 'badabing_multimask_init' );

/**
 * callback that adds field multimask
 * @return [type] [description]
 */
function badabing_multimask_init() {

    if ( class_exists( 'RWMB_Field' ) ) {

        /* only pass in 2 parameters, that's all we need */
        add_filter( 'rwmb_get_value' , 'multimask_normalize_juggler' , 100, 2 );

        /**
         * function to return the value as float, not string
         * @param  [type] $value [description]
         * @param  [type] $field [description]
         * @return [type]        [description]
         */
        function multimask_normalize_juggler( $value , $field  ) {
            if ($field['type'] == 'multimask' ) {
                if ( isset( $field['return'] ) && $field['return'] == 'float' ) return (float)$value;
            }
            // if not returned earlier return as is
            return $value;
        }

        class RWMB_Multimask_Field extends RWMB_Field {

        	/**
        	 * load this script when field is used
        	 * @return [type] [description]
        	 */
        	public static function admin_enqueue_scripts() {

                wp_enqueue_script( 'imask-plugin', plugins_url( '/', __FILE__ ) . "js/imask.4.1.5.min.js" , array() , '1.3' , false );

                /* or, enqueue file below instead to get latest version of imask */
                // wp_enqueue_script( 'imask-plugin', "https://unpkg.com/imask" );
        	}

            /**
             * Output html for this field
             * @param  [type] $meta  [description]
             * @param  [type] $field [description]
             * @return [type]        [description]
             */
            public static function html( $meta, $field ) {

                $default_options = array(
                    'mask_type'             =>  'currency',         // 'currency' / 'regex' / 'custom'
                    'scale'                 =>  2,                  // decimal digits, 0 for integers
                    'signed'                =>  'false',            // disallow negative
                    'padFractionalZeros'    =>  'true',             // if true, then pads zeros at end to the length of scale
                    'mask'                  =>  '$num',             // currency mask, ie: '$ num' , '€ num' , '£ num'
                    'thousandsSeparator'    =>  ',',                // any single character
                    'radix'                 =>  '.',                // fractional delimiter
                    'mapToRadix'            =>  '[\'.\']',          // symbols to process as radix
                    'min'                   =>  false,              // optional number interval options
                    'max'                   =>  false,              // optional number interval options
                    'return'                =>  'string',           // how to return value from meta/value: 'string' , 'float'
                    'store'                 =>  'unmaskedValue',    // how to store value to the meta: 'value' / 'unmaskedValue'
                    'placeholder'           =>  '',
                    'custom'                =>  (isset($field['mask_type']) && $field['mask_type'] == 'regex' )?"/^[0-9]\d{0,4}$/":"",  // full custom settings or regex
                );

                // parse the field settings
                $field = wp_parse_args(
                    $field,
                    $default_options
                );

                // make sure signed and padFractionalZeros can be passed in as true/false ( boolean ) or 'true'/'false' (string)
                $field['signed'] = is_bool($field['signed']) ? ( ( $field['signed'] == false ) ? 'false' : 'true' ) : $field['signed'] ;
                $field['padFractionalZeros'] = is_bool($field['padFractionalZeros']) ? ( ( $field['padFractionalZeros'] == false ) ? 'false' : 'true' ) : $field['padFractionalZeros'] ;


                // add a hidden field-value. This is the stored and returned value
                $return_string = sprintf(
                    '<input type="multimask" name="%s" id="%s" value="%s" style="display:none;">',
                    $field['field_name'],
                    $field['id'],
                    $meta
                );

                // add a masked field just for show. We want an unmasked field-value as a return value
                 $return_string .= sprintf(
                    '<input type="multimask" placeholder="%s" name="__%s" id="__%s" value="%s">',
                    $field['placeholder'],
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
                                                    $field['id'],
                                                    $field['store']
                                            );
                    break;
                    case "regex":
                    $return_string .= sprintf(
                                                self::get_mask_template( $field['mask_type'] ),
                                                    $field['id'],
                                                    $field['id'],
                                                    $field['custom'],
                                                    $field['id'],
                                                    $field['id'],
                                                    $field['id'],
                                                    $field['store']
                                            );
                    break;
                    default:
                    case "custom":
                    $return_string .= sprintf(
                                                self::get_mask_template( $field['mask_type'] ),
                                                    $field['id'],
                                                    $field['id'],
                                                    $field['custom'],
                                                    $field['id'],
                                                    $field['id'],
                                                    $field['id'],
                                                    $field['store']
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
            (function($) {
                $(document).ready( function() {
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
                    mask__%s.on("accept", function() { document.getElementById( '%s' ).value = mask__%s.%s;});
                });
            })(jQuery);
        </script>
EOT;
                    break;
                    case "regex":
    $mask_template = <<<EOT
        <script type="text/javascript">
            (function($) {
                $(document).ready( function() {
                    var mask__%s = new IMask(
                        document.getElementById( '__%s' ),
                        {
                            mask: %s,
                        }
                    );
                    /* make sure to write accepted changes to the actual meta-field */
                    mask__%s.on("accept", function() { document.getElementById( '%s' ).value = mask__%s.%s;});
                });
            })(jQuery);
        </script>
EOT;
                    break;
                    default:
                    case "custom":
    $mask_template = <<<EOT
        <script type="text/javascript">
            (function($) {
                $(document).ready( function() {
                    var mask__%s = new IMask(
                        document.getElementById( '__%s' ),
                        {
                            %s
                        }
                    );
                    /* make sure to write accepted changes to the actual meta-field */
                    mask__%s.on("accept", function() { document.getElementById( '%s' ).value = mask__%s.%s;});
                });
            })(jQuery);
        </script>
EOT;
                    break;
                }

                return $mask_template;

            }

        }
    }

}
