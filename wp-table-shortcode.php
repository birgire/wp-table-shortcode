<?php
/**
 * Plugin Name: WP Table Shortcode
 * Author: birgire
 * Author URI: https://github.com/birgire/
 * Version: 0.3
 * Text Domain: wp_table_shortcode
 * Description: This plugin adds the [tafla] shortcode for Multi-Site WordPress that help you to display HTML tables.
 * License: GPL2
 */
/* 
 Copyright 2013 birgire 

 This program is free software; you can redistribute it and/or modify 
 it under the terms of the GNU General Public License, version 2, as 
 published by the Free Software Foundation. 
 
 This program is distributed in the hope that it will be useful, 
 but WITHOUT ANY WARRANTY; without even the implied warranty of 
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the 
 GNU General Public License for more details. 
 
 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software 
 Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA 
 */

/**
 * No direct access
 */
defined( 'ABSPATH' ) or die( 'Nothing here!' );

/**
 * Init the WP_Table_Shortsodes class
 */
if( ! class_exists( 'WP_Table_Shortsodes' ) ):

	add_action( 'plugins_loaded', array( 'WP_Table_Shortsodes', 'get_object' ) );

	/**
	* WP_Table_Shortsodes Class
	* 
	* @author birgire
	*
	*/
	class WP_Table_Shortsodes {
		
		public $delimiter		= '|';
		private $plugin_domain 	= 'wp_table_shortsodes';
		static private $obj 	= NULL;
	
		/**
		 * The constructor
		 * 
		 * @access  public
		 * @since   0.1
		 * @uses   add_shortcode
		 * @return  void		
 		 */

		public function __construct() 
		{
			// register the shortcode
			add_shortcode( 'tafla', array( $this, 'tafla_callback' ) );									
		}	
	
		
		/**
		 * Instantiate the class.
		 * 
		 * @access  public
		 * @since   0.1
		 * @return  object $obj
		 */

		 public function get_object () 
		 {	
			if ( NULL === self :: $obj ) {
				self :: $obj = new self;
			}
			
			return self :: $obj;
		}

		
		/**
		 * tafla shortcode callback 
		 *
		 * @access  public
		 * @since   0.1
		 * @param array $atts
		 * @param string $content
		 * @return string $html
		 */

		 public function tafla_callback( $atts, $content ) 
		 {	
			$atts = shortcode_atts( array(
						'class'		=> 'tafla',
						'width' 	=> '100%',
						'style'		=> '',
						'head'		=> '0',
					), $atts, $this->plugin_domain );
		
			// escape user input
			$content 		= esc_textarea( trim( strip_tags( $content ) ) );
			$atts['class'] 	= esc_attr( $atts['class'] );
			$atts['width'] 	= esc_attr( $atts['width'] );
			$atts['style'] 	= esc_attr( $atts['style'] );
		
			//
			// Generate the output
			//
						
			// Open table
			$html = sprintf( '<table class="%s" width="%s" style="%s">', 
						$atts['class'], 
						$atts['width'], 
						$atts['style']
					);

			// row offset - 1 if no header is displayed, otherwise it's equal to 2
			$table_body_offset = 0;
					
			// Table header - the head attribute can be 'on', 'true', '1' to activate it
			if( filter_var( $atts['head'], FILTER_VALIDATE_BOOLEAN ) )
			{
				$html .= '<thead>';
				foreach( $this->split( $content, PHP_EOL, 0, 1 ) as $row )
				{				
					$html .= '<tr>';
					foreach( $this->split( $row ) as $col )
					{
						$html .= sprintf( '<th>%s</th>', $col );
					}
					$html .= '</tr>';
				}
				$html .= '</thead>';			
				
				$table_body_offset = 1;
			}

			// Table body
			$html .= '<tbody>';
			foreach( $this->split( $content, PHP_EOL, $table_body_offset, -1 ) as $row )
			{				
				$html .= '<tr>';
				foreach( $this->split( $row ) as $col )
				{
					$html .= sprintf( '<td>%s</td>', $col );
				}
				$html .= '</tr>';
			}
			$html .= '</tbody>';

			// Close table
			$html .= '</table>';

			return $html;		
		}
		
		/**
		 * split string based on a given delimiter
		 *
		 * @access  public
		 * @since   0.2
		 * @param string $s Input string
		 * @param string $delimiter Delimiter
		 * @param integer $offset Array slice offset
		 * @param integer $items Number of array slice items to return
		 * @return array $rows Resulting array
		 */

		public function split( $s, $delimiter = '|', $offset = 0, $items = -1 ) 
		{
			$rows = explode( $delimiter, $s );			
			if( ! is_array( $rows ) )
				$rows = array();
						
			return ( $items >= 0) ? array_slice( $rows, $offset, $items ) : array_slice( $rows, $offset );
		}
		
	} // end class

	
endif;

