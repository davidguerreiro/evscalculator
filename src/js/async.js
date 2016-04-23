(function(doc, w) {

	// Stat field validation
	if(document.querySelector('.js-stat')) {
		var myStatValidator = new StatValidator('.js-stat');
	}

	// Start graph
	if(document.querySelector('.js-graph--stats')) {
		if (typeof w.graphdata === 'undefined') {
			w.graphdata = {
				"hp": 0,
				"spattack": 0,
				"spdefense": 0,
				"speed": 0,
				"defense": 0,
				'attack': 0
			};
		}

		var id = '.js-graph--stats';
		var chart = RadarChart.chart();
		var options = {};
		options.w = parseInt(d3.select(id).style("width"));
    	options.h = parseInt((options.w*3)/4);
		var cfg = chart.config(options);
		var svg = d3.select(id).append('svg')
		  .attr('width', options.w)
		  .attr('height', options.h);

		function resize() {
	        // update width  
		    if(document.documentElement.clientWidth<1000) return;
	        options.w = parseInt(d3.select(id).style("width"));
	        options.h = parseInt((options.w*3)/4);

	        chart = RadarChart.chart().config(options);
	        cfg = chart.config();

	        d3.select(id).select('svg').remove();
	        svg = d3.select(id).append('svg')
			  .attr('width', options.w)
			  .attr('height', options.h);

			w.updateGraph();
	    }

	    d3.select(window).on('resize', resize); 

		w.updateGraph = function render() {
		  var spread = svg.selectAll('g.spread').data(
		    [
		      createStatData(w.graphdata)
		    ]
		  );
		  spread.enter().append('g').classed('spread', 1);
		  spread
		    .call(chart);
		}
		w.updateGraph();

	}

})(document, window);