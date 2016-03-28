(function(doc) {

	// Stat field validation
	if(document.querySelector('.js-stat')) {
		var myStatValidator = new StatValidator('.js-stat');
	}

	// Start graph
	if(document.querySelector('.js-graph--stats')) {
		window.graphdata = {
			"hp": 252,
			"spattack": 0,
			"spdefense": 252,
			"speed": 0,
			"defense": 4,
			'attack': 0
		};

		RadarChart.draw(".js-graph--stats", window.graphdata);
	}

})(document);