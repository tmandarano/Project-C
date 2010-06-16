<?php
class SessionManager {
    public $life_time;
	public $cookie_path			= NULL;				//Path to set in session_cookie
	public $cookie_domain		= NULL;				//The domain to set in session_cookie
	public $cookie_secure		= NULL;				//Should cookies only be sent over secure connections?
	public $cookie_httponly		= NULL;				//Only accessible through the HTTP protocol?


    function SessionManager() {
        $this->life_time = get_cfg_var("session.gc_maxlifetime");

        session_set_save_handler(array(&$this, "open"),
                                 array(&$this, "close"),
                                 array(&$this, "read"),
                                 array(&$this, "write"),
                                 array(&$this, "destroy"),
                                 array(&$this, "gc")
                                 );
    }

    function open($save_path, $session_name) {
        $conn = ConnectionFactory::getFactory()->getConnection();
        
        global $sess_save_path;
        $sess_save_path = $save_path;

        return TRUE;
    }

    function close() {
        return true;
    }

    function read($id) {
        $data = "";

        $time = time();
        
        $newid = mysql_real_escape_string($id);
        $sql = "SELECT session_data from sessions WHERE session_id = :session_id and expires > :time";
        $stmt = $conn.prepare($sql);
        $stmt->bindParam(":session_id", $newid, PDO::PARAM_STR);        
        $stmt->bindParam(":time", $time, PDO::PARAM_STR);

        $rs = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        
        if(len($rs) > 0) {
            $row = $rs;
            $data = $row['session_data'];
        }    

        return $data;
    }

    function write($id, $data) {
        $time = time() + $this->life_time;

        $newid = mysql_real_escape_string($id);
        $newdata = mysql_real_escape_string($data);

        $sql = "REPLACE sessions (session_id, session_data, expires) VALUES (:session_id, :session_data, :expires)";
        $stmt = $conn.prepare($sql);
        $stmt->bindParam(":session_id", $newid, PDO::PARAM_STR);        
        $stmt->bindParam(":session_data", $newdata, PDO::PARAM_STR);
        $stmt->bindParam(":expires", $time, PDO::PARAM_STR);

        $conn->query($sql);        

        return TRUE;
    }

    function destroy($id) {
        $newid = mysql_real_escape_string($id);
        $sql = "DELETE FROM sessions where session_id = :session_id";
        $stmt = $conn.prepare($sql);
        $stmt->bindParam(":session_id", $newid, PDO::PARAM_STR);        

        $conn->query($sql);        

        return TRUE;
    }

    function gc() {
        $sql = "DELETE FROM sessions WHERE expires < UNIX_TIMESTAMP()";
        $conn->query($sql);

        return TRUE;
    }
}
?>