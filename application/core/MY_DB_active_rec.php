<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CodeIgniter Active Record Class Extension
 * @author andy <andy@unassumingphp.com>
 * @copyright (c) 2012, Andrew Podner
 * @license http://opensource.org/licenses/MIT MIT License
 * @category Database
 *
 * This extension permits the user to pass a multidimensional array
 * to the where_group and or_where_group methods and build a where clause
 * that is encapsulated in parenthesis ( )
 *
 * The array passed into the methods must be structured as follows
 * $arrClause[][0] : The name of the field and a custom operator
 * $arrClause[][1] : The value that the field is being filtered on
 * $arrClause[][2] : 'AND'  or   'OR'
 *
 * NOTE: The operator setting is not used on the first field->value pair.
 *
 *
 * EXAMPLES:
 *
 * $arrClause = array (
 *      array('fieldName1', 'value1'),
 *      array('fieldName2 !=', 'value2', 'AND'),
 *      array('fieldName3', 'value3', 'OR')
 * );
 *
 * $this->where_group($arrClause)
 *    PRODUCES
 * AND (`fieldName1` = 'value1' AND `fieldName2` != 'value2' OR `fieldName3` = 'value3')
 *
 *
 * $this->or_where_group($arrClause)
 *    PRODUCES
 * OR (`fieldName1` = 'value1' AND `fieldName2` != 'value2' OR `fieldName3` = 'value3')
 *
 *
 *
 */
class MY_DB_active_record extends CI_DB_active_record
{
    // --------------------------------------------------------------------

	/**
	 * Where Group
	 *
	 * Generates a ( ) grouped WHERE portion of the query prepended with AND.
	 *
	 * @param	mixed
	 * @param	mixed
	 * @return	object
	 */
	public function where_group($arrClause, $escape = TRUE)
	{
		return $this->_where_group($arrClause, 'AND ', $escape);
	}

	// --------------------------------------------------------------------

	/**
	 * OR Where Group
	 *
	 * Generates a ( ) grouped WHERE portion of the query prepended with OR.
	 *
	 * @param	mixed
	 * @param	mixed
	 * @return	object
	 */
	public function or_where_group($arrClause, $escape = TRUE)
	{
		return $this->_where_group($arrClause, 'OR ', $escape);
	}

	// --------------------------------------------------------------------


    /**
	 * Where Group
	 *
	 * Called by where_group() or or_where_group()
	 *
	 * @param	mixed
	 * @param	mixed
	 * @param	string
	 * @return	object
	 */
    protected function _where_group($arrClause, $type = 'AND ', $escape = NULL)
    {
        $temp_where ='';

		// If the escape value was not set will will base it on the global setting
		if ( ! is_bool($escape))
		{
			$escape = $this->_protect_identifiers;
		}

        $i = 0;
		foreach ($arrClause as $x => $arr)
		{
            //In the first element we do not need and cannot have an operator
            if ($i == 0)
            {
                $arr[2] = '';
            }

			if (is_null($arr[1]) && ! $this->_has_operator($arr[0]))
			{
				// value appears not to have been set, assign the test to IS NULL
				$arr[1] .= ' IS NULL';
			}

			if ( ! is_null($arr[1]))
			{
				if ($escape === TRUE)
				{
					$arr[0] = $this->_protect_identifiers($arr[0], FALSE, $escape);

					$arr[1] = ' '.$this->escape($arr[1]);
				}

				if ( ! $this->_has_operator($arr[0]))
				{
					$arr[0] .= ' = ';
				}
			}
			else
			{
				$arr[0] = $this->_protect_identifiers($arr[0], FALSE, $escape);
			}
			$temp_where .= $arr[2]. ' ' . $arr[0] . $arr[1] . ' ';
            $i++;
		}

        //We do not want OR or AND in front of the opening parenthesis if this is
        //the first piece of the where clause.
        if (count($this->ar_where) == 0) {
           $grouped_where = " (". $temp_where .")";
        } else {
           $grouped_where = " ". $type ." (". $temp_where .")";
        }

        $this->ar_where[] = $grouped_where;

        if ($this->ar_caching === TRUE)
        {
            $this->ar_cache_where[] = $grouped_where;
            $this->ar_cache_exists[] = 'where';
        }

		return $this;
    }
}

/* End of file MY_DB_active_rec.php */
/* Location: ./application/core/MY_DB_active_rec.php */
