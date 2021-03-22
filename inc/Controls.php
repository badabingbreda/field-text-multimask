<?php

namespace BadabingMultimask;

class Controls {

    public function __construct() {
        add_filter( 'mbb_field_types', __CLASS__ . '::add_field_type' );
    }
    
    /**
     * add_field_type
     *
     * @param  mixed $field_types
     * @return void
     */
    function add_field_type( $field_types ) {
      
      $field_types['multimask'] = [
        'title'    => __( 'Multimask', 'bada-multimask' ),
        'category' => 'advanced',
        'controls' => [
          'name', 'id', 'type', 'label_description', 'desc',
          \MBB\Control::Select( 
                    'mask_type', 
                    [
                        'label'   => __( 'Mask Type', 'bada-multimask' ),
                        'tooltip' => __( 'Select the type of mask for this field', 'bada-multimask' ),

                        'options' => [
                            'currency'   => __( 'Currency', 'bada-multimask' ),
                            'regex'      => __( 'Regex', 'bada-multimask' ),
                            'custom'     => __( 'Custom', 'bada-multimask' ),
                        ],
              ],
                    'currency'
                 ),
                 \MBB\Control::Input(
                  'mask',
                    [
                      'label'   => __( 'Mask', 'bada-multimask' ),
                      'tooltip' => __( 'Enter the needed mask here (see https://imask.js.org/guide.html )', 'bada-multimask' ),
                      // 'type' => 'number',
                      'dependency' => 'mask_type:currency', // 'custom_layout:true',
                    ],
                    '$ num',    // default value
                    'general'    // settings tab
                ),
                 \MBB\Control::Input(
                   'scale',
                     [
                       'label'   => __( 'Scale', 'bada-multimask' ),
                       'tooltip' => __( 'decimal digits, 0 for integers', 'bada-multimask' ),
                       // 'type' => 'number',
                       'dependency' => 'mask_type:currency', // 'custom_layout:true',
                     ],
                     2,    // default value
                     'general'    // settings tab
                 ),
                 \MBB\Control::Checkbox(
                   'signed',
                     [
                       'label'   => __( 'Allow negative values?', 'bada-multimask' ),
                       'tooltip' => __( 'Negative values', 'bada-multimask' ),
                       'dependency' => 'mask_type:currency', // 'custom_layout:true',
                     ],
                     false,    // default value
                     'general'    // settings tab
                 ),
                \MBB\Control::Checkbox(
                  'padFractionalZeros',
                    [
                      'label'   => __( 'Pad with Zeros', 'bada-multimask' ),
                      'tooltip' => __( 'Pad with zeros when entering decimals to complete the scale size.', 'bada-multimask' ),
                      'dependency' => 'mask_type:currency', // 'custom_layout:true',
                    ],
                    true,    // default value
                    'general'    // settings tab
                ),
                \MBB\Control::Checkbox(
                  'normalizeZeros',
                    [
                      'label'   => __( 'Normalize Zeros', 'bada-multimask' ),
                      'tooltip' => __( 'Appends or removes zeros at ends when editing. Example: when entering 20.5000 with this setting enabled, it will be stripped to 20.5', 'bada-multimask' ),
                      'dependency' => 'mask_type:currency', // 'custom_layout:true',
                    ],
                    true,    // default value
                    'general'    // settings tab
                ),
                \MBB\Control::Select(
                  'thousandsSeparator',
                    [
                      'label'   => __( 'Thousands Seperator', 'bada-multimask' ),
                      'tooltip' => __( 'Select the thousand seperator', 'bada-multimask' ),
                      'options' => [
                        ','  => __( ',', 'bada-multimask' ),
                        '.'  => __( '.', 'bada-multimask' ),
                      ],
                      'dependency' => 'mask_type:currency', // 'custom_layout:true',
                    ],
                    ',',    // default value
                    'general'    // settings tab
                ),
                \MBB\Control::Select(
                  'radix',
                    [
                      'label'   => __( 'Radix', 'bada-multimask' ),
                      'tooltip' => __( 'Used for seperating whole numbers and decimals', 'bada-multimask' ),
                      'options' => [
                        '.'  => __( '.', 'bada-multimask' ),
                        ','  => __( ',', 'bada-multimask' ),
                      ],
                      'dependency' => 'mask_type:currency', // 'custom_layout:true',
                    ],
                    '.',    // default value
                    'general'    // settings tab
                ),
                \MBB\Control::Input(
                  'min',
                    [
                      'label'   => __( 'Min', 'bada-multimask' ),
                      'tooltip' => __( 'Do not allow values lower than this', 'bada-multimask' ),
                      // 'type' => 'number',
                      'dependency' => 'mask_type:currency', // 'custom_layout:true',
                    ],
                    null,    // default value
                    'general'    // settings tab
                ),
                \MBB\Control::Input(
                  'max',
                    [
                      'label'   => __( 'Max', 'bada-multimask' ),
                      'tooltip' => __( 'Do not allow values higher than this', 'bada-multimask' ),
                      // 'type' => 'number',
                      'dependency' => 'mask_type:currency', // 'custom_layout:true',
                    ],
                    null,    // default value
                    'general'    // settings tab
                ),
                \MBB\Control::Input(
                  'custom',
                    [
                      'label'   => __( 'Regex', 'bada-multimask' ),
                      'tooltip' => __( 'Regular Expression (see https://imask.js.org/guide.html )', 'bada-multimask' ),
                      // 'type' => 'number',
                      'dependency' => 'mask_type:regex', // 'custom_layout:true',
                    ],
                    '/^[0-9]{0,4}$/',    // default value
                    'general'    // settings tab
                ),
                \MBB\Control::Input(
                  'placeholder',
                    [
                      'label'   => __( 'Placeholder', 'bada-multimask' ),
                      'tooltip' => __( 'Placeholder text when field is empty', 'bada-multimask' ),
                      // 'type' => 'number',
                      'dependency' => '', // 'custom_layout:true',
                    ],
                    'Enter an amount',    // default value
                    'general'    // settings tab
                ),
          'before', 'after', 'class', 'save_field', 'sanitize_callback', 'attributes', 'custom_settings',
        ],
      ];
    
      return $field_types;
    }    


}

