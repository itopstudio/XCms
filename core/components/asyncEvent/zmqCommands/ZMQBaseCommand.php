<?php
/**
 * @name ZMQBaseCommand.php
 * @author lancelot <lancelot1215@gmail.com>
 * Date 2013-12-7 
 * Encoding UTF-8
 */
class ZMQBaseCommand extends CConsoleCommand{
	public $contextIoThreads = 1;
	public $contextPersistent = true;
	/**
	 * @var ZMQContext
	 */
	private $_context = null;
	
	public function init(){
		parent::init();
		$this->createContext();
	}
	
	protected function createContext(){
		$this->_context = new ZMQContext($this->contextIoThreads,$this->contextPersistent);
	}
	
	public function getContext(){
		if ( $this->_context === null ){
			$this->createContext();
		}
		return $this->_context;
	}
}