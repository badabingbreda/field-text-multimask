<?php
/**
 * Meta Box Input Mask Custom Field
 *
 * @package     BadabingMultimask
 * @author      Badabingbreda
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: Meta Box Input Mask Custom Field
 * Plugin URI:  https://www.badabing.nl
 * Description: A Javascript Input Mask Field plugin for Meta Box. Allows you to add masked fields.
 * Version:     1.4.0
 * Author:      Badabingbreda
 * Author URI:  https://www.badabing.nl
 * Text Domain: bada-multimask
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

define( 'BADABINGMULTIMASK_VERSION', '1.4.0' );
define( 'BADABINGMULTIMASK_DIR', plugin_dir_path( __FILE__ ) );
define( 'BADABINGMULTIMASK_FILE', __FILE__ );
define( 'BADABINGMULTIMASK_URL', plugins_url( '/', __FILE__ ) );

require_once( 'inc/RWMB_Multimask_Field.php' );
require_once( 'inc/Controls.php' );

use BadabingMultimask\Controls;

new RWMB_Multimask_Field();
new Controls();