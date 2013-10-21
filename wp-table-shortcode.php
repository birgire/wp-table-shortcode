<?php
/**
 * Plugin Name: WP Table Shortcode
 * Author: birgire
 * Author URI: https://github.com/birgire/
 * Version: 0.1
 * Text Domain: wp-table-shortcode
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
	* Piktochart Class
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

		public function __construct() {
		
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

		 public function get_object () {
			
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
		
			//
			// Generate the output
			//
			
			// open table
			$html = sprintf( '<table class="%s" width="%s" style="">', $atts['class'], $atts['width'], $atts['style'] );
			
			// table header
			if( '1' === $atts['head'] )
			{
				$html .= '<thead>';
				foreach( $this->rows( $content, 1, 1 ) as $row )
				{				
					$html .= '<tr>';
					foreach( $this->cols( $row ) as $col )
					{
						$html .= sprintf( '<th>%s</th>', $col );
					}
					$html .= '</tr>';
				}
				$html .= '</thead>';
			
			}

			// table body
			$html .= '<tbody>';
			foreach( $this->rows( $content, 2, -1 ) as $row )
			{				
				$html .= '<tr>';
				foreach( $this->cols( $row ) as $col )
				{
					$html .= sprintf( '<td>%s</td>', $col );
				}
				$html .= '</tr>';
			}
			$html .= '</tbody>';

			// close table
			$html .= '</table>';

			return $html;		
		}
		
		/**
		 * Make new rows from string based on PHP_EOL
		 *
		 * @access  public
		 * @since   0.1
		 * @param string $s
		 * @return array $rows
		 */

		public function rows( $s, $offset = 0, $items = -1 ) 
		{
			$rows = explode( PHP_EOL, $s );			
			if( ! is_array( $rows ) )
				$rows = array();
			
			print_r( $items );
			
			return ( $items >= 0) ? array_slice( $rows, $offset, $items ) : array_slice( $rows, $offset );
		}
		
		/**
		 * Make new columns from string based on PHP_EOL
		 *
		 * @access  public
		 * @since   0.1
		 * @param string $s
		 * @return array $rows
		 */

		public function cols( $s, $offset = 0, $items = -1 ) 
		{
			$cols = explode( $this->delimiter, $s );			
			if( ! is_array( $cols ) )
				$cols = array();
				
			return ( $items >= 0 ) ? array_slice( $cols, $offset, $items ) : $cols;
		}

	} // end class

	
endif;

