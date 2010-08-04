<?php
require_once('src/models/photo.php');
require_once('src/models/user.php');
require_once('src/models/tag.php');
require_once('src/models/comment.php');
require_once('src/utils/logging.php');

/**
 * @internal
 * @param string $sql This is a SQL string that will be executed using a prepared statement.
 * @param array $params This is an array of params that need to be bound to the query.
 * @param string $classname This is the name of the class that will be used to attempt to fill a valid object with the results of the query. The fields in this class should match up with the returned columns of the query.
 * @return array
 *
 * This function takes the incoming sql statement and creates a PDO PreparedStatement. 
 * Then the parameters are bound were necessary in the query, the statement is executed and an array of objects
 * of the defined class (as defined by $classname) is returned.
 */
function find_objects_by_sql($sql = '', $params = array(), $classname) {
    $db = option('db_conn');

    $result = array();
    $stmt = $db->prepare($sql);

    if ($params) {
        // Can't just pick out :limit if there are more parameters to be bound
        // because execute(array(...)) will delete previous bindings.
        foreach ($params as $param => &$val) {
            if (is_numeric($val)) {
                $stmt->bindParam($param, $val, PDO::PARAM_INT);
            } else {
                $stmt->bindParam($param, $val, PDO::PARAM_STR);
            }
        }
    }
    
    if ($stmt->execute()) {
        while ($obj = $stmt->fetchObject($classname)) {
            $result[] = $obj;
        }
    }

    return $result;
}

/**
 * @internal
 * @param string $sql This is a SQL string that will be executed using a prepared statement.
 * @param array $params This is an array of params that need to be bound to the query.
 * @return object
 *
 * This function takes the incoming sql statement and creates a PDO PreparedStatement. 
 * Then the parameters are bound were necessary in the query, the statement is executed and a single object is returned.
 */
function find_object_by_sql($sql = '', $params = array()) {
    $db = option('db_conn');

    $stmt = $db->prepare($sql);
    if ($stmt->execute($params) && $obj = $stmt->fetch(PDO::FETCH_OBJ)) {
        return $obj;
    }
    return null;
}

/**
 * @internal
 * @param array $params This is an array of parameters used to set values on an object. 
 * @param object $obj The object to set values against if it exists.
 * @return object
 *
 * This function takes parameters and uses them as values for the object passed in or a standard class.
 */
function make_model_object($params, $obj = null) {
    if (is_null($obj)) {
        $obj = new stdClass();
    }
    foreach ($params as $key => $value) {
        $obj->$key = $value;
    }
    return $obj;
}

/**
 * @internal
 * @param integer $obj_id This is the id of a row to be deleted in the table.
 * @param object $obj The table where a row will be deleted.
 * @return 
 *
 * This function takes an object id and a table and uses these values to delete a row from a table with that ID value.
 */
function delete_object_by_id($obj_id, $table) {
    $db = option('db_conn');

    $stmt = $db->prepare("DELETE FROM `$table` WHERE id = ?");
    $stmt->execute(array($obj_id));
}

/**
 * @internal
 * @param string $optional_function If this exists, this is a function that is appended in a string to wrap the column in SQL. Ex: lower(somecolumn).
 * @param string $column The column to append the colon to for the SQL String.
 * @return string
 *
 * Add the colon to the column as we prepare the PDO prepared
 * statement string. Additionally, if there are any functions
 * in $obj_columns_funcs that need to wrap around a particular
 * value parameter, then we we add that as well. 
 */
function add_colon_and_function($column, $optional_function) { 
    if(!empty($optional_function)) {
        return $optional_function.'('.add_colon($column).')';
    } else {
       return add_colon($column);
    } 
};

/** 
 * @internal
 * @param string $column The column to append the colon to for the SQL String.
 * @return string
 *
 * This function simply appends a colon to a column string. This is done this way 
 * so that it can be inlined in the "implode" call of joining the columns 
 * into a single string for the SQL query being built.
 * e.g. :somecolumnparam
 */
function add_colon($column) { return ':' . $column; };


/**
 * @internal
 * @param object $object The object which contains the data from which to build the SQL query.
 * @param string $table The name of the table used in this query.
 * @param array $obj_columns An array containing the names of columns in the query.
 * @param array $obj_column_funcs An array of any possible functions we need to wrap each column in as we iterate through the columns and build the query.
 * @return integer
 *
 * This function builds an INSERT SQL statement using data found in $object, the table as defined by $table and 
 * columns as defined in $obj_columns. This generalizes the process of creating a SQL 
 * query so that this can be used with any object or table.
 * We then bind the values for each column, execute the statement and return the ID of the 
 * object that was inserted.
 */
function create_object($object, $table, $obj_columns = array(), $obj_column_funcs = array()) {
    // Grab the database handle from the limonade option store. 
    $db = option('db_conn');

    if (!count($obj_columns)) {
        $obj_columns = array_keys(get_object_vars($object));
    }
    unset($obj_columns['id']);

    /*
     * Pay attention to the inline call to add_colon_and_function. What happens here is that 
     * as the column names are imploded into a comma-delimited string of columns we run the 
     * inline function against the column to modify each column as necessary. Adding the 
     * column (:some_column) as is standard in a PDO prepared statement and perhaps 
     * adding the function wrapper.
     */ 
    $sql =
        "INSERT INTO `$table` (" .
        implode(', ', $obj_columns) .
        ') VALUES (' .
        implode(', ', array_map('add_colon_and_function', $obj_columns, $obj_column_funcs)) . ')';

    $stmt = $db->prepare($sql);

    // Bind each object value for a given column to that column in the prepared statement
    foreach ($obj_columns as $column) {
        $stmt->bindValue(':' . $column, $object->$column);
    }

    $stmt->execute();

    return $db->lastInsertId();
}

/**
 * @internal
 * @param string $column_name Take the column name and append it to the equals and colon to create a column / param pair for the update statement.
 * @return string
 *
 * This function creates a string out of the column so that it's formatted as expected in an update statement.
 * e.g. somecolumn = :somecolumnparam
 */
function name_eq_colon_name($column_name) { return $column_name . ' = :' . $column_name; };

/** 
 * @internal 
 * @param object $object The object from which values to update the row.
 * @param string $table The name of the table in which the row will be updated.
 * @param array $obj_columns An array containing the names of the columns to update.
 * @return boolean
 *
 * This function builds an UPDATE SQL statement using the columns and the values in the object passed in
 * and then uses this statement to update a row in the table with these pairs.
 */
function update_object($object, $table, $obj_columns = array()) {
    $db = option('db_conn');

    if (!count($obj_columns)) {
        $obj_columns = array_keys(get_object_vars($object));
    }

    /*
     * In order to setup a proper prepared statement we need to create a string matching 
     * column name to a parameter (:paramname). This is what name_eq_colon_name does. 
     */
    $sql =
        "UPDATE `$table` SET " .
        implode(', ', array_map('name_eq_colon_name', $obj_columns)) .
        ' WHERE id = :id';

    $stmt = $db->prepare($sql);
    $stmt->bindValue(':id', $object->id);
  
    // Bind each object value to a given column. 
    foreach ($obj_columns as $column) {
        $stmt->bindValue(':' . $column, $object->$column);
    }

    return $stmt->execute();
}
?>
