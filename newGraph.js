function createGraph(jsonData,tabDiv)
{

  jsonData.sort(function(a,b){if(a.source>b.source){return 1;}
  else if(a.source<b.source){return-1;}
  else{
    if(a.target>b.target){return 1;}

    if(a.target<b.target) {
      return-1;
    }

    else{
      return 0;
    }
  }
});

  // jsonData.sort puts all similar sources and targets in order and in groups, this is needed 
  // for the loop below because it checks a previous object for similarity

for(var i=0;i<jsonData.length;i++) {
  if(i!=0&&jsonData[i].source==jsonData[i-1].source&&jsonData[i].target==jsonData[i-1].target) {
    jsonData[i].linknum=jsonData[i-1].linknum+1;
  }
  else
  {
    jsonData[i].linknum=1;
  }
}
// attaches a number to how many links (source target pairs) exist to the jsonData

    var nodes={};
    jsonData.forEach(function(link){
      link.source = nodes[link.source] || (nodes[link.source]={name:link.source});
      link.target = nodes[link.target] || (nodes[link.target]={name:link.target});
      
    });
//stores sources and targets into nodes

    console.log("NODES "+nodes);


    var count=0;
    for(var k in nodes) {
      if(nodes[k].name.indexOf("mir") > -1 || nodes[k].name.indexOf("miR") > -1 || nodes[k].name.indexOf("MIR") > -1 || nodes[k].name.indexOf("let") > -1) {
        nodes[k]["group"] = 0;
      }
      else {
        nodes[k]["group"] = 1;
      }
      count++;
      console.log("nodes[k] #"+count+" "+nodes[k].name);
      console.log("nodes[k][group] #"+count+" "+nodes[k]["group"]);
    }
//assings nodes to groups, sources and targets, the group number is used later to determine its color

    var arrows=[];

    for(var i=0;i<jsonData.length;i++){arrows[i]=jsonData[i].type;};
   
    //stores type data in arrows , taken from the jsonData

    /************************************************ */
    var up_regulated=[];
    var down_regulated=[];
    var unspecified=[];
    for(var i=0; i<arrows.length; i++){
      if(arrows[i]=="Up-regulated")
        up_regulated[i]=arrows[i];
        
      
      else if(arrows[i]=="Down-regulated")
        down_regulated[i]=arrows[i];
        
      
      else
        unspecified[i]=arrows[i];
      
      
    }
    //separates types into up or down regulated and stores them in individual arrays
      
    
   
    /************************************************ */
    //var w=1000,h=800;
    var w=1200,h=1500;

    //gives the size of the graph

    var w = document.getElementById("graph").offsetWidth;

    var force=d3.layout.force()
    .nodes(d3.values(nodes))
    .links(jsonData)
    .size([w,h])
    .linkDistance(300)
    .charge(-1000)
    .on("tick",tick)
    .start();

//build the force layout object, attaches nodes, links, the size of the graph window, the 
//distance between the nodes
//the charge, which indicates how repelled nodes are from eachother

    var color = d3.scale.category10();

    //grabs a set of colors that will be referenced for node color

    var svg=d3.select(tabDiv)
    .append("svg:svg")
    .attr("width",w)
    .attr("height",h);
    
// append graph to the provided div , appends a svg:svg this is essentially a container for whats
// going to be in the graph, width and height are applied to the container
    
    svg.append("svg:defs").selectAll("marker")
    .data(down_regulated)
    .enter().append("svg:marker")
    .attr("id",String).attr("viewBox","0 -5 10 10")
    .attr("refX",17)
    .attr("refY",0)
    .attr("markerWidth",13)
    .attr("markerHeight",26)
    .attr("orient","auto")
    .attr("markerUnits","userSpaceOnUse")
    .append("svg:path")
    .attr("d","M-100,-50L100,0L0,200"); 

    //a bar marker is  built here, it is attached to the downregulated types from the jsonData
    //the maker is appended to the same svg that the nodes are appended to (defs)      
    //a viewbox is appended, this gives the size of the window for the marker, the marker
    //scales to this window size, so increasing it will increase the marker size automatically
    //refx gives the position of the marker along the line
    //refy gives the position of the maarker perpendicular to the line (moves it the left of right of the line) 
    //markerWidth and markerHieght give the size of the marker
    //makerUnits makes it so that the marker does not inherit the stroke-width of the line from the path SVG
    //so line thickness has no effect on the marker
    //finally a path is appended and the coordinates of the path are given 
  
    svg.append("svg:defs").selectAll("marker")
    .data(up_regulated)      // Different link/path types can be defined here
    .enter().append("svg:marker")    // This section adds in the arrows
    .attr("id", String)
    .attr("viewBox", "0 -5 10 10")
    .attr("refX", 15)
    .attr("refY", 0)
    .attr("markerWidth", 15)
    .attr("markerHeight", 15)
    .attr("orient", "auto")
    .attr("markerUnits","userSpaceOnUse")
    .append("svg:path")
    .attr("d", "M0,-5L10,0L0,5");

    //same as above except markers are attached to upregulated data and 
    //the path that is drawn is an arrow

    var path=svg.append("svg:g").selectAll("path")
    .data(force.links())
    .enter().append("svg:path")
    .attr("class",function(d){return"link "+d.type;})
    .attr("marker-end",function(d){return"url(#"+d.type+")";})
    .attr("stroke-width", function(d){return d.numb *5;});
  //sets path width to be dynamic based on number of associations
  //note, arrows/plungers are considered paths, so are effected;

    var circle=svg.append("svg:g").selectAll("circle")
    .data(force.nodes())
    .enter().append("svg:circle")
    .attr("r",8)
    .style("fill", function(d) {
      return color(d.group);
    })
    .call(force.drag);

    //builds circles on node locations, the node locations are grabbed from the data(force.nodes)
    //the radius is set to 8
    //the color of the nodes is set to color(d.group) d.group is the group number that was attached earlier
    // nodes will have different colors if they are a source vs a target
    //force.drag allows the nodes to be movable by clicking and dragging them

    var text=svg.append("svg:g").selectAll("g")
    .data(force.nodes())
    .enter().append("svg:g");
    text.append("svg:text")
    .attr("x",8)
    .attr("y",".31em")
    .attr("class","shadow")
    .text(function(d){return d.name;});

    //finds the g svg that was declared for nodes and attaches text to it. 
    // a text element in d3 is just declared with  .append("svg:g")
    // the size of the txt is declared in x and dy 

    text.append("svg:text")
    .attr("x",8).attr("y",".31em")
    .text(function(d){return d.name;});

//may be an error, txt appears to be applied more than once, not sure if needed 

    function tick() {
        path.attr("d",function(d)
        {
          var dx=d.target.x-d.source.x,dy=d.target.y-d.source.y,
          dr=1000/d.linknum;
          return"M"+d.source.x+","+d.source.y+"A"+dr+","+dr+" 0 0,1 "+d.target.x+","+d.target.y;
        });

      circle.attr("transform",function(d){return"translate("+d.x+","+d.y+")";});

      text.attr("transform",function(d){return"translate("+d.x+","+d.y+")";});
    }
  }

//tick is applied to the force object, and it governs the following properties
// determines the coordinates of the nodes, grabs the linkum property of the newLinkData and is modified by 1000 to create dynamic arcs
// the node and line placement are returned 
//the circle and text attributes move the nodes and text from their original coordinate(this is done because of "translate"). 
// when the graph is built nodes will be dynamically moved based on their links(child relations), depending on the d.x, d.y
//the (coordinate of the node) the linked nodes will move the exact amount of space that the parent node moves 