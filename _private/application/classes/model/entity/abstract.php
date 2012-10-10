<?php
/**
 * Entity Abstract File
 *
 * @package Mwl
 * @author  Mindwork Labs
 */
abstract class Model_Entity_Abstract
{
	/**
     * Magic function to capture getters & setters
     *
     * @param string $name      the name of the function
     * @param array  $arguments an array of arguments
     *
     * @return void
     */
	public function __call($name, array $arguments)
	{
		$type     = substr($name, 0, 3);
		$variable = strtolower(substr($name, 3));
		switch ($type) {
			case 'get':
				return $this->$variable;
			break;
			case 'set':
				$this->$variable = $arguments[0];
			break;
			default:
				return $this->invalid();
			break;
		}

        return $this;
	}

    public function getData()
    {
        return $this->_data;
    }
	/**
     * Magic function to capture getters
     *
     * @param string $name name of the variable
     *
     * @return mixed
     */
	public function __get($name)
	{	
		if (true === array_key_exists($name, $this->_data)) {
			return Arr::get($this->_data, $name);
		} else {
			throw new Exception('Param ' . $name . ' not found in ' . get_called_class());
		}
	}

	/**
     * Magic function to capture setters
     *
     * @param string $name  the name of the var
     * @param string $value the value for the var
     *
     * @return void
     */
	public function __set($name, $value)
	{
		if (true === array_key_exists($name, $this->_data)) {
			$this->_data[$name] = $value;
		} else {
			throw new Exception('Param ' . $name . ' not found in ' . get_called_class());
		}
	}

	/**
	 * called when invalid function is called
	 *
	 * @return boolean
	 **/
	public function invalid()
	{
		return false;
	}
}