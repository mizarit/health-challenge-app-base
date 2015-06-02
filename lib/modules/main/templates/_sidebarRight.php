<div id="sidebar-right" class="sidebar">
  <div id="sidebar-right-inner" class="sidebar-inner" style="overflow:scroll;">

    <ul>
      <li>
        <div style="position: relative;width:95%;" id="chat-input">
          <form action="#" method="post" onsubmit="sendChat();return false;">
            <input id="chat-text" autocomplete="off" type="text" style="margin:0.1em 0 0 0;width:99%;font-size:1.2em;padding:0.1em 0.2em;border-radius:0.3em;">
            <i id="chat-enter" style="position:absolute;width:auto;right:-0.1em;left:auto;float:none;display:inline;top:0.15em;font-size: 1.6em;color:#aaa;" class="fa fa-caret-square-o-down"></i>
            <button type="submit" style="display:none;"></button>
          </form>
        </div>
      </li>
      <li style="height: auto;">
        <div id="chat-stream">
          <ul>
          </ul>
        </div>
      </li>
    </ul>

  </div>
</div>