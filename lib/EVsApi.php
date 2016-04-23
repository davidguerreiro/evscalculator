<?php

class EVs {

	/**
	* API base url
	*/
	const API_URL = "http://api.evscalculator.com/v1/";

	/**
	*
	* Get data from the API
	*
	* @param String $url (required) Dinamic part of the URL
	* @param Array $params Post params array.
	* @param String $custom_request HTTP custom method 
	* @return Object json decoded object
	*/
	private function getData($url, $params = null, $custom_request = null){

		if(!is_string($url) || !isset($url))
			return false;

		//building url
		$petition_url = self::API_URL . $url;

		//curl init
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $petition_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		//adding custom method if required
		if(!is_null($custom_request) && is_string($custom_request) && $custom_request !== '')
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $custom_request);

		//adding params if required
		if(!is_null($params) && !empty($params) && is_array($params))
			curl_setopt($curl, CURLOPT_POSTFIELDS, $params);

		//exc
		$data = curl_exec($curl);
		curl_close($curl);

		return json_decode($data);
	}

	//Other methods

	/**
	*
	* Get hordes
	*
	* @param $stat (optional) String Filter by stat
	* @param $game (optional) int Filter by game
	* @return $data json object with all the hordes
	*
	*/
	public function getHordes($stat = '', $game = false){

		//building the url
		$petition_url = 'hordes';

		if(!empty($stat) && $game !== false)
			$petition_url .= '?stat='.$stat.'&game='.$game;
		else{
			if(!empty($stat))
				$petition_url .= '?stat='.$stat;

			if($game !== false)
				$petition_url .= '?game='.$game;
		}

		//getting data
		return self::getData($petition_url);
	}


	/**
	*
	* Get berries
	*
	* @param $stat String stat Filter berries by stat
	* @return $data json object with all the berries
	*
	*/
	public function getBerries($stat = ''){

		//building the url
		$petition_url = 'berries';

		if(!empty($stat))
			$petition_url .= '?stat='.$stat;

		//getting data
		return self::getData($petition_url);
	}

	/**
	*
	* Get Vitamins
	*
	* @param $stat String stat Filter Vitamins by stat
	* @return $data json object with all the Vitamins
	*
	*/
	public function getVitamins($stat = ''){

		//building the url
		$petition_url = 'vitamins';

		if(!empty($stat))
			$petition_url .= '?stat='.$stat;

		//getting data
		return self::getData($petition_url);
	}

	/**
	*
	* Get Power Items
	*
	* @param String $stat Filter Power Items by stat
	* @return Object json object data
	*/
	public function getPowerItems($stat = ''){

		//building the url
		$petition_url = 'items';

		if(!empty($stat))
			$petition_url .= '?stat='.$stat;

		//getting data
		return self::getData($petition_url);
	}

	//Training methods

	/**
	*
	* Get trainings
	*
	* @param $id String Training codificated id -- Filter by id
	* @param $stat String Filter by stat (Id required)
	* @return $data json object with all the tranings data
	*
	*/
	public function getTrainings($id = '', $stat = ''){

		//building the url
		$petition_url = 'trainings';

		if(!empty($id)){
			$petition_url .= '/'.$id;

			if(!empty($stat))
				$petition_url .= '/'.$stat;
		}


		//getting data
		 return self::getData($petition_url);
	}

	/**
	*
	* Post trainings
	*
	* @param $params (required) Array Array which contents all the stats to be training of, the game, if pokerus is being used, if power brace is being used, if sturdy object is being used and a non required timestamp parameter
	* @return $data json object which contains all the new training created
	*
	*/
	public function postTraining($params){

		//buildin the url
		$petition_url = 'trainings';

		//getting data
		 return self::getData($petition_url, $params, 'POST');
	}

	/**
	*
	* Delete trainings
	*
	* @param $id (required) String Trining encoded id
	* @return $response Status response with status code
	*
	*/
	public function deleteTraining($id){

		//building the url
		$petition_url = 'trainings/'.$id;

		//getting data
		 return self::getData($petition_url, null, 'DELETE');

	}

	//Records

	/**
	*
	* Get all the records related with a single training
	*
	* @param $id String (required) Training encoded id
	* @param $stat String filter by stat
	* @return $data json object with all the records related with that training
	*
	*/
	public function getRecords($id, $stat = ''){

		//building the url
		$petition_url = 'trainings/'.$id.'/records';

		if(!empty($stat))
			$petition_url .= '/'.$stat;

		//getting data
		 return self::getData($petition_url);
	}

	/**
	*
	* Get all the actions for a single training and stat
	*
	* @param $id String (required) Training encoded id
	* @param $stat String (required) Stat name
	* @return $data json object with all the records related with that training
	*
	*/
	public function getActions($id, $stat){

		//building the url
		$petition_url = 'trainings/'.$id.'/actions/'.$stat;

		//getting data
		 return self::getData($petition_url);
	}

	/**
	*
	* Create a new training record
	*
	* @param $id String (required) Training encoded id
	* @param $params (requried) Array Array which contains all the parameters needed
	* @return $data json object with all the new record created
	*
	*/
	public function postRecord($id, $params){

		//building the url
		$petition_url = 'trainings/'.$id.'/records';

		//getting data
		 return self::getData($petition_url, $params, 'POST');
	}

	/**
	*
	* Update a single value on the database
	*
	* @param $id String (required) Training encoded id
	* @param $params Array (requried) Array which contains all the parameters needed
	* @return $data Object json object which contains the updated training data
	*/
	public function patchTraining($id, $params){

		//building the url
		$petition_url = 'trainings/'.$id;

		//getting data
		 return self::getData($petition_url, $params, 'PATCH');

	}

	/**
	*
	* Get a summary of a training
	*
	* @param $id String (required) Training encoded id
	* @return $data Object json object which contains the summary of a traning (actions used, times used and items used during the training)
	*/
	public function getTrainingSummary($id){

		//buildind ghe url
		$petition_url = 'trainings/' . $id . '/summary';

		//getting data
		return self::getData($petition_url);

	}

	/**
	*
	* This function enables pokerus
	*
	* @param $id String (requried) Training encoded id
	* @return $data Object json object which contains the updated training data
	*/
	public function enablePokerus($id){

		//building the id
		$petition_url = 'trainings/'.$id;

		$params = [
			'op' => 'replace',
			'field' => 'pokerus',
			'value' => time()
		];

		//getting data
		return self::getData($petition_url, $params, 'PATCH');
	}

	/**
	*
	* Equip a power item during a training
	*
	* @param $id String (required) Training encoded id
	* @param $item_id int Power Item id
	* @return $data Object json object which contains the updated traning data
	*/
	public function setPowerItem($id, $item = ''){

		//building the url
		$petition_url = 'trainings/'.$id;

		$params = ['field' => 'power_item'];

		//setting operation and value if required
		if($item === '')
			$params['op'] = 'remove'
		else{
			$params['op'] = 'replace';
			$params['value'] = $item;
		}

		return self::getData($petition_url, $params, 'PATCH')
	}

};