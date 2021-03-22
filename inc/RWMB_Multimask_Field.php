<?php

class RWMB_Multimask_Field extends \RWMB_Field {


    /**
     * load this script when field is used
     * @return [type] [description]
     */
    public static function admin_enqueue_scripts() {

        wp_enqueue_script( 'imask-plugin', BADABINGMULTIMASK_URL . "js/imask.6.0.7.min.js" , array() , BADABINGMULTIMASK_VERSION , false );

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
            'signed'                =>  false,              // disallow negative
            'padFractionalZeros'    =>  false,               // if true, then pads zeros at end to the length of scale
            'normalizeZeros'        =>  false,               // append or remove zeros at ends
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
        //$field['signed'] = is_bool($field['signed']) ? ( ( $field['signed'] == false ) ? 'false' : 'true' ) : $field['signed'] ;
        
        //$field['padFractionalZeros'] = is_bool($field['padFractionalZeros']) ? ( ( $field['padFractionalZeros'] == false ) ? 'false' : 'true' ) : $field['padFractionalZeros'] ;


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
                                            $field['signed'] === false
                                                ? 'false' 
                                                : 'true',
                                            $field['padFractionalZeros'] === false
                                                ? 'false' 
                                                : 'true',
                                            $field['normalizeZeros'] === false
                                                ? 'false' 
                                                : 'true',
                                            $field['radix'] 
                                                ? ",radix: '{$field['radix']}'"
                                                : '',
                                            $field['mapToRadix'] 
                                                ? ",mapToRadix: {$field['mapToRadix']}"
                                                : '',
                                            $field['min'] 
                                                ? ",min: {$field['min']}" 
                                                : '',
                                            $field['max'] 
                                                ? ",max: {$field['max']}" 
                                                : '',
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
                        num: {mask: Number, thousandsSeparator: '%s', scale: %s, signed: %s, padFractionalZeros: %s, normalizeZeros: %s%s%s%s%s}
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