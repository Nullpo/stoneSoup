Model = {
	loadAll: function(sections){
		for(var i in sections){
			elem = sections[i];
			
			if(elem.controller)
				Model[elem.innerName] = Model.genericGetter(elem.innerName, elem);
		};
	},
	genericGetter : function(name, elem){
		return function(callback){
			$.getJSON(elem.controller, function(data){
				Cache[name] = {
					response: data,
					date: new Date()
				};
				callback(data);
			});
		}
	},
	NullTalk : function(){
		this.name = "No hay charlas";
		this.id = null;
	}
}

Cache = {
	
}
