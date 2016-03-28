var StatValidator = (function(doc) {

	// Each stat field
	var Stat = function(el, wrap) {
		this.el = el;
		this.wrap = wrap;
		this.el.addEventListener('focus', this.statFocus.bind(this));
		this.el.addEventListener('change', this.statChange.bind(this));
		this.el.addEventListener('blur', this.statBlur.bind(this));
	};

	Stat.prototype.statFocus = function(){
		this.wrap.statSetMax();
		if(!this.el.value) {
			if((this.wrap.maxEvs - this.wrap.statTotal()) > 252 ) this.el.value = 252;
			else this.el.value = (this.wrap.maxEvs - this.wrap.statTotal());
		}
		if(window.graphdata) {
			window.graphdata[this.el.name] = parseInt(this.el.value, 10);
			RadarChart.draw(".js-graph--stats", window.graphdata);
		}
		else console.log(this.el.value);
	};

	Stat.prototype.statBlur = function() {
		if(this.el.value == 0) this.el.value = '';
		else {
			if(parseInt(this.el.value, 10) > this.el.max) this.el.value = this.el.max;
			this.el.value = Math.ceil(parseInt(this.el.value)/4.0) * 4;
		}
	};

	Stat.prototype.statChange = function() {
		this.el.value.replace(/[^0-9]/, '');

		if(window.graphdata) {
			window.graphdata[this.el.name] = parseInt(this.el.value, 10);
			RadarChart.draw(".js-graph--stats", window.graphdata);
		}
	};
	

	// Stat wrapper or collection
	var StatWrap = function(selector) {
		this.maxEvs = 508;
		this.statNodes = document.querySelectorAll(selector);
		this.statObjects = [];
		this.graphData = [
		  {
		    className: 'graph--evs', // optional can be used for styling
		    axes: [
		      { axis: "strength", value: 13, yOffset: 10}, 
		      { axis: "intelligence", value: 6}, 
		      { axis: "charisma", value: 5},  
		      { axis: "dexterity", value: 9},  
		      { axis: "luck", value: 2, xOffset: -20}
		    ]
		  }
		];
	
		for(var i=0; i < this.statNodes.length; i++) {
			this.statObjects.push(new Stat(this.statNodes[i], this));
		}
	};

	StatWrap.prototype.statSetMax = function() {
		var commonMax = parseInt(this.maxEvs - this.statTotal());
		for(var i=0; i < this.statNodes.length; i++) {
			var statValue = 0;
			if(this.statNodes[i].value) statValue = parseInt(this.statNodes[i].value, 10);
			var maxPossible = commonMax + statValue;

			if(maxPossible > 252) this.statNodes[i].max = 252;
			else this.statNodes[i].max = maxPossible;
		}
	}

	StatWrap.prototype.statTotal = function() {
		var total = 0;
		for(var i=0; i < this.statNodes.length; i++) {
			if(this.statNodes[i].value) {
				this.statNodes[i].value = Math.ceil(parseInt(this.statNodes[i].value)/4.0) * 4;
				total += parseInt(this.statNodes[i].value, 10);
			}
		}
		console.log(total);

		return total;
	}

	return StatWrap;
})(document);