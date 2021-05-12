<?php
/*
//|-------------------------------------------|\\
//|        Database abstraction class         |\\
//|-------------------------------------------|\\
*/
/**
 * Database abstraction class
 *
 * @author Ducki`
 * @package V3
 * @version 1.0.0
*/
defined('on_top') or die('You cannot access this file directly. Please go to index.php.<br />Du kannst diese Datei nicht direkt aufrufen. Bitte gehe zu index.php.');
/**
 * Database abstraction class
 * 
 * Provides all database relevant functions
 * @author Ducki`
 * @version 1.0.0
 *
 */
class db {

	protected	$connection_resource;

	protected	$cur_query;

	public		$query_count			= 0;
	
	public		$sql_debug_output		= '';

	/**
	 * Database connection settings
	 *
	 * Settings like username or password for the database server
	 *
	 * @var array
	 */
	protected	$db_settings			= array('host'			=> 'localhost',
												'database'		=> 'gfx-v3',
												'user'			=> 'root',
												'password'		=> '',
												'persistent'	=> false);


	function __call($m, $a) {
		trigger_error('Called to not existing method <b>'.$m.'</b><br />', E_USER_ERROR);	
	}
	
									
	//|===========================================
	//|                Connection
	//|===========================================
	
	public function connect() {

			$this->connection_resource = mysql_connect(	$this->db_settings['host'],
														$this->db_settings['user'],
														$this->db_settings['password']);

		
		if (!$this->connection_resource) {
			trigger_error('Could not connect to database.', E_USER_ERROR);
		}
		if (!mysql_select_db($this->db_settings['database'])) {
			trigger_error('Database <b>'.$this->db_settings['database'].'</b> does not exist.', E_USER_ERROR);
		}

	}
	
	
	
	//|===========================================
	//|              Simple select
	//|===========================================
	
	/**
	 * Select
	 * 
	 * Performs a simple select query.
	 *
	 * @param string $what Field names
	 * @param string $from Table names
	 * @param string $where Where clause
	 * @param string $order_by Ordering
	 * @param string $order Ordering
	 * @return resource
	 */
	public function simple_select($what, $from, $where='', $order_by='', $order='', $limit='') {
		$this->cur_query = 'SELECT '.$what.' FROM '.$from.'';

		if ($where != '') {

			$this->cur_query .= ' WHERE '.$where;
			
		}
		
		if (($order_by != '') AND ($order != '')) {

			$this->cur_query .= ' ORDER BY '.$order_by.' '.$order;
		
		}
		
		if ($limit != '') {

			$this->cur_query .= ' LIMIT '.$limit;
			
		}

		return $this->query($this->cur_query);
	}
	
	
	
	//|===========================================
	//|              Manual query
	//|===========================================
	
	/**
	 * Query
	 *
	 * Executes the given query.
	 *
	 * @param string $sql The SQL-Query
	 * @param bool $board_db
	 * @return resource
	 */
	public function query($sql, $board_db=false) {
		if (SQL_DEBUG) {
			$debug_start_time = start_timer();
		}
		
		$this->connection_resource = mysql_query($sql);
		
		if (!$this->connection_resource) {
			trigger_error('Error in SQL-Query!<br>'.mysql_error().'<p>'.$sql.'</p>', E_USER_ERROR);
			
		}
	//	echo $sql, "\r\n";
		//|-------------------------------------------
		//|                Debug me!
		//|-------------------------------------------

		if (SQL_DEBUG) {
			$debug_end_time=end_timer();

			if (strpos($sql, 'SELECT') !== false) {
				$debug_res=mysql_query('EXPLAIN '.$sql);
				
				$this->sql_debug_output .= '<table style="background: #dedede; font-family: arial; border: 1px solid #acacac" cellspacing="1" cellpadding="3"><tr style="background: white;"><th colspan="8">Select query</th></tr><tr style="background: #fafafa;"><td colspan="8">'.$sql.'</td></tr><tr style="background: white; font-weight: bold;"><td>table</td><td>type</td><td>possible_keys</td><td>key</td><td>key_len</td><td>ref</td><td>rows</td><td>Extra</td></tr>';
			
					
				while ($row=mysql_fetch_assoc($debug_res)) {
					$col_type='#ffffff';
					
					if ($row['type']=='ref' OR $row['type']=='eq_ref' or $row['type']=='const') {
						$col_type='#d8ffc9';
					}
					elseif ($row['type']=='ALL') {
						$col_type='#ffd0c8';
					}
					
					$this->sql_debug_output .= '<tr style="background: white;"><td>'.$row['table'].'</td>
											<td style="background: '.$col_type.';">'.$row['type'].'</td>
											<td>'.$row['possible_keys'].'</td>
											<td>'.$row['key'].'</td>
											<td>'.$row['key_len'].'</td>
											<td>'.$row['ref'].'</td>
											<td>'.$row['rows'].'</td>
											<td>'.$row['Extra'].'</td>
											</tr>';
				}
				
				if ($debug_end_time > 0.09) {
					$col_type='#ffd0c8';
				}
				else {
					$col_type='#fff';
				}
				
				$this->sql_debug_output .= '<tr>
											<td colspan="8" style="background: '.$col_type.'">time: '.$debug_end_time.'</td>
											</tr></table>';

			}
		
			
			
		}
		
		$this->query_count++;
		
		return $this->connection_resource;
	}
	
	
	//|===========================================
	//|                  Fetch
	//|===========================================
	
	function fetch($con_res="") {
    	if (!$con_res) {
    		$con_res = $this->connection_resource;
    	}
        
        return mysql_fetch_assoc($con_res);
    }
	
	//|===========================================
	//|                  Update
	//|===========================================
	
	/**
	 * Update
	 * 
	 * Performs an update query.
	 * Example:
	 * <code>
	 * $core->db->update('table', array('field' => 'value', 'field2' => 'value2'), 'id=1');
	 * </code>
	 *
	 * @param string $table Table name
	 * @param array $a Values
	 * @param string $where Where clause
	 * @param bool $board
	 * @return resource
	 */
	public function update($table, $a, $where='', $board=false) {
		
		$set = '';
		
		foreach ($a as $k => $v) {
			

			
			if (is_array($v)) {
				
				if ($v[1] === 1) {
						
						$set.=$k."=".$v[0].',';
					
				}
			}
			else {
				
				if (is_numeric($k) AND intval($v) == $v) {
					
					$set.=$k."=".$v.',';
					
				}
				else {
					
					$v = mysql_real_escape_string($v);
					$set.=$k."=".'\''.$v.'\',';
					
				}
			}

		}
		
		/*foreach ($a as $k => $v)
		{
			$v = mysql_real_escape_string($v);
			
			if (is_numeric($v) AND intval($v) == $v) {
				$set.=$k."=".$v.",";
			}
			else {
				$set.=$k."='".$v."',";
			}
		}*/
		
		$sql = 'UPDATE '.$table.' SET '.$set;
		
		$sql = preg_replace('/,$/' , '' , $sql);
		
		if ($where) {
    		$sql.=' WHERE '.$where;
    	}
    	
		return $this->query($sql, $board);
	}
	
	
	//|===========================================
	//|                 Insert
	//|===========================================
	
	/**
	 * Insert
	 *
	 * Inserts the specified values in the specified tables
	 * Example:
	 * <code>
	 * $core->db->insert('table', array('field' => 'value', 'field2' => 'value2'));
	 * </code>
	 *
	 * @param string $table Table name
	 * @param array $a Array with values
	 * @param bool $board
	 * @return resource
	 */
	public function insert($table, $a, $board=false) {
		
		$field_value	= '';
		$field			= '';
		
		foreach ($a as $k => $v) {
			
			$field.=$k.',';
			
			if (is_array($v)) {
				
				if ($v[1] === 1) {
						
						$field_value.=$v[0].',';
					
				}
			}
			else {
				
				if (is_numeric($k) AND intval($v) == $v) {
					
					$field_value.=$v.',';
					
				}
				else {
					
					$v = mysql_real_escape_string($v);
					$field_value.='\''.$v.'\',';
					
				}
			}

		}
		
		$field			= preg_replace('/,$/' , '' , $field );
		$field_value	= preg_replace('/,$/' , '' , $field_value);
		//echo 'INSERT INTO '.$table.' ('.$field.') VALUES('.$field_value.')';
		return $this->query('INSERT INTO '.$table.' ('.$field.') VALUES('.$field_value.')');
	}
	
	
	//|===========================================
	//|                 Delete
	//|===========================================
	
	/**
	 * Delete
	 *
	 * Deletes the specified row
	 * Example:
	 * <code>
	 * $core->db->delete('foo', 'id = 2');
	 * </code>
	 *
	 * @param string $table Table name
	 * @param string $where where-clause
	 * @return resource
	 */
	function delete($table, $where) {
    	
		return $this->query('DELETE FROM '.$table.' WHERE '.$where);
	}

}

?>