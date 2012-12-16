Active Record Class Extension
=============================

Description
-----------
CodeIgniter Active Record Extension to allow where clauses with parenthesis.  This
extension adds 2 methods to active record that allow the user to define group of
fields & values to be included within a set of parenthesis for a where clause


Details
-------
Tested on: CodeIgniter 2.1.3
License: MIT (see LICENSE.md)
Copyright: 2012, Andrew Podner


Installation:
-------------
* Add the MY_DB.php, MY_DB_active_rec.php, and MY_Loader.php files to the application/core directory
* If you are using a subclass_prefix other than "MY_", you will need to modify these files accordingly


Usage:
------
* There are 2 functions, where_group() and or_where_group().
* These 2 functions accept 2 parameters: an array of values and an escape setting
* The array passed to the method is a multidimensional array.
  * The main array encapsulates all of the sub arrays that contain values
  * The sub arrays have either 2 or 3 elements
    * First Element: field name (can accept operators such as <,>,!= [default: =])
    * Second Element: value
    * Third Element: the joining operator (AND/OR). [NOTE: do not use in the first subarray]

Notes:
------
* As stated above, the first subarray should not have a 3rd element for the AND/OR. (Will be clipped if it exists)
* where_group() will place "AND" in front of the parenthesis
* or_where_group() will place "OR" in front of the parenthesis
* If one of these functions is used as the first part of the SQL Statement's where clause, the AND/OR will be clipped.

Example:
--------

```php

$arrClause = array(
    array('fieldName1', 'value1'),
    array('fieldName2 >', 'value2', 'OR'),
);

$this->db->from('pages');
$this->db->where('user_id', 6);
$this->db->where_group($arrClause);
$this->db->get();

/*
SQL Statement:
SELECT * FROM (`pages`) WHERE `id` = 6 AND (`fieldName1` = 'value1' OR `fieldName2` < 'value2)
*/


```

Thanks:
-------

* Calvin Lai <http://robotslacker.com> for the mod to extend DB and Active Record
* Mineth Studios <http://mineth.net> for the blog post about extending DB & Active Record
* Ninjabear and Bestmomo for posting the questions in CI Forums and giving me a reason to write this.


Release Notes:
--------------
* 12/16/12: First Release.