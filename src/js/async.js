(function(doc) {

	// Stat field validation
	if(document.querySelector('.js-stat')) {
		var myStatValidator = new StatValidator('.js-stat');
	}

	// Start graph
	if(document.querySelector('.js-graph--stats')) {
		window.graphdata = [
		  {
		    className: 'graph--target', // optional can be used for styling
		    axes: [
		      { axis: "HP", value: 13, yOffset: 10 }, 
		      { axis: "Speed", value: 3 },
		      { axis: "Special defense", value: 2, xOffset: -20 },
		      { axis: "Special attack", value: 9 },  
		      { axis: "Defense", value: 5 },  
		      { axis: "Attack", value: 6 }
		    ]
		  }
		];

		RadarChart.draw(".js-graph--stats", window.graphdata);
	}

})(document);