<?php

class EVs {

	/**
	* API base url
	*/
	const API_URL = "http://api.evscalculator.com/v1/";

	//Others methods

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
		$petition_url = self::API_URL.'hordes';

		if(!empty($stat) && $game !== false)
			$petition_url .= '?stat='.$stat.'&game='.$game;
		else{
			if(!empty($stat))
				$petition_url .= '?stat='.$stat;

			if($game !== false)
				$petition_url .= '?game='.$game;
		}

		//curl init
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $petition_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		//exc
		$data = curl_exec($curl);
		curl_close($curl);

		return $data;

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
		$petition_url = self::API_URL.'berries';

		if(!empty($stat))
			$petition_url .= '?stat='.$stat;

		//curl init
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $petition_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		//exc
		$data = curl_exec($curl);
		curl_close($curl);

		return $data;
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
		$petition_url = self::API_URL.'vitamins';

		if(!empty($stat))
			$petition_url .= '?stat='.$stat;

		//curl init
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $petition_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		//exc
		$data = curl_exec($curl);
		curl_close($curl);

		return $data;
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
		$petition_url = self::API_URL.'trainings';

		if(!empty($id)){
			$petition_url .= '/'.$id.'/';

			if(!empty($stat))
				$petition_url .= '/'.$stat;
		}


		//curl init

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $petition_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		//exc
		$data = curl_exec($curl);

		$error = curl_error($curl);

		
		curl_close($curl);

		return $data;
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
		$petition_url = self::API_URL.'trainings';

		//curl init
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $petition_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($curl, CURLOPT_POSTFIELDS, $params);

		//exc
		$data = curl_exec($curl);
		curl_close($curl);

		return $data;

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
		$petition_url = self::API_URL.'trainings/'.$id;

		//curl init
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $petition_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');

		//exc
		$data = curl_exec($curl);
		curl_close($curl);

		return $data;

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
		$petition_url = self::API_URL.'trainings/'.$id.'/records';

		if(!empty($stat))
			$petition_url .= '/'.$stat;

		//curl init
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $petition_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		//exc
		$data = curl_exec($curl);
		curl_close($curl);

		return $data;
	}

	/**
	*
	* Create a new training record
	*
	* @param $id String (required) Training encoded id
	* @param $params Array Array which contains all the parameters needed
	* @return $data json object with all the new record created
	*
	*/
	public function postRecord($id, $params){

		//building the url
		$petition_url = self::API_URL.'trainings/'.$id.'/records';

		//curl init
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $petition_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($curl, CURLOPT_POSTFIELDS, $params);

		//exc
		$data = curl_exec($curl);
		curl_close($curl);

		return $data;
	}

};