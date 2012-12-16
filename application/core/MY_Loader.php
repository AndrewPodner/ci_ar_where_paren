<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 *   Custom Loader Class for CodeIgniter
 *
 *
 */
class MY_Loader extends CI_Loader {

    // --------------------------------------------------------------------

	/**
	 * Database Loader Override Method
     * Allows for MY_DB and MY_DB_active_rec extension
     *
     * @author Calvin Lai <calvin@robotslacker.com>
     * @link http://robotslacker.com/2011/05/extending-codeigniters-active-record-class/
     *
	 *
	 * @param	string	the DB credentials
	 * @param	bool	whether to return the DB object
	 * @param	bool	whether to enable active record (this allows us to override the config setting)
	 * @return	object
	 */
    public function database($params = '', $return = FALSE, $active_record = NULL)
	{
		// Grab the super object
		$CI =& get_instance();

		// Do we even need to load the database class?
		if (class_exists('CI_DB') AND $return == FALSE AND $active_record == NULL AND isset($CI->db) AND is_object($CI->db))
		{
			return FALSE;
		}

		// Check if custom DB file exists, else include core one
		if (file_exists(APPPATH.'core/'.config_item('subclass_prefix').'DB'.EXT))
		{
			require_once(APPPATH.'core/'.config_item('subclass_prefix').'DB'.EXT);
		}
		else
		{
			require_once(BASEPATH.'database/DB'.EXT);
		}

		if ($return === TRUE)
		{
			return DB($params, $active_record);
		}

		// Initialize the db variable. Needed to prevent
		// reference errors with some configurations
		$CI->db = '';

		// Load the DB class
		$CI->db =& DB($params, $active_record);
	}

}

?>
