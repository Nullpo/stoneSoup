View = {
		tpl : {
			"section" : "files/templates/section.mustache"
		},
		components : {},
		loadEvents:function(component){
			View.components = component;
		},
		loadAll:function(sections){
			View._countSections = sections.length;

			$.get(View.tpl["section"],function(data){
				View.tpl["section"] = data;
				View.loadTemplates(sections);
			});
		},
		loadTemplates : function(sections){
			$.each(sections, function(i,section){
				View.tpl[section.innerName] = [];
				if(section.views){
					$.each(section.views, function(i,elem){
						$.get("files/templates/"+elem,View._loadTemplate(section));
					});
				} else {
					View.eventLoadedAllTemplatesOf(section);
				}
			});
		},
		_loadTemplate: function(section){
			return function(data){
				View.tpl[section.innerName].push(data);
				/**
				* _loadTemplate loads a template :P.
				* If all templates of this section are loaded, then
				* throw the LoadedAllTemplatesOf event.
				*/

				if(section.views.length == View.tpl[section.innerName].length){
					View.eventLoadedAllTemplatesOf(section);
						
				}
			}
		},
		eventLoadedAllTemplatesOf: function(section){
			/**
			*
			* save the Loaded template.
			*
			*/
			View._loadedTemplates.push (section);
			/**
			*
			* If all the section's templates are loaded, throw
			* the LoadedAllComponents event.
			*
			*/
			if(View._loadedTemplates.length == View._countSections){
				View._loadedTemplates.sort(function(a,b){
					return a.id-b.id; 
				});
				console.log(View._loadedTemplates);
				View.eventLoadedAllComponents();
			}
		},
		eventLoadedAllComponents: function(){
			$.each(View._loadedTemplates,function(i,data){
				if(data){
					View.loadComponent(data);
				}
			});
		},
		loadComponent : function(section){
			var div = View.components[section.type].component(section);
			$("#mainContent").append(div);
		},
		_loadedTemplates : [],
		_countSections : 0
	};