Utils = {
	_createPage: function(html, page) {
	    //append the new page onto the end of the body
	    if(page == null)
	    	return;
	    $('#theBody').append(html);
	    $("#"+page).one( 'pagecreate', $.mobile._bindPageRemove );
	}
}

ListComponent = { // TODO: Inherit from CollapsableComponent
	func: function(section){
		return function(evt){
			var theContent = $("[id='" + this.id + "Content']");
			theContent.empty();
			var name = this.id
			var tpl = View.tpl[name][0];
			var tplPage = View.tpl[name][1];
			// Obtengo las proximas charlas.
			Model[name](function(talks){
				// Creo las paginas
				ListComponent.createListOfTalks(talks,tplPage,tpl,name+"Ul",name+"Content")
			});
		};
	},
	component:function(section){
		var div = $(Mustache.render(View.tpl.section,section));
		div.collapsible({refresh:true});
		div.bind('expand', View.components[section.type].func(section));
		return div;
	},
	createListOfTalks:function(talks,tplPage,tpl,idUl,idContent){
			var viewTalks = [];
			for(var i in talks){
				id = null;
				
				id = "talkPage" + talks[i].id;
				
				//clonning attribute by atribute in javascript is too slow :(
				var viewTalk = JSON.parse(JSON.stringify(talks[i]));
				viewTalk.timeDateInit = talks[i].timeDateInit.date.substring(11,16);
				viewTalk.timeDateFinish = talks[i].timeDateFinish.date.substring(11,16);
				viewTalks.push(viewTalk);

				Utils._createPage(Mustache.render(tplPage, viewTalk),id);

			}
					
			var obj = { 'data' : viewTalks, 'id' : idUl	};

			$("#" + idContent).html( Mustache.render(tpl, obj) );
			$("#" + idUl ).listview();
	}
}

CollapsableHtmlComponent = {
	expand : function(section){
		return function(evt){
			var name = this.id
			var tpl = View.tpl[name][0];
			$("#" + name + "Content").html(Mustache.render(tpl,section));
		}
	},
	component: function(section){
		var div = $(Mustache.render(View.tpl.section,section));
		div.collapsible({refresh:true});
		div.bind('expand', this.expand(section));
		return div;
	}
}

ImageComponent = {
	component:function(section){
		return "<img src='img/" + section.image + "'style='max-width: 100%;height: auto;width: auto;' />";
	}
}

PicasaComponent = { //TODO : all ;)
	component:function(section){
		return "<img src='img/" + section.views[0] + "'style='max-width: 100%;height: auto;width: auto;' />";
	}
}

PlainComponent = {
	component:function(section){
		return div = $(Mustache.render(View.tpl.section,section));
	}
}


OpenStreetMapComponent = {
	component: function(section){
		var div = $("<div id='mapContainer"+section.innerName+"'>");
		div.attr("style","width:95%;height:280px;margin:0px auto;margin-bottom:30px"); //TODO: pass to class
		$(document).ready(function(){
			var name = section.innerName
			var tpl = View.tpl[name][0];
			div.prepend($(Mustache.render(tpl,section)));
		});
		return div;
	}
};

TextComponent = Object.create(CollapsableHtmlComponent,{
	expand: {
		value: function(section){
			return function(evt){
				var name = section.innerName
				$("#" + name + "Content").html(section.html);
			}
		}
	}
});

DefaultComponents = {
	"list": ListComponent,
	"htmlTemplate": CollapsableHtmlComponent,
	"plain" : PlainComponent,
	"image" : ImageComponent,
	"map" : OpenStreetMapComponent,
	"html" : TextComponent,
	addComponent : function(name,component){
		if(DefaultComponents[name])
			throw "Component " + name + " already exists";
		else
			DefaultComponents[name] = component;
	}
};