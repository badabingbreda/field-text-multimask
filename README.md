

# 1. Meta Box Multimask Field

## Last updated: March 21st, 2021

Added Controls for Meta Box Builder 4, drag and drop fields.

## About this plugin

Multimask fieldtype is a fieldtype that is based on the imaskjs javascript library. This library allows you to very easily create custom layouts for your fields.
https://unmanner.github.io/imaskjs/ and https://unmanner.github.io/imaskjs/guide.html

This is short docs for multimask fieldtype for Meta Box. More info can be found on libraries page.

## 1.1. Usage:
Download and activate the plugin or copy the plugin-code from 'input-multimask-field.php' and paste to your functions.php.

after activation you will have the 'multimask' field-type at your displosal.

Optional settings for the field are:

                setting                     default-value

                'mask_type'             =>  'currency',       // optional: 'currency' / 'regex' / 'custom'
                'scale'                 =>  2,                // decimal digits, 0 for integers
                'signed'                =>  false,            // disallow negative
                'padFractionalZeros'    =>  true,             // if true, then pads zeros at end to the length of scale
                'normalizeZeros'        =>  false,            // append or remove zeros at ends
                'mask'                  =>  '$num',           // currency mask, ie: '$ num' , '€ num' , '£ num'
                'thousandsSeparator'    =>  ',',              // any single character
                'radix'                 =>  '.',              // fractional delimiter
                'mapToRadix'            =>  '[\'.\']',        // symbols to process as radix
                'min'                   =>  false,            // optional number interval options
                'max'                   =>  false,            // optional number interval options
                'return'                =>  'float',          // optional string to return meta as float, not string
                'placeholder'           =>  '',               // optional placeholder text
                'store'                 =>  'unmaskedValue',  // optional how to store value to the postmeta: 'value' / 'unmaskedValue'
                'custom'                =>  ''                // custom regex or full settings (depending on mask_type)

The returned value for the field is an unstyled value. So, even if you see a currency field-value of '$ 199.00', the stored value is '199'. This is so that you can gracefully fall back to a text or numeric field, and can switch masks without much problems.

## 1.2. Example:

Example:

    add_filter( 'rwmb_meta_boxes', 'agent_meta_box' );
    /**
     * Create a Meta Box, anonymously
     */
    function agent_meta_box( $meta_boxes ) {
        $meta_boxes[] = array(
            'title'  => 'Agent',
            'id'     =>  'agent-meta-box',
            'post_types'    => array(
                'agent',
            ),
            'context'   => 'normal',   // normal / advanced / side / form_top / after_title / after_editor / before_permalink
            'priority'  => 'high',      // high / low
            'fields' => array(
	            // fields go here
                array(  // USD currency format with US number format
                    'name'                  =>  'Dollars',
                    'id'                    =>  'my_number_format',
                    'type'                  =>  'multimask',
                    'scale'                 => 0,
                    'signed'                => true,
                    'padFractionalZeros'    => false,
                    'mask'                  => '$ num'
                ),
                array(  // Euro currency with European number format
                    'name'                  =>  'Euros',
                    'id'                    =>  'my_euro_currency',
                    'type'                  =>  'multimask',
                    'scale'                 => 0,
                    'signed'                => true,
                    'padFractionalZeros'    => false,
                    'mask'                  => '€ num',
                    'min'                   => -1000,
                    'radix'                 => ',',
                    'thousandsSeparator'    => '.',
                    'mapToRadix'            => '[\'.\']'
                ),
                array(  // Dutch postal code
                    'name'                  =>  'Postal Code',
                    'id'                    =>  'my_postal_code',
                    'type'                  =>  'multimask',
                    'mask_type'             =>  'custom',
                    'custom'               =>  "mask: '0### aa', definitions: { '0': /[1-9]/, '#':/[0-9]/, 'a': /[a-zA-Z]/ }",
                    'store'                 => 'value'                  // store to postmeta-table as masked-value
                ),
                array(  // Input restricted to coco coco
                    'name'                  =>  'Enter coco coco',
                    'id'                    =>  'my_continue',
                    'type'                  =>  'multimask',
                    'mask_type'             =>  'custom',
                    'custom'                 =>  "mask: 'coco coco', definitions: { 'c': /[cC]/, 'o': /[oO]/ }",
                    'store'                 => 'value',
                    'desc'                  => 'Enter coco coco'
                ),
                array( // Phone number in format 0-000-000-000
                    'name'        => 'Phone number',
                    'label_description' => '',
                    'id'          => 'my_phone_number',
                    'desc'        => 'Enter Phone number',
                    'type'        => 'multimask',
                    'mask_type'   => 'custom',
                    'custom'      => "mask: '0-000-000-000'",
                    'store'       => 'value',

                    // Placeholder
                    'placeholder' => '1-800-234-567',

                ),
            ),
        );
        return $meta_boxes;
    }


### 1.2.1. changelog:

**1.4** (March 21st, 2021)
Updated for usage with Meta Box Builder 4, added field controls. Cleaned up code by seperating into multiple files.

**1.3** (July 5th, 2019)
Added $(document).ready() for use with Frontend Submissions

**1.2** (December 4th, 2018)

enhancement: added mask_type 'regex' and 'custom'.

**1.1** (December 4th, 2018)

feature: add field-setting return so that meta-value is float instead of string

enhancement: signed and padFractionalZeros setting can be input as both boolean and string

**1.0.1** (December 4th, 2018)

bugfix: Removed redeclaring of field-name so that returned value is unstyled.

**1.0** (December 3rd, 2018)

Initial release.
