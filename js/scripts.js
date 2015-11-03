


//This function changes the pokemon selected image in form1

function change_pkmn_image(){

	//Variables
	var input_name = document.getElementById('pokemon_name_input');
	var pkmn_image = document.getElementById('pokemon-sprite');
	var input_id = document.getElementById('pokemon_id_input');
	var ajax_loader_path = './images/static/ajax-loader.gif';
	var default_image = './images/static/interrogation.gif';

	pkmn_image.setAttribute('src', ajax_loader_path);

	jQuery.ajax({
		type: 'POST',
		url : "ajax.php?action=changePkmnImage&pkmnName=" + input_name.value,
		success: function (result){

			if(!result)
				pkmn_image.setAttribute('src' , default_image);	
			else{
				pkmn_image.setAttribute('src', result.image);
				input_id.setAttribute('value', result.id);
			}

		}
	});
}

//This function check if any stat has been selected in form1

function check_selected_stats(label_name){

	//Variables
	var checkbox = document.getElementsByName('stats[]');
	var submit_button = document.getElementById('next_button');
	var selected_label = document.getElementById(label_name);
	var label_class = label_name + '-label'
	var length = checkbox.length;
	var control = false;

	//changing box style
	(selected_label.getAttribute('class') == 'half-label ' + label_class) ? selected_label.setAttribute('class', 'half-label ' + label_class + ' selected') : selected_label.setAttribute('class', 'half-label ' + label_class);


	for(i = 0; i < length; i++){

		if(checkbox[i].checked)
			control = true;

	}

	if(control)
		submit_button.removeAttribute('disabled');
	else
		submit_button.setAttribute('disabled', 'disabled');

}


//this function checks if everything is ready to start the training

function check_everything_ready(){

	//variables
	var evs_init_assigned_element = document.getElementById('evs_init').textContent;
	var evs_init_assigned_parse = parseInt(evs_init_assigned_element);
	var start_training_button = document.getElementById('start_training_button');
	var control1 = false;
	var control2 = false;

	if(evs_init_assigned_parse <= 510)
		control1 = true;

	console.log(control1);
	console.log(control2);

	if(control1)
		start_training_button.removeAttribute('disabled');				//Start training button is enabled if evs are equal to 510 and a stat option to start has been selected
	else
		start_training_button.setAttribute('disabled', 'disabled');

}


//this function checks if the evs assigned are correct

function check_total_evs(stat_to_add, stat_name){

	if(stat_to_add === '')
		stat_to_add = 0;

	//variables
	var evs_init_assigned = document.getElementById('evs_init');
	var h2_evs_displayed = document.getElementById('evs_form');
	var assigned_ivs = parseInt(evs_init_assigned.textContent);
	var parse_stats_to_add = parseInt(stat_to_add);
	var allinputs = document.getElementsByTagName('input');
	var length = allinputs.length;
	var total_evs_init = 0;

	//adding all the evs to the total amount
	for(i = 0; i < length; i++){
		if(allinputs[i].type == 'number'){
			evs_value = parseInt(allinputs[i].value);
			total_evs_init = total_evs_init + evs_value;
		}

	}

	console.log(total_evs_init);

	h2_evs_displayed.innerHTML = "Evs assigned ( <span id='evs_init'>" + total_evs_init + "</span> / <span id='evs_total'>510</span> )";

	if(total_evs_init > 510)
		h2_evs_displayed.setAttribute('class', 'training_title red');
	else	
		check_everything_ready();			//This function checks if everything is ready to start the training
	
}

//this function checks the assigned evs

function check_assigned_evs(stat_name){

	//variables
	var field_stat_total = document.getElementById(stat_name + '_total');
	var evs_form_title  = document.getElementById('evs_form');
	var alert_message = document.getElementById('alert-message-form');

	//if(field_stat_total.value === 0)

	if( isNaN(field_stat_total.value) || field_stat_total.value == ''){
		display_error_message('The value of the field has to be a number');
	}
	else if(field_stat_total.value < 0 || field_stat_total.value > 252)
		display_error_message('The value of the ' + stat_name + 'total is not valid');
	else{
		hide_error_message();
		check_total_evs(field_stat_total.value, stat_name);		//call the function to check if the evs assigned are correct	
	}
	

}


//this function add evs to the total amount in the training template

function add_evs(){

	//variables
	var current_evs_element = document.getElementById('evs_assigned');
	var total_evs_element = document.getElementById('evs_total');
	var evs_to_add_element = document.getElementById('evs_input');
	var current_evs = parseInt(current_evs_element.textContent);
	var total_evs = parseInt(total_evs_element.textContent);
	var evs_to_add = parseInt(evs_to_add_element.value);
	var message = document.getElementById('alert-message-form');


	if(evs_to_add <= 0)
		return false;

	var new_current_evs = current_evs + evs_to_add;

	if(new_current_evs >= 252 || new_current_evs >= total_evs){
		new_current_evs = total_evs;
		current_evs_element.innerHTML = new_current_evs;
		message.innerHTML = 'You have completed this stat training !';		//Completed
	}
	else{
		current_evs_element.innerHTML = new_current_evs;					//Not completed
		localStorage['last_add'] = evs_to_add;
	}

	
		
}
	
//This function reset the evs current value to 0

function reset_evs(){

	//variables
	var current_evs_element = document.getElementById('evs_assigned');
	var message = document.getElementById('alert-message-form');

	current_evs_element.innerHTML = 0;
	localStorage['last_add'] = 0;
	message = 'Current evs value reset to 0';

}

//This function undo the last evs add

function undo_evs(){

	//variables
	var current_evs_element = document.getElementById('evs_assigned');
	var current_evs = parseInt(current_evs_element.textContent);

	if(localStorage.getItem('last_add') === null || localStorage['last_add'] === 0)
		current_evs_element.textContent = 0;
	else{
		
		var new_undo_evs = current_evs - localStorage['last_add'];

		if(new_undo_evs < 0)
			new_undo_evs = 0;

		current_evs_element.textContent = new_undo_evs;
	}
	
}

function check_order_selected(stat){

	//variables
	var checkbox = document.getElementsByName('starting_stat');			//well, actually it is a radio button, but it was a checkbox when I started to write the script :D
	var label = document.getElementsByName('starting_stats_label');
	var selected_label =document.getElementById(stat);
	var checkbox_length = checkbox.length;
	var label_length = label.length;
	var selected_class = 'half-label '+ stat + '-label selected';

	console.log(selected_label);

	//reset all the labels original style
	for(i = 0; i < checkbox_length; i++){
	 	stat = checkbox[i].id.split('-');
	 	default_class = 'half-label ' + stat[0] + '-label';
	 	label[i].setAttribute('class', default_class);

	}

	//assigned the border class to the selected class
	selected_label.setAttribute('class', selected_class);
}

//This function displays an error message
function display_error_message(message){

	//variables
	var alert_message = document.getElementById('alert_message');
	var current_class = alert_message.getAttribute('class');

	//checking if there is a message displayed

	if(current_class === 'element hidden'){

		//message element empty

		//creating the text node element for the message
		textNode = document.createElement('p');
		textNode.setAttribute('class', 'red');
		textNode.setAttribute('id', 'error_message');
		content = document.createTextNode(message);
		textNode.appendChild(content);

		//modifing the content element class
		alert_message.setAttribute('class', 'element');

		//appending the messsage element text node
		alert_message.appendChild(textNode);

	}
	else{

		//message already displayed, only the content has to be changed

		error_message = document.getElementById('error_message');
		error_message.innerHTML = message;

	}

	
	
}

//This function hides an error message
function hide_error_message(){

	//variables
	var alert_message_text = document.getElementById('alert_message');
	var current_class = alert_message_text.getAttribute('class');

	//checking if there is any error message displayed

	if(current_class !== 'element hidden'){

		//destroy the text node
		textNode = document.getElementById('error_message');
		alert_message_text.removeChild(textNode);

		//hide the text message
		alert_message_text.setAttribute('class', 'element hidden');
	}
}

//This function changes the input border color
function input_error(id){

	//variables
	var input = document.getElementById(id);
	var current_class = input.getAttribute('class');

	//creating the new class atribute
	new_class = current_class + ' ' + 'border_red';

	//adding the new class
	input.setAttribute('class', new_class);
}

//This function removes the border color class for an input
function remove_input_class(id){
	
}