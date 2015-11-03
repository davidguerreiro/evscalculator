<?php

//Validator class - Created by David Guerreiro - me@davidguerreiro.com - davidguerreiro.com

//This class should be used to validate different types of data (strings, integer, emails, nums, nums_telf, text fields, etc)


class Validator{

	//object constructor

	function __construct(){

		$this->last_validation = false;		//Last validation status

	}

	/**
	*
	*This method returns the last validation
	*
	*@return boolean $last_validation Last validation status
	*
	**/
	public function getLastValidation(){
		return $this->last_validation;
	}

	/**
	*
	*This method changes the last validation value
	*
	*@param boolean $new_validation_value New validation value
	*@return void
	*
	**/
	private function setLastValidation($new_validation_value){

		if(is_bool($new_validation_value))
			$this->last_validation = $new_validation_value;

	}

	/**
	*
	*This method validate a text field
	*
	*@param string $text Text to be validated
	*@param boolean $checkEmpty Check wheter the empty field has to be validated or not. Optional. True by default
	*@param boolean $numbersAllowed Check wheter the numbers are allowed or not
	*@return boolean Validation status
	*
	**/
	public function validateTextField($text, $checkEmpty = true, $numbersAllowed = false){

		self::setLastValidation(false);
		$not_allowed_characters = '@#~$%&/()=\'".,:;´{}+-/*!|<>?¿-_^'; //Not allowed characters by default

		if(!$numbersAllowed)
			$not_allowed_characters .= '0123456789';

		//Check if we have number inside
		if(!is_string($text))
			return false;

		$str_length = strlen(utf8_decode($text));

		if($checkEmpty){

			//Check if the string is empty
			if(empty($text) || $text === '')
				return false;

		}	

		//checking if the text has some special or forbbiden characteres

		for($q = 0; $q < $str_length; $q++){


			if(strpos($not_allowed_characters, substr($text, $q, 1)) !== false)
				return false;	

		}

		self::setLastValidation(true);
		return true;

	}

	/**
	*
	*This method validate a number field
	*
	*@param int || float $number The numeric value that we are validating
	*@param boolean $checkEmpty Validate wheter the value is empty or not. Optional parameter. True by default
	*@return boolean Validation status
	*
	**/
	public function validateNumberField($number, $checkEmpty = true){

		self::setLastValidation(false);

		//checking if this is a int
		if(!is_numeric($number))
			return false;

		if($checkEmpty){

			//checking if it is empty
			if(empty($number) || $number === '')
				return false;
		}

		self::setLastValidation(true);
		return true;
	}

	/**
	*
	*This method check if a group of variables exist
	*
	*@param array $variables Array wich contains all the variables that we want to validate
	*@param boolean nullAllowed Check wheter the value null is allowed or not. Optional parameter. False by default.
	*@return boolean Validation Status
	*
	*/
	public function validateExist($variables, $nullAllowed = false){

		self::setLastValidation(false);

		//checking if an array has been sent as a parameter
		if(!is_array($variables))
			return false;

		$length = count($variables);

		//check if the array is empty
		if($length <= 0)
			return false;

		//check if we have a sequential array
		for($i = 0; $i < $length; $i++){
			if(!is_numeric(key($variables)))
				return false;
			next($variables);
		}

		//check if the variable exist
		for($i = 0; $i < $length; $i++){

			if(!isset($variables[$i]))
				return false;

			if(!$nullAllowed){

				if(is_null($variables[$i]))
					return false;

			}
		}

		self::setLastValidation(true);
		return true;
	}

	/**
	*
	*This method validates an email address
	*
	*@param string $email Email address that we want to validate
	*@return boolean Validation status
	*
	*/
	public function validateEmailField($email){

		self::setLastValidation(false);
		$not_allowed_characters =  '?¿*,;:\'/¨´{}()%$·#';

		//checking if email is a string
		if(!is_string($email))
			return false;

		$str_length = strlen(utf8_decode($email));

		//check if the character '@' is included in our email
		if(!strpos($email, '@'))
			return false;

		//filter this email

		if(!filter_var($email, FILTER_VALIDATE_EMAIL))
			return false;

		//checking special characters
		for($q = 0; $q < $str_length; $q++){

			if(strpos($not_allowed_characters, substr($text, $q, 1)) !== false)
				return false;

		}

		self::setLastValidation(true);
		return true;
	}

	/**
	*
	*This method checks if an url is correct
	*
	*@param string $url The url that we want to validate
	*@return boolean Validation status
	*
	*/
	public function validateUrl($url){

		self::setLastValidation(false);

		//check if it is an string

		if(!is_string($url))
			return false;

		if(!filter_var($url, FILTER_VALIDATE_URL))
			return false;

		self::setLastValidation(true);
		return true;
	}


}

?>