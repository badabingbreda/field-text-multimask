

# Meta Box Multimask Field

Multimask fieldtype is a fieldtype that is based on the imaskjs javascript library. This library allows you to very easily create custom layouts for your fields.
https://unmanner.github.io/imaskjs/

This is short docs for multimask fieldtype for Meta Box. More info can be found on libraries page.

## Usage:
Download and activate the plugin or copy the plugin-code from 'input-multimask-field.php' and paste to your functions.php.

after activation you will have the 'multimask' field-type at your displosal.

Optional settings for the field are:

				setting						default-value

                'scale'                 =>  2,              // decimal digits, 0 for integers
                'signed'                =>  'false',        // disallow negative
                'padFractionalZeros'    =>  'true',         // if true, then pads zeros at end to the length of scale
                'mask'                  =>  '$num',         // currency mask, ie: '$ num' , '€ num' , '£ num'
                'thousandsSeparator'    =>  ',',            // any single character
                'radix'                 =>  '.',            // fractional delimiter
                'mapToRadix'            =>  '[\'.\']',      // symbols to process as radix
                'min'                   =>  false,          // optional number interval options
                'max'                   =>  false,          // optional number interval options

The returned value for the field is an unstyled value. So, even if you see a currency field-value of '$ 199.00', the stored value is '199'. This is so that you can gracefully fall back to a text or numeric field, and can switch masks without much problems.

## Example:

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
	            array(
	                'name'                  =>  'Dollars',
	                'id'                    =>  'my_dollar_amount',
	                'type'                  =>  'multimask',
	                'scale'                 => 0,
	                'padFractionalZeros'    => 'false',
	                'mask'                  => '$ num'
	            ),
	            array(
	                'name'                  =>  'Euros',
	                'id'                    =>  'my_euro_amount',
	                'type'                  =>  'multimask',
	                'scale'                 => 0,
	                'padFractionalZeros'    => 'false',
	                'mask'                  => '€ num',
	                'min'                   => -1000,
	                'max'                   => 3000000,
	                'radix'                 => ',',
	                'thousandsSeparator'    => '.',
	                'mapToRadix'            => '[\'.\']'
	            )
            ),
        );
        return $meta_boxes;
    }


**changelog:**
1.0.1 (December 4th, 2018)
bugfix: Removed redeclaring of field-name so that returned value is unstyled.

1.0 (December 3rd, 2018)
Initial release.
