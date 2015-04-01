<!DOCTYPE html>
<html>
<head>
    <link href="/css/mobile.css" rel="stylesheet" type="text/css" />
    <link href="/css/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700,300italic" rel="stylesheet" type="text/css" />
    <meta name="viewport" content="user-scalable=no" />
    <link rel="apple-touch-icon-precomposed" href="/iphone-icon.png"/>
    <link rel="apple-touch-icon" href="/iphone-icon.png"/>
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <script type="text/javascript" src="/js/prototype.js"></script>
    <script type="text/javascript" src="/js/swipeable.js"></script>
    <title>Health Challenge</title>
</head>
<body>
<style type="text/css">
    body {
        padding: 0;
    }
#sidebar-left {
    width:95%;
    position: fixed;
    left: -95%;

    background: rgb(84,84,84); /* Old browsers */
    background: -moz-linear-gradient(left,  rgba(84,84,84,1) 0%, rgba(84,84,84,1) 93%, rgba(66,66,66,1) 100%); /* FF3.6+ */
    background: -webkit-gradient(linear, left top, right top, color-stop(0%,rgba(84,84,84,1)), color-stop(93%,rgba(84,84,84,1)), color-stop(100%,rgba(66,66,66,1))); /* Chrome,Safari4+ */
    background: -webkit-linear-gradient(left,  rgba(84,84,84,1) 0%,rgba(84,84,84,1) 93%,rgba(66,66,66,1) 100%); /* Chrome10+,Safari5.1+ */
    background: -o-linear-gradient(left,  rgba(84,84,84,1) 0%,rgba(84,84,84,1) 93%,rgba(66,66,66,1) 100%); /* Opera 11.10+ */
    background: -ms-linear-gradient(left,  rgba(84,84,84,1) 0%,rgba(84,84,84,1) 93%,rgba(66,66,66,1) 100%); /* IE10+ */
    background: linear-gradient(to right,  rgba(84,84,84,1) 0%,rgba(84,84,84,1) 93%,rgba(66,66,66,1) 100%); /* W3C */
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#545454', endColorstr='#424242',GradientType=1 ); /* IE6-9 */

    color: #fff;
}

.sidebar ul {
    margin: 0;
    padding: 0;
    border-bottom:#262b2e 2px solid;
    margin-bottom:2em;
}

.sidebar li {
    list-style: none;
    border-bottom:#32373a 2px solid;
    border-top:#262b2e 2px solid;
    height: 2.2em;
    line-height: 2.2em;
    font-size: 1.5em;
    margin:0;
    padding:0 0 0 0.5em;
    font-weight: 400;
    text-shadow: 0.1em 0.1em #32373a;
    background: #2a2f33 url(/img/sidebar-bkg.png);
    transition: background 0.1s;
}

.sidebar li.active-item {
    background: #475057 url(/img/sidebar-bkg.png);
}

.sidebar li i {
    display:block;
    float:left;
    width:1.5em;
    position:relative;
    top:0.6em;

}
#sidebar-right {
    width:95%;
    position: fixed;
    right: -96%;

    background: rgb(84,84,84); /* Old browsers */
    background: -moz-linear-gradient(left,  rgba(84,84,84,1) 0%, rgba(84,84,84,1) 93%, rgba(66,66,66,1) 100%); /* FF3.6+ */
    background: -webkit-gradient(linear, left top, right top, color-stop(0%,rgba(84,84,84,1)), color-stop(93%,rgba(84,84,84,1)), color-stop(100%,rgba(66,66,66,1))); /* Chrome,Safari4+ */
    background: -webkit-linear-gradient(left,  rgba(84,84,84,1) 0%,rgba(84,84,84,1) 93%,rgba(66,66,66,1) 100%); /* Chrome10+,Safari5.1+ */
    background: -o-linear-gradient(left,  rgba(84,84,84,1) 0%,rgba(84,84,84,1) 93%,rgba(66,66,66,1) 100%); /* Opera 11.10+ */
    background: -ms-linear-gradient(left,  rgba(84,84,84,1) 0%,rgba(84,84,84,1) 93%,rgba(66,66,66,1) 100%); /* IE10+ */
    background: linear-gradient(to right,  rgba(84,84,84,1) 0%,rgba(84,84,84,1) 93%,rgba(66,66,66,1) 100%); /* W3C */
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#545454', endColorstr='#424242',GradientType=1 ); /* IE6-9 */
    color: #fff;
}

#sidebar-right p {
    font-size: 1.5em;
}

#sidebar-right .sidebar-inner {
    padding:1em;
}
.sidebar {
    transition: all 0.2s cubic-bezier(.69,1,.88,1);
    top:0;
    height:100%;
}
.sidebar-inner {
    border: #a1a1a1 1px solid;
    height: 100%;
    background:url(/img/sidebar-bkg.png);
}
#sidebar-left.sidebar-inner {
    border-radius: 0 1em 1em 0;
}
#sidebar-right.sidebar-inner {
    border-radius: 1em 0 0 1em;
}
#sidebar-left.active {
     left:0;
 }
#sidebar-right.active {
    right:0;
}
#container {
    position: relative;

}
h1 {
    text-align: center;
    font-weight: 800;
}

</style>
<script type="text/javascript">
    function toggleSidebar(which)
    {
        if (which =='sidebar-left' && $('sidebar-right').hasClassName('active')) {
            $('sidebar-right').removeClassName('active');
        }
        if (which =='sidebar-right' && $('sidebar-left').hasClassName('active')) {
            $('sidebar-left').removeClassName('active');
        }
        if ($(which).hasClassName('active')) {
            $(which).removeClassName('active');
        }
        else {
            $(which).addClassName('active');
        }
    }

    function clearSidebars()
    {
        $('sidebar-left').removeClassName('active');
        $('sidebar-right').removeClassName('active');
    }

    function log(text)
    {
        $('log').innerHTML = text;
    }

    Event.observe(window, 'load', function() {
        var swipeMain = $('main');
        var swipeMainObj = new Swipeable(swipeMain);
        var w = $('main').getWidth();

        swipeMain.observe("swipe:left",function() {
            p = swipeMainObj.lastStartX / (w /100);
            if (p > 80) {
              if( $('sidebar-left').hasClassName('active')) {
                    toggleSidebar('sidebar-left');
                }
                else if ( !$('sidebar-right').hasClassName('active')) {
                  toggleSidebar('sidebar-right');
              }
            }
        });
        swipeMain.observe("swipe:right",function() {
            p = swipeMainObj.lastStartX / (w /100);
            if (p < 20) {
                if ($('sidebar-right').hasClassName('active')) {
                    toggleSidebar('sidebar-right');
                }
                else if (!$('sidebar-left').hasClassName('active')) {
                    toggleSidebar('sidebar-left');
                }
            }

        });

        var swipeSidebarLeft = $('sidebar-left');
        var swipeSidebarLeftObj = new Swipeable(swipeSidebarLeft);
        swipeSidebarLeft.observe("swipe:left",function() {
            p = swipeSidebarLeftObj.lastStartX / (w /100);
            if (p > 80) {
                if( $('sidebar-left').hasClassName('active')) {
                    toggleSidebar('sidebar-left');
                }
            }
        });

        var swipeSidebarRight = $('sidebar-right');
        var swipeSidebarRightObj = new Swipeable(swipeSidebarRight);
        swipeSidebarRight.observe("swipe:right",function() {
            p = swipeSidebarRightObj.lastStartX / (w /100);
            if (p < 20) {
                if( $('sidebar-right').hasClassName('active')) {
                    toggleSidebar('sidebar-right');
                }
            }
        });

    });

</script>
    <div id="container">
        <div id="sidebar-left" class="sidebar">
            <div class="sidebar-inner" style="overflow:scroll;">
                <ul>
                    <li><i class="fa fa-user"></i> Ricardo Matters</li>
                    <li><i class="fa fa-heartbeat"></i> Menu item 2</li>
                    <li><i class="fa fa-line-chart"></i> Trends</li>
                    <li><i class="fa fa-facebook-official"></i> Facebook</li>
                    <li><i class="fa fa-twitter"></i> Twitter</li>
                </ul>

                <ul>
                    <li><i class="fa fa-flag"></i> Doelen</li>
                    <li><i class="fa fa-fire"></i> Energie</li>
                </ul>

                <ul>
                    <li><i class="fa fa-search"></i> Zoek teamgenoten</li>
                    <li><i class="fa fa-cogs"></i> Instellingen</li>
                    <li><i class="fa fa-question"></i> Hulp</li>
                    <li><i class="fa fa-question"></i> Hulp</li>
                    <li><i class="fa fa-question"></i> Hulp</li>
                    <li><i class="fa fa-question"></i> Hulp</li>
                    <li><i class="fa fa-question"></i> Hulp</li>
                    <li><i class="fa fa-question"></i> Hulp</li>
                    <li><i class="fa fa-question"></i> Hulp</li>
                    <li><i class="fa fa-question"></i> Hulp</li>
                </ul>
            </div>
        </div>
        <div id="sidebar-right" class="sidebar" style="overflow:scroll;">
            <div class="sidebar-inner">
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam ut cursus elit. Etiam vel urna sodales, mollis dolor et, euismod odio. Praesent ut pretium magna, id tempor quam. Nam volutpat sollicitudin semper. Nullam vulputate viverra lectus id placerat. Vivamus eget vehicula tellus. Vivamus nisi nunc, feugiat vitae consectetur fermentum, pellentesque sit amet tortor. Cras quis metus nec est tristique suscipit at sed massa. Etiam a nulla sit amet lectus euismod condimentum. Ut accumsan est augue, sit amet sollicitudin nisi aliquam non. Mauris ac neque nec dui dapibus egestas at in urna. Donec in magna nunc.</p>
                <p>Vestibulum iaculis ac risus elementum sagittis. Sed a dolor et lorem aliquam rutrum eu vel quam. Sed quis fermentum velit, non dignissim lectus. Integer vestibulum fringilla aliquam. Fusce gravida nulla ex. Vivamus imperdiet vel magna sed semper. Etiam vel ligula massa. Integer dapibus eu libero ac lacinia. Vestibulum sodales risus ac mi viverra gravida. Vestibulum tincidunt volutpat egestas. Aliquam quis neque quis eros tincidunt consequat sit amet eu erat. Mauris non nisl eros. Ut leo sapien, tincidunt nec blandit posuere, efficitur a elit.</p>
            </div>
        </div>
        <div id="main" onclick="clearSidebars();">
        <h1>Swipeable sidebar test</h1>
            <div id="log"></div>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam ut cursus elit. Etiam vel urna sodales, mollis dolor et, euismod odio. Praesent ut pretium magna, id tempor quam. Nam volutpat sollicitudin semper. Nullam vulputate viverra lectus id placerat. Vivamus eget vehicula tellus. Vivamus nisi nunc, feugiat vitae consectetur fermentum, pellentesque sit amet tortor. Cras quis metus nec est tristique suscipit at sed massa. Etiam a nulla sit amet lectus euismod condimentum. Ut accumsan est augue, sit amet sollicitudin nisi aliquam non. Mauris ac neque nec dui dapibus egestas at in urna. Donec in magna nunc.</p>
            <p>Vestibulum iaculis ac risus elementum sagittis. Sed a dolor et lorem aliquam rutrum eu vel quam. Sed quis fermentum velit, non dignissim lectus. Integer vestibulum fringilla aliquam. Fusce gravida nulla ex. Vivamus imperdiet vel magna sed semper. Etiam vel ligula massa. Integer dapibus eu libero ac lacinia. Vestibulum sodales risus ac mi viverra gravida. Vestibulum tincidunt volutpat egestas. Aliquam quis neque quis eros tincidunt consequat sit amet eu erat. Mauris non nisl eros. Ut leo sapien, tincidunt nec blandit posuere, efficitur a elit.</p>
            <p>Sed porta feugiat arcu in malesuada. Mauris sem sapien, accumsan facilisis rutrum vitae, sollicitudin ac neque. Quisque id convallis erat, nec consectetur odio. Praesent fringilla ante vitae dolor dictum pellentesque. Nam ullamcorper nisl sit amet ullamcorper viverra. Mauris accumsan risus placerat metus laoreet, aliquet venenatis nisi consectetur. Nulla non sapien ac metus imperdiet vehicula. Proin nisl libero, feugiat non libero sed, accumsan viverra tortor.</p>
            <p>Aliquam a magna at lacus lobortis consectetur id sit amet nisi. Donec nec aliquam tortor, quis iaculis dui. Integer sapien libero, porttitor id lobortis in, finibus quis lorem. Ut eget dolor faucibus, malesuada eros nec, feugiat magna. Etiam nec lacinia ligula. Morbi vehicula molestie vulputate. Morbi dapibus eros non velit maximus, non placerat diam commodo.</p>
            <p>Aenean egestas convallis mi aliquam ultricies. Praesent fermentum sapien faucibus sollicitudin varius. Nam sollicitudin placerat justo nec suscipit. Sed dictum ultricies leo, sit amet vestibulum ex molestie quis. Proin semper lacinia dolor, quis ornare odio aliquam at. Duis tincidunt diam eget tortor elementum, ac ornare libero vehicula. Vivamus porta varius tincidunt. Morbi viverra arcu vel tincidunt dictum. Aenean tristique ut lacus vel rutrum. Etiam condimentum libero lectus, at placerat tellus facilisis et. Phasellus tempus fringilla consequat. Nulla malesuada sem dui, eget eleifend arcu dignissim vitae.</p>
        </div>
    </div>
</body>
</html>
