<?php
class EmailReader {

	public $conn;

	private $_inbox;
	private $_msgCnt;

	private $_server = 'yourmailserver';
	private $_user   = 'youraccount';
	private $_pass   = 'yourpassword';
	private $_port   = 110; 
	private $_type = 'pop3';

	
	public function __construct() {
		$this->_connect();
		$this->_inbox();
	}

	/** @param None
	  * @return Void
	  * Closes the connection
	  */
	private function _close() {
		$this->_inbox = array();
		$this->_msgCnt = 0;

		imap_close($this->conn);
	}

	
	/** @param None
	  * @return Void
	  * Opens a connection
	  */

	private function _connect() {
		$this->conn = imap_open('{'.$this->_server.':' . $this->_port . '/' . $this->_type .'}', $this->_user, $this->_pass);
	}

	/** @param int $msgIndex Index of a message
	  * @param string $folder The name of a folder
	  * @return Void
	  * Moves a message to a new folder
	  */

	public function move($msgIndex, $folder='INBOX.Processed') {
		// move on server
		imap_mail_move($this->conn, $msgIndex, $folder);
		imap_expunge($this->conn);

		// re-read the inbox
		$this->_inbox();
	}

	/** @param int $mgsIndex Index of a message
	  * @return array The associative array of a message
	  * Gets a specific message
	  */

	public function get($msgIndex = null) {
		if (count($this->_inbox) <= 0) {
			return array();
		}
		elseif ( ! is_null($msgIndex) && isset($this->_inbox[$msgIndex])) {
			return $this->_inbox[$msgIndex];
		}

		return $this->_inbox[0];
	}

	/** @param None
	  * @return array The associative array of each message
	  * Reads the inbox
	  */

	private function _inbox() {
		$this->_msgCnt = imap_num_msg($this->conn);

		$in = array();
		for($i = 1; $i <= $this->_msgCnt; $i++) {
			$in[] = array(
				'index'     => $i,
				'header'    => imap_headerinfo($this->conn, $i),
				'body'      => imap_body($this->conn, $i),
				'structure' => imap_fetchstructure($this->conn, $i)
			);
		}

		$this->_inbox = $in;
	}

	/** @param None
	  * @return array The inbox associative array
	  * Gets the inbox associative array
	  */

	public function getInbox() {
		$inbox = $this->_inbox;
		return $inbox;
	}

}


