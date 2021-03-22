<?php

namespace BadabingMultimask;

class RegisterField {

public function __construct() {
    add_action( 'init' , __CLASS__ . '::init' );
}

/**
 * init
 *
 * @return void
 */
public static function init() {

    if ( !class_exists( 'RWMB_Field' ) ) return;

    /* only pass in 2 parameters, that's all we need */
    add_filter( 'rwmb_get_value' , __CLASS__ . '::multimask_normalize_juggler' , 100, 2 );

}

/**
 * function to return the value as float, not string
 * @param  [type] $value [description]
 * @param  [type] $field [description]
 * @return [type]        [description]
 */
public static function multimask_normalize_juggler( $value , $field  ) {
    if ($field['type'] == 'multimask' ) {
        if ( isset( $field['return'] ) && $field['return'] == 'float' ) return (float)$value;
    }
    // if not returned earlier return as is
    return $value;
}     


}