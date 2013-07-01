stoneSoup
=========

Aplicación movil para mostrar la agenda de charlas en un evento.

TODO: Create README_EN.md for english users.


Demo
----

http://mycrm.p.ht/stoneSoup/
(A veces se cae, es un hosting gratuito!)

Requerimientos
--------------

- PHP 5.3 (La aplicación usa Clousures).
- Cualquier servidor que corra PHP.
- Conexión a Internet (por ahora usa CDN).

- Si se quiere usar openStreetsMap, necesitan una API KEY.


Servidor probado en:

- [x] PHP 5.3
- [x] PHP 5.3.10

- [x] Nginx 1.1.19
- [x] Apache 2.4.4
- [x] Apache 2.2.14

Cliente probado en:
- [x] Android 2.3


Instalación
-----------

Copiar la carpeta stoneSoup a la carpeta pública del servidor.

e.g.: cp stoneSoup/ /usr/share/nginx/www


¿Por qué se llama stoneSoup?
----------------------------

Por la fábula de la sopa de piedra (http://es.wikipedia.org/wiki/Sopa_de_piedra), me pareció un buen ejemplo
de como un conjunto de personas que poseen sus propios intereses, encuentran una excusa para apoyarse
entre todos y convertirse al fin en una comunidad unida... y justamente de eso se trata el software libre!


Configuración básica
--------------------

El archivo talks.json posée toda la información del evento. El mismo debe ser 100% json compliant.
Ante cualquier duda, se puede probar aquí: http://jsonformatter.curiousconcept.com/

Su formato es:

    [                           # Listado de salas.
    
       {                        # Objeto "Sala" (Repetir una o más veces).
          "id":0,                 # ID de la sala
          "name":"Sala 1",        # Nombre de la sala
          "talks":[               # Listado de Charlas que habrá en la sala 
          
          	{                     # Objeto "Charla" (Repetir cero o más veces)
              "name":"Mesa redonda: ¿Que medialunas te gustan?",      # Nombre de la charla
              "timeDateInit":{            
                 "date":"2013/07/01 04:00:00",                        # Fecha de inicio: YYYY/MM/dd hh:mm:ss
                 "timezone_type":3,                                   
                 "timezone":"America\/Argentina\/Buenos_Aires"
              },
              "timeDateFinish":{                                      # Fecha de finalización: YYYY/MM/dd hh:mm:ss
                 "date":"2013/07/01 04:59:00",
                 "timezone_type":3,
                 "timezone":"America\/Argentina\/Buenos_Aires"
              },
              "description":"Lorem ipsum dolor sit amet2",            # Descripción de la charla
              "id":1,                                                 # ID de la charla
              "dataUrls":""                                           # <future> URLs de archivos adjuntos.
            }
            
          ]
       }
       
    ] 


El archivo sections.json posée las diferentes secciónes que tiene la pantalla principal. 
Si lo crée necesario, puede eliminar la sección que desée.
El orden de las secciónes está dado por el campo "id" de cada sección.



Uso de OpenStreetMaps
---------------------

Para insertar un mapa en la pantalla de inicio, debe asegurarse que en el archivo sections.json exista un objeto
que posea 
 - el campo "type":"map"
 - el campo "views":["mapTpl.mustache"]
 - el campo "mapData".

 
El campo "mapData" es un objeto que tiene el siguiente formato:

    "mapData" : {
       "position":{
          "longitude": <float: Longitud del punto central>,
          "latitude": <float: Latitud del punto central>,
        },
       "zoom": <int: nivel de zoom>,
       "polygons":[         #Listado de Poligonos a dibujar
       
          {                 #Repetir cero o más veces.
             "points": [
                {"longitude":-34.5845053, "latitude":-58.3984702},    #
                {"longitude":-34.584785, "latitude":-58.3981282},     # Puntos
                {"longitude":-34.5845133, "latitude":-58.3977805},    # Del
                {"longitude": -34.5843965, "latitude":-58.3976309},   # Poligono
                {"longitude": -34.584109, "latitude":-58.3979417},    #
                {"longitude": -34.5845053, "latitude":-58.3984702}    #
             ],
             "color": "green",
             "fillColor": "#0f3",
             "fillOpacity": 0.5
          }
          
       ],
       "circles":[      # Listado de circulos a dibujar (Util para señalar la entrada)
          {             # Repetir cero o más veces
             "center":{
                "longitude":<float:Longitud del punto central>,  
                "latitude":<float:Latitud del punto central>
             },
             "radius": 3,
             "color": "red",
             "fillColor": "#f03",
             "fillOpacity": 0.5
          }
       ]
    }
  







