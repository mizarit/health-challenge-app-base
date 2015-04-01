<!DOCTYPE html>
<style type="text/css">
    body {
        background: #f0f9ff;
    }
    .parallax {
        height: 100vh;
        overflow-x: hidden;
        overflow-y: auto;
        -webkit-perspective: 1px;
        perspective: 1px;
    }

    .parallax__layer {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
    }

    .parallax__layer--base {
        -webkit-transform: translateZ(0);
        transform: translateZ(0);
        opacity: 0.9;
    }

    .parallax__layer--cloud-1 {
        -webkit-transform: translateZ(-5px);
        transform: translateZ(-5px) scale(7);
        background: url(/img/cloud-2.png);
        height:700%;
    }
    .parallax__layer--cloud-2 {
        -webkit-transform: translateZ(-10px);
        transform: translateZ(-10px) scale(12);
        background: url(/img/cloud-1.png);
        height:700%;
    }
    .parallax__layer--deep {
        /*-webkit-transform: translateZ(-2px);*/
        /*transform: translateZ(-2px);*/

        width: 300%;
        height: 500%;
        background: rgb(161,219,255); /* Old browsers */
        background: -moz-linear-gradient(top,  rgba(161,219,255,1) 0%, rgba(203,235,255,1) 53%, rgba(240,249,255,1) 100%); /* FF3.6+ */
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(161,219,255,1)), color-stop(53%,rgba(203,235,255,1)), color-stop(100%,rgba(240,249,255,1))); /* Chrome,Safari4+ */
        background: -webkit-linear-gradient(top,  rgba(161,219,255,1) 0%,rgba(203,235,255,1) 53%,rgba(240,249,255,1) 100%); /* Chrome10+,Safari5.1+ */
        background: -o-linear-gradient(top,  rgba(161,219,255,1) 0%,rgba(203,235,255,1) 53%,rgba(240,249,255,1) 100%); /* Opera 11.10+ */
        background: -ms-linear-gradient(top,  rgba(161,219,255,1) 0%,rgba(203,235,255,1) 53%,rgba(240,249,255,1) 100%); /* IE10+ */
        background: linear-gradient(to bottom,  rgba(161,219,255,1) 0%,rgba(203,235,255,1) 53%,rgba(240,249,255,1) 100%); /* W3C */
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#a1dbff', endColorstr='#f0f9ff',GradientType=0 ); /* IE6-9 */



    }

    * {
        margin:0;
        padding:0;

        font-family: 'Lato', sans-serif;
        font-size: 36px;
        font-style: normal;
        font-weight: 300;
    }

    p {
        margin-bottom:20px;
    }

    h1{
        font-weight:800;
        margin-bottom: 20px;
        text-align: center;
    }
    .parallax {
        font-size: 200%;
    }

    /* add some padding to force scrollbars */
    .parallax__layer {
        /*padding: 100vh 0;*/
    }

    /* centre the content in the parallax layers */
    .title {
        position: absolute;
        left: 50%;
        top: 50%;
        -webkit-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
    }


    .sunburst {
        width: 100%;
        height: 670px;
        margin:-11px -11px -11px -11px;
        overflow: hidden;
        border-radius: 1em;
        background: #fec983;
    }
    .sunburst .outer {
        width: 3000px;
        height: 910px;
        padding-top: 1090px;
        margin: -800px 0 0 -940px;
        -webkit-animation-name: rotate1;
        -webkit-animation-duration:2s;
        -webkit-animation-iteration-count:infinite;
        -webkit-animation-timing-function:linear;
        -moz-animation-name: rotate1;
        -moz-animation-duration:2s;
        -moz-animation-iteration-count:infinite;
        -moz-animation-timing-function:linear;
    }
    .sunburst b {
        display: block;
        width: 0;
        height: 0;
        border-width: 90px 1500px;
        margin: -180px 0 0 0;
        border-color: transparent #fb985d;
        border-style: solid;
    }
    .sunburst b:nth-child(1) { -webkit-transform:rotate(20deg);  -moz-transform:rotate(20deg);  }
    .sunburst b:nth-child(2) { -webkit-transform:rotate(40deg);  -moz-transform:rotate(40deg);  }
    .sunburst b:nth-child(3) { -webkit-transform:rotate(60deg);  -moz-transform:rotate(60deg);  }
    .sunburst b:nth-child(4) { -webkit-transform:rotate(80deg);  -moz-transform:rotate(80deg);  }
    .sunburst b:nth-child(5) { -webkit-transform:rotate(100deg); -moz-transform:rotate(100deg); }
    .sunburst b:nth-child(6) { -webkit-transform:rotate(120deg); -moz-transform:rotate(120deg); }
    .sunburst b:nth-child(7) { -webkit-transform:rotate(140deg); -moz-transform:rotate(140deg); }
    .sunburst b:nth-child(8) { -webkit-transform:rotate(160deg); -moz-transform:rotate(160deg); }
    .sunburst b:nth-child(9) { -webkit-transform:rotate(180deg); -moz-transform:rotate(180deg); }
    @-webkit-keyframes rotate1 {
        from { -webkit-transform: rotate(0deg); }
        to   { -webkit-transform: rotate(20deg); }
    }
    @-moz-keyframes rotate1 {
        from { -moz-transform: rotate(0deg); }
        to   { -moz-transform: rotate(20deg); }
    }
</style>
<link href='https://fonts.googleapis.com/css?family=Lato:100,300,400,700,300italic' rel='stylesheet' type='text/css'>
<div class="parallax">
    <div class="parallax__group">
        <div class="parallax__layer parallax__layer--deep"></div>
        <div class="parallax__layer parallax__layer--cloud-1"></div>
        <div class="parallax__layer parallax__layer--cloud-2"></div>

        <div class="parallax__layer parallax__layer--base">
            <div style="position: absolute;top:0;width:90%;padding: 0 5%;">
                <div style="margin:0 auto;margin-top:2em;">
                    <div class="sunburst">
                        <div class="outer">
                            <b></b><b></b><b></b><b></b><b></b><b></b><b></b><b></b><b></b><b></b>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
</div>
