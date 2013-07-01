$.getJSON("config/sections.json",function(data){
	Model.loadAll(data);
	View.loadEvents(DefaultComponents);
	View.loadAll(data);
});
