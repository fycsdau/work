var t = new Trianglify();
var button = document.getElementById('button'),
saveas = document.getElementById('saveas'),
subtractnoise = document.getElementById('subtractnoise'),
addnoise = document.getElementById('addnoise'),
noise_display = document.getElementById('noise'),
subtractcellsize = document.getElementById('subtractcellsize'),
addcellsize = document.getElementById('addcellsize'),
cellsize_display = document.getElementById('cellsize'),
subtractcellpadding = document.getElementById('subtractcellpadding'),
addcellpadding = document.getElementById('addcellpadding'),
cellpadding_display = document.getElementById('cellpadding'),
toggle_controls = document.getElementById('togglecontrols'),
controls = document.getElementById('controls'),
prevheight = height();

button.onclick = function() {
    recolor();
    redraw();
    return false;
};
addnoise.onclick = function() {
    noise(0.2);
    return false;
};
subtractnoise.onclick = function() {
    noise(-0.2);
    return false;
};
addcellsize.onclick = function() {
    cellsize(10);
    return false;
};
subtractcellsize.onclick = function() {
    cellsize(-10);
    return false;
};
addcellpadding.onclick = function() {
    cellpadding(5);
    return false;
};
subtractcellpadding.onclick = function() {
    cellpadding(-5);
    return false;
};
toggle_controls.onclick = function() {
    toggle_controls.innerHTML = toggleClass(controls, "hidden") ? "show controls":"hide controls";
            window.setTimeout(heightChange, 0); //see if the height changed after render and redraw if it did
            return false;
        };

        window.onresize = function() {
        	redraw();
        };

        function heightChange() {
        	if (height() != prevheight) {
        		console.log("height changed from "+prevheight+" to "+height());
        		prevheight = height();
        		redraw();
        	}
        }

        function redraw() {
        	console.log("drawing "+document.body.clientWidth+"x"+height());
        	var pattern = t.generate(document.body.clientWidth, height());
        	document.body.setAttribute('style', 'background-image: '+pattern.dataUrl);
        	saveas.setAttribute('href', pattern.dataUri);
        	noise_display.innerHTML = t.options.noiseIntensity.toFixed(1);
        	cellsize_display.innerHTML = t.options.cellsize.toFixed(0);
        	cellpadding_display.innerHTML = t.options.cellpadding.toFixed(0);
        }

        function recolor() {
        	t.options.x_gradient = Trianglify.randomColor();
        	t.options.y_gradient = t.options.x_gradient.map(function(c){return d3.rgb(c).brighter(0.5);});
        }

        function noise(i) {
        	i += t.options.noiseIntensity;
        	if (i >= 0 && i <= 1) {
        		t.options.noiseIntensity = i;
        		redraw();
        	} else if (i < 0) {
        		t.options.noiseIntensity = 0;
        		redraw();
        	}
        }

        function cellsize(i) {
        	i += t.options.cellsize;
        	if (i >= 0) {
        		t.options.cellsize = i;
        		t.options.bleed = i;
        		if (t.options.cellpadding >= t.options.cellsize/2) {
        			t.options.cellpadding = 5*Math.floor((t.options.cellsize/2 - 1)/5);
        		}
        		redraw();
        	}
        }

        function cellpadding(i) {
        	i += t.options.cellpadding;
        	if (i >= 0  && i < t.options.cellsize/2) {
        		t.options.cellpadding = i;
        		redraw();
        	}
        }

        function height() {
        	return Math.max(
        		document.body.scrollHeight, document.documentElement.scrollHeight,
        		document.body.offsetHeight, document.documentElement.offsetHeight,
        		document.body.clientHeight, document.documentElement.clientHeight
        		);
        }

        function toggleClass(el, className) {
        	if (el.classList) {
        		return el.classList.toggle(className);
        	} else {
        		var classes = el.className.split(' ');
        		var existingIndex = classes.indexOf(className);

        		if (existingIndex >= 0)
        			classes.splice(existingIndex, 1);
        		else
        			classes.push(className);

        		el.className = classes.join(' ');
        		return existingIndex >= 0;
        	}
        }