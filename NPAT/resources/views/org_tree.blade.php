@extends('layouts.applayout')

@section('menu')
    @include('partials.navigationMenu')
@endsection
<meta charset="utf-8">
<style>

    .node {
        cursor: pointer;
    }

    .node circle {
        fill: #fff;
        stroke: steelblue;
        stroke-width: 1.5px;
    }

    .node text {
        font: 10px sans-serif;
    }

    .link {
        fill: none;
        stroke: #ccc;
        stroke-width: 1.5px;
    }

</style>
<body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.5/d3.min.js"></script>
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script>

    var margin = {top: 20, right: 500, bottom: 20, left: 120},
            width = 960 - margin.right - margin.left,
            height = 1500 - margin.top - margin.bottom;

    var i = 0,
            duration = 750,
            root;

    var tree = d3.layout.tree()
            .size([height, width]);

    var diagonal = d3.svg.diagonal()
            .projection(function (d) {
                return [d.y, d.x];
            });

    var svg = d3.select("body").append("svg")
            .attr("width", width + margin.right + margin.left)
            .attr("height", height + margin.top + margin.bottom)
            .append("g")
            .attr("transform", "translate(" + margin.left + "," + margin.top + ")");


    var data = {
        "firstName": "Rajesh",
        "lastName": "Kanna",
        "title": "CEO",
        "photo": "http://www.compassitesinc.com/wp-content/uploads/2016/04/Favicon.png",
        "children": [{
            "firstName": "Suneel",
            "lastName": "Sashtry",
            "title": "Head-Engineering",
            "photo": "http://www.compassitesinc.com/wp-content/uploads/2016/04/Favicon.png",
            "children": [{
                "firstName": "Suresh",
                "lastName": "S",
                "title": "SSE",
                "photo": "http://www.compassitesinc.com/wp-content/uploads/2016/04/Favicon.png",
                "children": [{
                    "firstName": "Arasu",
                    "lastName": "B",
                    "title": "Practice lead",
                    "photo": "http://www.compassitesinc.com/wp-content/uploads/2016/04/Favicon.png"
                }, {
                    "firstName": "Vijendra",
                    "lastName": "V",
                    "title": "Quality",
                    "photo": "http://www.compassitesinc.com/wp-content/uploads/2016/04/Favicon.png"
                }]
            }
            ]
        },
            {
                "firstName": "Ashok",
                "lastName": "Datla",
                "title": "Head-Engineering",
                "photo": "http://www.compassitesinc.com/wp-content/uploads/2016/04/Favicon.png",
                "children": [{
                    "firstName": "Balaji",
                    "lastName": "BVR",
                    "title": "SSE",
                    "photo": "http://www.compassitesinc.com/wp-content/uploads/2016/04/Favicon.png"
                }
                    , {
                        "firstName": "Mihr",
                        "lastName": "M",
                        "title": "SSE",
                        "photo": "http://www.compassitesinc.com/wp-content/uploads/2016/04/Favicon.png"
                    }]
            }


        ]
    };
    $.ajax({
        url: '/api/user-hierarchical/0',
        async: false,
        success: function (d) {
            data = d;
            console.log(data);
        },
        error: function (result) {
        }
    });
    console.log(data);
    root = data;
    root.x0 = 700 / 2;
    root.y0 = 0;

    function collapse(d) {
        if (d.children) {
            d._children = d.children;
            d._children.forEach(collapse);
            d.children = null;
        }
    }

    root.children.forEach(collapse);
    update(root);

    function update(source) {

        // Compute the new tree layout.
        var nodes = tree.nodes(root).reverse(),
                links = tree.links(nodes);

        // Normalize for fixed-depth.
        nodes.forEach(function (d) {
            d.y = d.depth * 180;
        });

        // Update the nodes…
        var node = svg.selectAll("g.node")
                .data(nodes, function (d) {
                    return d.id || (d.id = ++i);
                });

        // Enter any new nodes at the parent's previous position.
        var nodeEnter = node.enter().append("g")
                .attr("class", "node")
                .attr("transform", function (d) {
                    return "translate(" + source.y0 + "," + source.x0 + ")";
                })

                .on("click", click)
                .on("mouseover", function(d) {
                var g = d3.select(this); // The node
                    // The class is used to remove the additional text later
                    var info = g.append('text')
                            .classed('info', true)
                            .attr("x", function(d) { return d.children || d._children ? -10 : 40; })
                            .attr('y', -10)
                            .text(function(d){return  d.emp_id + ' ' + d.designationName});
                         })
                .on("mouseout", function() {
                    // Remove the info text on mouse out.
                    d3.select(this).select('text.info').remove();
                    });


        // add picture
        nodeEnter
                .append('defs')
                .append('pattern')
                .attr('id', function (d, i) {
                    return 'pic_' + d.name;
                })
                .attr('height', 60)
                .attr('width', 60)
                .attr('x', 0)
                .attr('y', 0)
                .append('image')
                .attr('xlink:href', function (d, i) {
                    console.log(d.photo);
                    return d.photo;
                })
                .attr('height', 60)
                .attr('width', 60)
                .attr('x', 0)
                .attr('y', 0);

        nodeEnter.append("circle")
                .attr("r", 1e-6)
                .style("fill", function (d) {
                    return d._children ? "lightsteelblue" : "#fff";
                });

        var g = nodeEnter.append("g");

        nodeEnter.append("svg:text")
                .attr("x", function(d) { return d.children || d._children ? -10 : 10; })
                .attr("dy", ".35em")
                .attr("text-anchor", function(d) { return d.children || d._children ? "end" : "start"; })
                .text(function(d) { return d.name; })
                .style("fill-opacity", 1e-6);

        // Transition nodes to their new position.
        var nodeUpdate = node.transition()
                .duration(duration)
                .attr("transform", function (d) {
                    return "translate(" + d.y + "," + d.x + ")";
                });

        nodeUpdate.select("circle")
                .attr("r", 6)
                .style("fill", function (d, i) {
                    return 'url(#pic_' + d.name + ')';
                });

        nodeUpdate.selectAll("text")
                .style("fill-opacity", 1);

        // Transition exiting nodes to the parent's new position.
        var nodeExit = node.exit().transition()
                .duration(duration)
                .attr("transform", function (d) {
                    return "translate(" + source.y + "," + source.x + ")";
                })
                .remove();

        nodeExit.select("circle")
                .attr("r", 1e-6);

        nodeExit.select("text")
                .style("fill-opacity", 1e-6);

        // Update the links…
        var link = svg.selectAll("path.link")
                .data(links, function (d) {
                    return d.target.id;
                });

        // Enter any new links at the parent's previous position.
        link.enter().insert("path", "g")
                .attr("class", "link")
                .attr("d", function (d) {
                    var o = {x: source.x0, y: source.y0};
                    return diagonal({source: o, target: o});
                });

        // Transition links to their new position.
        link.transition()
                .duration(duration)
                .attr("d", diagonal);

        // Transition exiting nodes to the parent's new position.
        link.exit().transition()
                .duration(duration)
                .attr("d", function (d) {
                    var o = {x: source.x, y: source.y};
                    return diagonal({source: o, target: o});
                })
                .remove();

        // Stash the old positions for transition.
        nodes.forEach(function (d) {
            d.x0 = d.x;
            d.y0 = d.y;
        });
    }

    // Toggle children on click.
    function click(d) {
        if (d.children) {
            d._children = d.children;
            d.children = null;
        } else {
            d.children = d._children;
            d._children = null;
        }
        update(d);
    }

</script>